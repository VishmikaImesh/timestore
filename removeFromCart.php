<?php

include "connection.php";

session_start();

$email=$_SESSION["u"]["email"];
$cid=$_GET["cartId"];
$pid=$_GET["pid"];


$cart_rs=Database::search("SELECT * FROM `cart` WHERE `cart_id`='".$cid."'  ");
$cart_data=$cart_rs->fetch_assoc();
$cart_qty=$cart_data["cart_qty"];

$pqty_rs=Database::search("SELECT `qty` FROM `product` WHERE `id`='".$pid."' ");
$pqty_data=$pqty_rs->fetch_assoc();
$pqty=$pqty_data["qty"];

$new_pqty=intval($cart_qty)+intval($pqty);

Database::iud("UPDATE `product` SET `qty`='" . $new_pqty . "' WHERE `id`='" . $pid . "' ");
Database::iud("DELETE FROM `cart` WHERE `cart_id`='".$cid."' ");



?>