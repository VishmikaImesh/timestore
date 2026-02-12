<?php
session_start();
include "connection.php";

$email = $_SESSION["u"]["email"];
$orderId = $_POST["orderId"];

$order_rs = Database::search("SELECT * FROM `order_data` WHERE `email`='" . $email . "' AND `order_id`='" . $orderId . "' ");

if ($order_rs != null) {

    $userDetails_rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "'");
    $userDetails_data = $userDetails_rs->fetch_assoc();

    $orders = [];

    $order_num = $order_rs->num_rows;
    for ($i = 0; $i < $order_num; $i++) {
        $order_data = $order_rs->fetch_assoc();
        $orders[] = [
            "img_path" => $order_data["img_path"],
            "product_name" => $order_data["product_name"],
            "model" => $order_data["model"],
            "qty" => $order_data["qty"],
            "price" => $order_data["price"]
            
        ];
    }

    $userDetails = [
        "email" => $userDetails_data["email"],
        "order_id" => $orderId
    ];

    echo json_encode([
        "status"=>true,
        "user" => $userDetails,
        "orders" => $orders
    ]);
}
