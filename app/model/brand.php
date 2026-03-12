<?php

require_once(BASE . "/config/connection.php");

class brand
{

    public function load()
    {
        try {
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
        } catch (Exception $e) {
            echo json_encode(["error" => "Error loading brands: " . $e->getMessage()]);
        }
    }

    public function add(){
        try {
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
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error adding brand: " . $e->getMessage()]);
        }
    }
}
