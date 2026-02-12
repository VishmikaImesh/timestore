<?php

include "connection.php";

session_start();

if(isset($_POST["id"])){
    Database::iud("DELETE FROM `user_history` WHERE `id`='".$_POST["id"]."' ");
}


?>