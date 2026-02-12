<?php

session_start();

if (isset($_POST["id"]) && isset($_POST["qty"]) && isset($_POST["delivery_method_id"])) {
    include "connection.php";

    $model_id = $_POST["id"];
    $qty = $_POST["qty"];
    $delivery_method_id = $_POST["delivery_method_id"];
    $email = $_SESSION["u"]["email"];

    $users_rs = Database::search("SELECT * FROM `users` WHERE `email`= '" . $email . "' ");
    $user_data = $users_rs->fetch_assoc();

    $address_rs = Database::search("SELECT * FROM `user_address_data` WHERE `email`='" . $email . "' ");
    $address_data = $address_rs->fetch_assoc();

    $delivery_rs = Database::search("SELECT * FROM `delivery_method`  WHERE `id`='" . $delivery_method_id . "' ");
    $delivery_data = $delivery_rs->fetch_assoc();

    $product_rs = Database::search("SELECT * FROM `model_data` WHERE `model_id`='" . $model_id . "' ");
    $product_data = $product_rs->fetch_assoc();



    if ($product_data != null && $product_data["qty"] >= $qty) {
        $price = $product_data["price"];
        $deliver_fee = $delivery_data["price"];
        $amount = $price * $qty + $deliver_fee;

        $merchant_id = "1226402";
        $currency = "LKR";
        $merchant_secret = "NDI3NjU0NDIwMzIxMDM5NzMxMTM0MTQ4MzY3NjY5MzQ1MzkxNzIwNw==";
        $order_id = str_pad(mt_rand(0, 999999999), 10, '0', STR_PAD_LEFT);
        $hash = strtoupper(
            md5(
                $merchant_id .
                    $order_id .
                    number_format($amount, 2, '.', '') .
                    $currency .
                    strtoupper(md5($merchant_secret))
            )
        );

        $invoice_id = str_pad(mt_rand(0, 999999999), 10, '0', STR_PAD_LEFT);

        $new_qty = intval($product_data["qty"])-intval($qty);

        Database::iud("UPDATE `product_has_model` SET `qty` =$new_qty WHERE `model_id`=$model_id ");
        Database::iud("INSERT INTO `order`(`order_id`,`email`,`delivery_method`,`order_status`) VALUES($order_id,'" . $email . "',$delivery_method_id,1) ");
        Database::iud("INSERT INTO `order_has_model`(`order_id`,`model_id`,`qty`) VALUE($order_id,$model_id,$qty)");
        Database::iud("INSERT INTO `invoice`(`invoice_id`,`order_id`,`email`,`delivery_fee`) VALUES($invoice_id,$order_id , '" . $email . "',$deliver_fee) ");
        Database::iud("INSERT INTO `invoice_items`(`invoice_id`,`order_id`,`product_id`,`product_name`,`product_price`,`qty`) VALUE($invoice_id,$order_id,'" . $product_data["model_id"] . "','" . $product_data["model"] . "',$price,$qty)");

        $data = [
            "merchant_id" => $merchant_id,
            "hash" => $hash,
            "amount" => $amount,
            "first_name" => $user_data["fname"],
            "last_name" => $user_data["lname"],
            "email" => $user_data["email"],
            "phone" => $user_data["mobile"],
            "items" => $product_data["model"],
            "currency" => $currency,
            "order_id" => $order_id,
            "address" => $address_data["address_line1"] . " , " . $address_data["address_line2"],
            "city" => $address_data["city_en"],
            "country" => "Sri Lanka",
        ];

        $jsonString = json_encode($data);
        echo $jsonString;
    }
} else {
    echo ($_SESSION["u"]["email"]);
}
