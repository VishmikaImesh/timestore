<?php

require_once(__DIR__ . '/env.php');

class Database{

    public static $connection;
    private static $config = null;

    private static function getConfig() {
        if (self::$config === null) {
            self::$config = [
                'host' => getenv('DB_HOST') ?: '127.0.0.1',
                'user' => getenv('DB_USER') ?: 'root',
                'pass' => getenv('DB_PASS') ?: '',
                'name' => getenv('DB_NAME') ?: 'timestore',
                'port' => getenv('DB_PORT') ?: 3306
            ];
        }
        return self::$config;
    }

    public static function setUpconnection(){
        if(!isset(self::$connection)){
            $config = self::getConfig();
            
            self::$connection = new mysqli(
                $config['host'],
                $config['user'],
                $config['pass'],
                $config['name'],
                $config['port']
            );
            
            // Check connection
            if (self::$connection->connect_error) {
                error_log("Database connection failed: " . self::$connection->connect_error);
                // Don't expose connection details to users
                throw new Exception("Database connection failed. Please try again later.");
            }
            
            // Set charset to prevent encoding issues
            self::$connection->set_charset("utf8mb4");
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

    public static function escape($value){
        self::setUpconnection();
        return self::$connection->real_escape_string($value);
    }


}

?>