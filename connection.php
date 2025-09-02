<?php 
require '.gitignore\config.php';
class Database{

    public static $connection;

    public static function setUpconnection(){
        global $host,$db,$user,$pw;
        if(!isset($connection)){
            Database::$connection= new mysqli($host,$user,$pw,$db,3306);
        }
    }

    public static function iud($q){
        Database::setUpconnection();
        Database::$connection->query($q);
    }

    public static function search($q){
        Database::setUpconnection();
        $resultset=Database::$connection->query($q);
        return $resultset;
    }


}

?>