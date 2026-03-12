<?php

require_once(BASE . "/config/connection.php");

class history
{
    public function removeHistoryItem($data)
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
                echo "error";
                return;
            }

            $id = intval($data["id"] ?? 0);
            if ($id <= 0) {
                echo "error";
                return;
            }

            $email = Database::escape($_SESSION["u"]["email"]);
            Database::iud("DELETE FROM `user_history` WHERE `id`='" . $id . "' AND `user_id`='" . $email . "' ");

            echo "success";
        } catch (Exception $e) {
            echo "error";
        }
    }
}
