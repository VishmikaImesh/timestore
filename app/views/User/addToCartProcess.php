<?php

include "connection.php";

session_start();

if (isset($_POST["id"])) {
    $pid = $_POST["id"];
    $qty = $_POST["qty"];
    $email = $_SESSION["u"]["email"];


    $cart_rs = Database::search("SELECT * FROM `cart` WHERE `product_id`='" . $pid . "' AND `users_email`='" . $email . "'  ");
    $cart_num=$cart_rs->num_rows;
    $cart_data = $cart_rs->fetch_assoc();

    $pqty_rs = Database::search("SELECT * FROM `product_has_model` WHERE `model_id`='" . $pid . "' ");
    $pqty_data = $pqty_rs->fetch_assoc();
    $new_pqty = intval($pqty_data["qty"]) - intval($qty);

    Database::iud("UPDATE `product_has_model` SET `qty`='" . $new_pqty . "' WHERE `model_id`='" . $pid . "' ");
    

    if ($cart_num == 0) {

        Database::iud("INSERT INTO `cart`(`product_id`,`users_email`,`cart_qty`) VALUE('" . $pid . "','" . $email . "','" . $qty . "') ");

        echo ("success");

    }else{

        $new_cart_qty=$cart_data["cart_qty"]+$qty;

        Database::iud("UPDATE `cart` SET `cart_qty`='".$new_cart_qty."' WHERE `users_email`='".$email."' ");

        echo("success");

    }
}
