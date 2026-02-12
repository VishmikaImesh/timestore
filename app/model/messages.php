<?php

require_once(BASE."/config/connection.php");

class messages
{
    function loadMsgSenders()
    {
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
    }

    function loadMessages()
    {

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
    }

    function loadUserMessages($data)
    {

        $user_rs = Database::search("SELECT 
        `sender`,
        `fname`,    
        `lname`,
        `email`,
        MAX(date_time) AS date_time,
        COUNT(case when messages.`status`=2 then 1 END) AS new_msg
        FROM messages
        JOIN users ON `messages`.`sender` = `users`.`email`
        WHERE `email`='" . $data["sender"] . "'
        GROUP BY `sender`  ");
        while ($user_data = $user_rs->fetch_assoc()) {

            $user_info = [
                "fname" => $user_data["fname"],
                "lname" => $user_data["lname"],
                "new_msg" => $user_data["new_msg"],
                "email" => $user_data["email"]
            ];

            $messages_rs = Database::search("SELECT * FROM `messages` WHERE `sender`='" . $user_data["email"] . "' ORDER BY `date_time` DESC ");
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
        }


        echo json_encode([
            "sender" => $user_info,
            "messages" => $messages
        ]);
    }

    function changeMessageState($data)
    {
        $message_id = $data["message_id"];
        Database::iud("UPDATE `messages` SET `status`='1' WHERE `message_id`='" . $message_id . "' ");
    }
}
