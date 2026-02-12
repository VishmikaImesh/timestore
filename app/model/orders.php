<?php

require_once(BASE . "/config/connection.php");

class orders
{
    public function newOrder()
    {
        if (isset($_POST["id"]) && isset($_POST["qty"]) && isset($_POST["delivery_method_id"])) {
            

            $model_id = $_POST["id"];
            $qty = $_POST["qty"];
            $delivery_method_id = $_POST["delivery_method_id"];
            $email = "imesh@gmail.com";
            

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

                $new_qty = intval($product_data["qty"]) - intval($qty);

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

                echo json_encode($data);
            }
        } else {
            echo "error";
        }
    }

    public function loadOrdersDetails($data)
    {
        Database::setUpconnection();

        $order_id = $data["order_id"];

        $order_q = ("SELECT `email`,`order_id`,`ordered_date`,SUM(`order_qty`*`price`) AS `sub_total`,`delivery_fee`,SUM(`order_qty`*`price`)+`delivery_fee` AS `grand_total` FROM `order_data` WHERE `order_id`=?  GROUP BY `order_id` ");
        $order_rs = $this->getData($order_q, 'i', $order_id);
        $order_data = $order_rs->fetch_assoc();
        $email = $order_data["email"];

        $order = [
            "email" => $order_data["email"],
            "order_id" => $order_data["order_id"],
            "ordered_date" => $order_data["ordered_date"],
            "sub_total" => $order_data["sub_total"],
            "delivery_fee" => $order_data["delivery_fee"],
            "grand_total" => $order_data["grand_total"]
        ];

        $order_items_q = ("SELECT `email`,`product_id`,`product_name`,`model_id`,`model`,`brand_name`,`price`,`order_qty` FROM `order_data` WHERE `order_id`=? ");
        $order_items = [];
        $orderItems_rs = $this->getData($order_items_q, 'i', $order_id);
        while ($orderItems_data = $orderItems_rs->fetch_assoc()) {
            $order_items[] = [
                "product_id" => $orderItems_data["product_id"],
                "product_name" => $orderItems_data["product_name"],
                "model_id" => $orderItems_data["model_id"],
                "model" => $orderItems_data["model"],
                "img_src" => "loadImg.php?model_id=" . $orderItems_data["model_id"],
                "brand" => $orderItems_data["brand_name"],
                "price" => $orderItems_data["price"],
                "qty" => $orderItems_data["order_qty"],
            ];
        }

        $address_q = "SELECT * FROM `user_address_data` WHERE `email`=?";
        $address_rs = $this->getData($address_q, 's', $email);
        $address_data = $address_rs->fetch_assoc();

        if ($address_rs->num_rows > 0) {
            $user_details = [
                "email" => $address_data["email"],
                "first_name" => $address_data["fname"],
                "last_name" => $address_data["lname"],
                "mobile" => $address_data["mobile"],
                "address_line1" => $address_data["address_line1"],
                "address_line2" => $address_data["address_line2"],
                "city" => $address_data["city_en"],
                "district" => $address_data["district_en"],
                "province" => $address_data["province_en"],
            ];

            $this->jsonResponce(true, [
                "user" => $user_details,
                "order" => $order,
                "order_items" => $order_items
            ]);
        }
    }

    public function loadOrders()
    {
        $order_rs = Database::search("SELECT `email`,`fname`,`lname`,`order_id`,`ordered_date`,SUM(order_qty*price) AS total,`status` FROM `order_data` GROUP BY order_id");

        $orders = [];

        while ($order_data = $order_rs->fetch_assoc()) {
            $orders[] = [
                "order_id" => $order_data["order_id"],
                "ordered_date" => $order_data["ordered_date"],
                "user_email" => $order_data["email"],
                "first_name" => $order_data["fname"],
                "last_name" => $order_data["lname"],
                "total" => $order_data["total"],
                "status" => $order_data["status"]
            ];
        }

        echo json_encode([
            "orders" => $orders
        ]);
    }


    public function getData($q, $type, $param)
    {
        $stmt = Database::$connection->prepare($q);
        $stmt->bind_param($type, $param);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function jsonResponce($state, $data = null, $message = null)
    {
        echo json_encode([
            "state" => $state,
            "data" => $data,
            "message" => $message
        ]);
    }
}
