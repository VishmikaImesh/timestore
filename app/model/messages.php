<?php

require_once(BASE."/config/connection.php");

class messages
{
    function loadMsgSenders()
    {
        try {
            $msg_senders_rs = Database::search("SELECT `sender`,
		`fname`,
		`lname`,
        MAX(`date_time`) as `date_time`,
        COUNT(case when `messages`.`status`=2 then 1  END) AS new_msg
        FROM messages JOIN users
        ON `messages`.`sender`=`users`.`email`
        GROUP BY `sender`
        ORDER BY `date_time` desc ");
        $msg_senders = [];
        while ($msg_senders_data = $msg_senders_rs->fetch_assoc()) {
            $msg_senders[] = [
                "sender" => $msg_senders_data["sender"],
                "fname" => $msg_senders_data["fname"],
                "lname" => $msg_senders_data["lname"],
                "date" => date("M d ,Y", strtotime($msg_senders_data["date_time"])),
                "new_msg" => $msg_senders_data["new_msg"]
            ];
        }
            echo json_encode(
                $msg_senders
            );
        } catch (Exception $e) {
            echo json_encode(["error" => "Error loading message senders: " . $e->getMessage()]);
        }
    }

    function loadMessages()
    {

        try {
            $sender_rs = Database::search("SELECT DISTINCT(`sender`) FROM `messages` ");
            $user_messages = [];

        while ($sender_data = $sender_rs->fetch_assoc()) {
            $messages_rs = Database::search("SELECT * FROM `messages` WHERE `sender`='" . $sender_data["sender"] . "' ORDER BY `date_time` DESC ");
            $user = $sender_data["sender"];
            $messages = [];
            while ($messages_data = $messages_rs->fetch_assoc()) {
                $messages[] = [
                    "message_id" => $messages_data["message_id"],
                    "subject" => $messages_data["subject"],
                    "message" => $messages_data["message"],
                    "status" => $messages_data["status"]
                ];
            };
            $user_messages[] = [
                "sender" => $user,
                "messages" => $messages
            ];
        }

            echo json_encode(
                $user_messages
            );
        } catch (Exception $e) {
            echo json_encode(["error" => "Error loading messages: " . $e->getMessage()]);
        }
    }

    function loadUserMessages($data)
    {
        try {
            if (!isset($data["sender"])) {
                echo json_encode(["error" => "Sender Not Found"]);
                return;
            }

        $sender = Database::escape($data["sender"]);

        $user_rs = Database::search("SELECT 
        `sender`,
        `fname`,    
        `lname`,
        `email`,
        MAX(date_time) AS date_time,
        COUNT(case when messages.`status`=2 then 1 END) AS new_msg
        FROM messages
        JOIN users ON `messages`.`sender` = `users`.`email`
        WHERE `email`='" . $sender . "'
        GROUP BY `sender`  ");

        // Initialize variables before the loop to prevent undefined variable warnings
        $user_info = null;
        $messages = [];

        if ($user_rs->num_rows > 0) {
            $user_data = $user_rs->fetch_assoc();

            $user_info = [
                "fname" => $user_data["fname"],
                "lname" => $user_data["lname"],
                "new_msg" => $user_data["new_msg"],
                "email" => $user_data["email"]
            ];

            $user_email = Database::escape($user_data["email"]);
            $messages_rs = Database::search("SELECT * FROM `messages` WHERE `sender`='" . $user_email . "' ORDER BY `date_time` DESC ");
            $messages = [];
            while ($messages_data = $messages_rs->fetch_assoc()) {
                $messages[] = [
                    "message_id" => $messages_data["message_id"],
                    "subject" => $messages_data["subject"],
                    "message" => $messages_data["message"],
                    "date" => date("M d ,Y", strtotime($messages_data["date_time"])),
                    "time" => date("h:i A", strtotime($messages_data["date_time"])),
                    "status" => $messages_data["status"]
                ];
            }
        } else {
            echo json_encode([
                "state" => false,
                "message" => "No sender found"
            ]);
            return;
        }

            echo json_encode([
                "state" => true,
                "sender" => $user_info,
                "messages" => $messages
            ]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error loading user messages: " . $e->getMessage()]);
        }
    }

    function changeMessageState($data)
    {
        try {
            $message_id = Database::escape($data["message_id"] ?? "");
            if ($message_id === "") {
                return;
            }
            Database::iud("UPDATE `messages` SET `status`='1' WHERE `message_id`='" . $message_id . "' ");
        } catch (Exception $e) {
            echo json_encode(["error" => "Error changing message state: " . $e->getMessage()]);
        }
    }
}
