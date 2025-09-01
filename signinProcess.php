<?php

include "connection.php";

$email=$_POST["e"];
$password=$_POST["p"];

$user_rs=Database::search("SELECT * FROM `users` WHERE `email`='".$email."' AND `password`='".$password."' ");

if($user_rs->num_rows==1){

    session_start();
    $_SESSION["u"]=$user_rs->fetch_assoc();    

    
    if($_POST["r"]==1){
        setcookie("email",$email,time()+60*60*24*7);
        setcookie("pw",$password,time()+60*60*24*7);
    }
       

    echo("success");

}



?>