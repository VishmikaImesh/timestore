<?php

include "connection.php" ;

if(isset($_POST["id"])){
    $id=$_POST["id"];

    $model_rs=Database::search("SELECT * FROM `product_has_model` JOIN `product_img` ON `product_has_model`.`model_id`=`product_img`.`product_id` WHERE `model_id`=$id ");
    $model_data=$model_rs->fetch_assoc();

    echo json_encode($model_data);

}


?>