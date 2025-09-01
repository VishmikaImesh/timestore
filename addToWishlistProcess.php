<?php

include "connection.php";

session_start();

if (isset($_GET["id"])) {
    $pid = $_GET["id"];
    $email = $_SESSION["u"]["email"];

    $cart_rs = Database::search("SELECT * FROM `watchlist` WHERE `product_id`='" . $pid . "' AND `users_email`='" . $email . "' ");
    $cart_num =$cart_rs->num_rows;

    if ($cart_num == 0) {

        Database::iud("INSERT INTO `watchlist`(`product_id`,`users_email`) VALUE('" . $pid . "','" . $email . "') ");
        echo ("Product added to watchlist Successfully...!");

    }else{
        Database::iud("DELETE FROM `watchlist` WHERE `product_id`='" . $pid."' AND `users_email`='".$email."' " );
        echo ("Product removed from watchlist Successfully...!");
    }

}
