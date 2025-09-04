<?php 
require '.gitignore/config.php';
class Database{

    public static $connection;

    public static function setUpconnection(){
        global $host,$db,$user,$pw;
        if(!isset(self::$connection)){
            self::$connection= new mysqli("localhost","root","Imesh#14681","timestore",3306);
        }
    }

    public static function iud($q){
        self::setUpconnection();
        return self::$connection->query($q);
    }

    public static function search($q){
        self::setUpconnection();
        $resultset=self::$connection->query($q);
        return $resultset;
    }


}

?>