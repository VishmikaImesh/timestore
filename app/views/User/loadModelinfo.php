<?php

include "connection.php" ;

if(isset($_POST["id"])){
    $id=$_POST["id"];

    $model_rs=Database::search("SELECT * FROM `model_data` WHERE `model_id`=$id ");
    $model_data=$model_rs->fetch_assoc();

    echo json_encode($model_data);
}

?>