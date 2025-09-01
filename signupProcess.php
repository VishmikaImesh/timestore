<?php

include "connection.php";

$fname=$_POST["f"];
$lname=$_POST["l"];
$email=$_POST["e"];
$mobile=$_POST["m"];
$pw=$_POST["pw"];
$pwa=$_POST["pwa"];
$gender=$_POST["g"];

$x=0;

if(empty($fname)){
    echo("a");
    $x++;
}elseif(strlen($fname)>50){
    echo("b");
    $x++;
}

if(empty($lname)){
    echo("c");
    $x++;
}elseif(strlen($lname)>50){
    echo("d");
    $x++;
}


if(empty($pw)){
    echo("e");
    $x++;
}elseif(strlen($pw)<5 || strlen($pw)>20){
    echo("f");
    $x++;
}


if(empty($pwa)){
    echo("g");
    $x++;
}
elseif($pw!=$pwa){
    echo("h");
    $x++;
}

if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo("i");
    $x++;
}

if(empty($mobile)){
    echo("j");
    $x++;
}

if($x==0){

    Database::iud("INSERT INTO `users`(`fname`,`lname`,`password`,`mobile`,`gender_id`,`email`) VALUES('".$fname."','".$lname."','".$pw."','".$mobile."','".$gender
    ."','".$email."'); ");    

    echo("success");

}





?>