<?php

require_once(BASE . "/config/connection.php");
require_once(BASE . "/config/payhere.php");

class orders
{
    public function newOrder()
    {
        try {
            if (isset($_POST["id"]) && isset($_POST["qty"]) && isset($_POST["delivery_method_id"])) {
            
            // Get email from session
            if (!isset($_SESSION["u"]["email"])) {
                echo json_encode(["error" => "User not logged in"]);
                return;
            }

            $model_id = intval($_POST["id"]);
            $qty = intval($_POST["qty"]);
            $delivery_method_id = intval($_POST["delivery_method_id"]);
            $email = Database::escape($_SESSION["u"]["email"]);
            

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

                $merchant_id = PAYHERE_MERCHANT_ID;
                $currency = PAYHERE_CURRENCY;
                $merchant_secret = PAYHERE_MERCHANT_SECRET;
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
        } catch (Exception $e) {
            echo json_encode(["error" => "Error creating order: " . $e->getMessage()]);
        }
    }

    public function loadOrdersDetails($data)
    {
        try {
            Database::setUpconnection();

        $order_id = $data["order_id"];

        $order_q = ("SELECT `email`,`order_id`,`ordered_date`,SUM(`order_qty`*`price`) AS `sub_total`,`delivery_fee`,SUM(`order_qty`*`price`)+`delivery_fee` AS `grand_total` FROM `order_data` WHERE `order_id`=?  GROUP BY `order_id` ");
        $order_rs = $this->getData($order_q, 'i', $order_id);
        $order_data = $order_rs->fetch_assoc();
        
        // Check if order exists
        if ($order_data === null) {
            $this->jsonResponce(false, null, "Order not found");
            return;
        }
        
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

        $user_details = null;
        if ($address_data !== null) {
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
        }

            $this->jsonResponce(true, [
                "user" => $user_details,
                "order" => $order,
                "order_items" => $order_items
            ]);
        } catch (Exception $e) {
            $this->jsonResponce(false, null, "Error loading order details: " . $e->getMessage());
        }
    }

    public function loadOrders()
    {
        try {
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
        } catch (Exception $e) {
            echo json_encode(["orders" => [], "error" => "Error loading orders: " . $e->getMessage()]);
        }
    }


    public function getData($q, $type, $param)
    {
        try {
            $stmt = Database::$connection->prepare($q);
            $stmt->bind_param($type, $param);
            $stmt->execute();
            return $stmt->get_result();
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateOrderStatusAfterPayment()
    {
        try {
            if (isset($_POST["order_id"]) && isset($_SESSION["u"]["email"])) {
                $order_id = intval($_POST["order_id"]);
                $email = Database::escape($_SESSION["u"]["email"]);

            // Update order status to 2 (Paid/Processing) after successful payment
            Database::iud("UPDATE `order` SET `order_status`=2 WHERE `order_id`=$order_id AND `email`='" . $email . "' ");
            
                echo json_encode(["state" => true, "message" => "Order status updated to paid"]);
            } else {
                echo json_encode(["state" => false, "message" => "Missing order_id or user session"]);
            }
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error updating order status: " . $e->getMessage()]);
        }
    }

    public function loadUserOrders()
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
                echo json_encode(["state" => false, "data" => [], "message" => "User not logged in"]);
                return;
            }

        $email = Database::escape($_SESSION["u"]["email"]);
        
        // Load user's orders with status information
        $query = "SELECT 
            o.`order_id`,
            o.`ordered_date`,
            o.`order_status`,
            os.`status` as status_name,
            SUM(ohm.`qty` * md.`price`) as total,
            dm.`price` as delivery_fee
        FROM `order` o
        LEFT JOIN `order_status` os ON o.`order_status` = os.`order_status_id`
        LEFT JOIN `order_has_model` ohm ON o.`order_id` = ohm.`order_id`
        LEFT JOIN `model_data` md ON ohm.`model_id` = md.`model_id`
        LEFT JOIN `delivery_method` dm ON o.`delivery_method` = dm.`id`
        WHERE o.`email` = '" . $email . "'
        GROUP BY o.`order_id`, o.`order_status`, os.`status`
        ORDER BY o.`ordered_date` DESC";

        $result = Database::search($query);
        $orders = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = [
                    "order_id" => $row["order_id"],
                    "ordered_date" => $row["ordered_date"],
                    "order_status" => intval($row["order_status"]),
                    "status_name" => $row["status_name"],
                    "total" => floatval($row["total"]),
                    "delivery_fee" => floatval($row["delivery_fee"] ?: 0)
                ];
            }
        }

            echo json_encode(["state" => true, "data" => $orders, "message" => "User orders loaded"]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "data" => [], "message" => "Error loading user orders: " . $e->getMessage()]);
        }
    }

    public function cancelOrder($data)
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
                echo json_encode(["state" => false, "message" => "User not logged in"]);
                return;
            }

        $order_id = intval($data["orderId"] ?? 0);
        if ($order_id <= 0) {
            echo json_encode(["state" => false, "message" => "Missing orderId"]);
            return;
        }

        $email = Database::escape($_SESSION["u"]["email"]);

            Database::iud("DELETE FROM `order` WHERE `email`='" . $email . "' AND `order_id`=" . $order_id . " ");
            Database::iud("DELETE FROM `invoice` WHERE `email`='" . $email . "' AND `order_id`=" . $order_id . " ");

            echo json_encode(["state" => true, "message" => "Order cancelled"]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error cancelling order: " . $e->getMessage()]);
        }
    }

    public function jsonResponce($state, $data = null, $message = null)
    {
        try {
            echo json_encode([
                "state" => $state,
                "data" => $data,
                "message" => $message
            ]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "data" => null, "message" => "Response error"]);
        }
    }
}
