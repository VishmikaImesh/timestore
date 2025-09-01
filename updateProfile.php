<?php

include "connection.php";

$fn = $_POST["fn"];
$ln = $_POST["ln"];
$em = $_POST["e"];
$mb = $_POST["mb"];

$ad1 = $_POST["ad1"];
$ad2 = $_POST["ad2"];
$city = $_POST["city"];

$address_rs = Database::search("SELECT * FROM `user_address` WHERE `users_email`='" . $em . "' ");
$address_num = $address_rs->num_rows;

if ($address_num == 1) {
    Database::iud("UPDATE `user_address` SET `address_line1`='" . $ad1 . "',`address_line2`='" . $ad2 . "',`cities_id`='" . $city . "' WHERE `users_email`='" . $em . "' ");
} else {
    Database::iud("INSERT INTO `user_address` (`address_line1`, `address_line2`,`cities_id`,`users_email`) VALUES('" . $ad1 . "','" . $ad2 . "','" . $city . "','" . $em . "') WHERE `users_email`='" . $em . "' ");
}
