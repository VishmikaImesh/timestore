<?php

require_once(BASE."/config/connection.php");

class delivery
{
    function loadDeliveryDetails()
    {
        try {
            $delivery_rs = Database::search("SELECT * FROM `delivery_method`");

        while ($delivery_data = $delivery_rs->fetch_assoc()) {
            $delivery[] = [
                "id" => $delivery_data["id"],
                "method" => $delivery_data["delivery_method"],
                "price" => $delivery_data["price"],
                "delivery_days" => date("F-d", strtotime('+' .$delivery_data["delivery_days"] . 'days'))
            ];
        };

        echo json_encode(
            $delivery
        );
        } catch (Exception $e) {
            echo json_encode(["error" => "Error loading delivery methods: " . $e->getMessage()]);
        }
    }

    function updateDeliveryDetails($data){
        try {
            echo ($data["new_price"]);

        Database::iud("UPDATE `delivery_method` SET `price`='" .$data["new_price"] . "', `delivery_days`='" . $data["new_days"] . "' WHERE `id`='" . $data["id"] . "' ");
        echo json_encode(
            ["status" => "success"]
        );
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Error updating delivery: " . $e->getMessage()]);
        }
    }

    function deleteDeliveryDetails($id){
        try {
            Database::iud("DELETE FROM `delivery_method` WHERE `id`='" . $id . "' ");
        echo json_encode(
            ["status" => "success"]
        );
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Error deleting delivery: " . $e->getMessage()]);
        }
    }
}
