<?php

require_once(BASE . "/config/connection.php");

class cart
{
    public function addToCart($data)
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
            echo "error";
            return;
        }

        $model_id = intval($data["id"] ?? 0);
        $qty = intval($data["qty"] ?? 0);
        if ($model_id <= 0 || $qty <= 0) {
            echo "error";
            return;
        }

        $email = Database::escape($_SESSION["u"]["email"]);

        $pqty_rs = Database::search("SELECT `qty` FROM `product_has_model` WHERE `model_id`='" . $model_id . "' ");
        if (!$pqty_rs || $pqty_rs->num_rows === 0) {
            echo "error";
            return;
        }

        $pqty_data = $pqty_rs->fetch_assoc();
        $available_qty = intval($pqty_data["qty"]);
        if ($available_qty < $qty) {
            echo "error";
            return;
        }

        $new_pqty = $available_qty - $qty;
        Database::iud("UPDATE `product_has_model` SET `qty`='" . $new_pqty . "' WHERE `model_id`='" . $model_id . "' ");

        $cart_rs = Database::search("SELECT * FROM `cart` WHERE `product_id`='" . $model_id . "' AND `users_email`='" . $email . "' ");
        $cart_num = $cart_rs->num_rows;

        if ($cart_num == 0) {
            Database::iud("INSERT INTO `cart`(`product_id`,`users_email`,`cart_qty`) VALUE('" . $model_id . "','" . $email . "','" . $qty . "') ");
            echo "success";
            return;
        }

        $cart_data = $cart_rs->fetch_assoc();
        $new_cart_qty = intval($cart_data["cart_qty"]) + $qty;
        Database::iud("UPDATE `cart` SET `cart_qty`='" . $new_cart_qty . "' WHERE `users_email`='" . $email . "' AND `product_id`='" . $model_id . "' ");

        echo "success";
        } catch (Exception $e) {
            echo "error";
        }
    }

    public function removeFromCart($data)
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
            echo "error";
            return;
        }

        $model_id = intval($data["model_id"] ?? 0);
        if ($model_id <= 0) {
            echo "error";
            return;
        }

        $email = Database::escape($_SESSION["u"]["email"]);

        $cart_rs = Database::search("SELECT * FROM `cart` WHERE `users_email`='" . $email . "' AND `product_id`='" . $model_id . "' ");
        if (!$cart_rs || $cart_rs->num_rows === 0) {
            echo "success";
            return;
        }

        $cart_data = $cart_rs->fetch_assoc();
        $cart_qty = intval($cart_data["cart_qty"]);

        $pqty_rs = Database::search("SELECT `qty` FROM `product_has_model` WHERE `model_id`='" . $model_id . "' ");
        if ($pqty_rs && $pqty_rs->num_rows > 0) {
            $pqty_data = $pqty_rs->fetch_assoc();
            $current_qty = intval($pqty_data["qty"]);
            $new_pqty = $current_qty + $cart_qty;
            Database::iud("UPDATE `product_has_model` SET `qty`='" . $new_pqty . "' WHERE `model_id`='" . $model_id . "' ");
        }

        Database::iud("DELETE FROM `cart` WHERE `users_email`='" . $email . "' AND `product_id`='" . $model_id . "' ");
        echo "success";
        } catch (Exception $e) {
            echo "error";
        }
    }
}
