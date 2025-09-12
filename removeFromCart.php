<?php

include "connection.php";

session_start();

$email=$_SESSION["u"]["email"];
$model_id=$_POST["model_id"];


$cart_rs=Database::search("SELECT * FROM `cart` WHERE `users_email`='".$email."'  ");
$cart_data=$cart_rs->fetch_assoc();
$cart_qty=$cart_data["cart_qty"];

$pqty_rs=Database::search("SELECT `qty` FROM `product_has_model` WHERE `product_id`='".$model_id."' ");
$pqty_data=$pqty_rs->fetch_assoc();
$pqty=$pqty_data["qty"];

$new_pqty=intval($cart_qty)+intval($pqty);

Database::iud("UPDATE `product_has_model` SET `qty`='" . $new_pqty . "' WHERE `product_id`='" . $model_id . "' ");
Database::iud("DELETE FROM `cart` WHERE `users_email`='".$email."' AND `product_id`='".$model_id."' ");



?>