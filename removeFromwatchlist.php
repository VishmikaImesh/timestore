<?php

include "connection.php";

if(isset($_GET["id"])){
    $id = $_GET["id"];

    Database::iud("DELETE FROM `watchlist` WHERE `watchlist_id`='".$id."' ");

    echo("success");
}

?>