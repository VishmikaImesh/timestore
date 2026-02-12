<?php

require_once(BASE . "/config/connection.php");

class brand
{

    public function load()
    {
        $barnd_rs= Database::search("SELECT * FROM `brand`");
        $brands = [];
        while ($brand = $barnd_rs->fetch_assoc()) { 
            $brands[] = [
                "id" => $brand["brand_id"],
                "name" => $brand["brand_name"]
            ];
        }
        echo json_encode([
            "brands" => $brands
        ]);
    }

    public function add(){
        Database::setUpconnection();

        if(!isset($_POST["brand_name"])){
            echo json_encode(["state" => false, "message" => "Missing brand name"]);
            exit;
        }

        $brand_name = $_POST["brand_name"];

        $brand_q = "INSERT INTO `brand` (name) VALUES (?)";
        $brand_s = Database::$connection->prepare($brand_q);
        $brand_s->execute([$brand_name]);

        echo json_encode([
            "state" => true,
            "message" => "Brand added successfully"
        ]);
    }
}
