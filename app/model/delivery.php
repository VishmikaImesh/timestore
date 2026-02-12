<?php

require_once(BASE."/config/connection.php");

class delivery
{
    function loadDeliveryDetails()
    {
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
    }

    function updateDeliveryDetails($data){
        echo ($data["new_price"]);

        Database::iud("UPDATE `delivery_method` SET `price`='" .$data["new_price"] . "', `delivery_days`='" . $data["new_days"] . "' WHERE `id`='" . $data["id"] . "' ");
        echo json_encode(
            ["status" => "success"]
        );
    }

    function deleteDeliveryDetails($id){
        Database::iud("DELETE FROM `delivery_method` WHERE `id`='" . $id . "' ");
        echo json_encode(
            ["status" => "success"]
        );
    }
}
