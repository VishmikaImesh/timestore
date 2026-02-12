<?php 
session_start();

if(isset($_SESSION["u"]) && isset($_POST["orderId"])){
    require("connection.php");

    $email=$_SESSION["u"]["email"];
    $orderId=$_POST["orderId"];

    Database::iud( "DELETE FROM `order` WHERE `email`='".$email."' AND `order_id`='".$orderId."' ");
    Database::iud( "DELETE FROM `invoice` WHERE `email`='".$email."' AND `order_id`='".$orderId."' ");
}