<?php
require_once(BASE . "/config/connection.php");

class product
{

    public function update($data)
    {

        Database::setUpconnection();

        if (!isset($data["model_id"])) {
            echo json_encode(["state" => false, "message" => "Missing product ID"]);
            exit;
        }

        $id = $data["model_id"];

        $fields = [];
        $params = [];
        $types  = "";
        $model_name = "";


        if (isset($data["model_name"])) {
            $fields[] = "model = ?";
            $model_name = $data["model_name"];
            $params[] = $data["model_name"];
            $types   .= "s";
        }

        if (isset($_FILES["img"])) {

            $img = $_FILES["img"];

            $types = ["image/jpeg", "image/jpg", "image/avif", "image/webp"];

            if (!in_array($img["type"], $types)) {
                exit;
            }

            $file_name = $model_name . "_" . uniqid();
            $file_loction = "product/" . $file_name;
            move_uploaded_file($img["tmp_name"], $file_loction);

            $fields[] = "img_path = ?";
            $params[] = $file_loction;
            $types .= "s";
        }

        if (isset($data["price"])) {
            $fields[] = "price = ?";
            $params[] = $data["price"];
            $types   .= "d";
        }

        if (isset($data["qty"])) {
            $fields[] = "qty = ?";
            $params[] = intval($data["qty"]);
            $types   .= "i";
        }

        if (empty($fields)) {
            echo json_encode(["success" => false, "msg" => "No changes detected"]);
            exit;
        }

        $sql = "UPDATE `product_has_model` JOIN `product_img` ON `product_has_model`.`model_id`=`product_img`.`model_id` SET " . implode(", ", $fields) . " WHERE `product_has_model`.`model_id` = ?";
        $params[] = $id;
        $types   .= "i";

        echo json_encode([
            $fields,
            $params
        ]);

        $stmt = Database::$connection->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        echo json_encode([
            "success" => true,
            "msg" => "Product updated successfully"
        ]);
    }

    public function load($data)
    {
        $product_q = "SELECT 
        `model_data`.`product_id`,
        `model_data`.`product_name`,
        SUM(`model_data`.`qty`) AS `qty`,
        MAX(`model_data`.`price`) AS `price`,
        `model_data`.`brand_name`,
        `model_data`.`brand_id`,
        MIN(`model_data`.`model_id`) AS `model_id`,
        CASE
        	WHEN SUM(`order_qty`)  IS NULL then  0
        	ELSE SUM(`order_qty`) 
    	END AS order_qty,
        CASE
            WHEN SUM(`order_data`.`price`*`order_qty`) is NULL then 0
            ELSE SUM(`order_data`.`price`*`order_qty`)
        END AS revenue
        FROM `model_data` 
        left JOIN `order_data`
        ON `order_data`.model_id=`model_data`.`model_id` ";

        if(isset($data["brand"])){
            $brand = $data["brand"];
            $product_q .= " WHERE `model_data`.`brand_id` IN (" . rtrim($brand, ",") . ") ";
        }
        $product_q .= " GROUP BY `product_id` ";

        if (isset($data["sort"])) {
            if ($data["sort"] == 1) {
                $product_q .= "ORDER BY `price` ASC ";
            } else {
                $product_q .= "ORDER BY `price` DESC ";
            }
        }

        $product_rs = Database::search($product_q);
        $products = [];

        while ($product_data = $product_rs->fetch_assoc()) {
            $products[] = [
                "product_id" => $product_data["product_id"],
                "product_name" => $product_data["product_name"],
                "brand" => $product_data["brand_name"],
                "brand_id"=>$product_data["brand_id"],
                "price" => $product_data["price"],
                "qty" => $product_data["qty"],
                "order_qty" => $product_data["order_qty"],
                "revenue" => $product_data["revenue"],
                "img_path" => "\\timestore\\Img\\" . $product_data["model_id"],
            ];
        };

        echo json_encode([
            "models" => $products
        ]);
    }

    public function add(){
            
    }

    public function models($data)
    {
        
        $q="";
        if (isset($data["product_id"])) {
            $q = "`model_data`.`product_id`='" . $data["product_id"] . "' ";
        } 
        if (isset($data["model_id"])) {
            $q = " AND `model_data`.`model_id`='" . $data["model_id"] . "' ";
        }

        $q = trim($q, " AND ");


        $model_rs = Database::search("SELECT 
         `model_data`.`model_id`,
        `model_data`.`model`,
         `model_data`.`price`,
        `model_data`.`qty`,
        `model_data`.`brand_id`,
        `model_data`.`brand_name`,
        `model_data`.`product_id`,
        `model_data`.`product_name`,
        case 
	        WHEN SUM(`order_data`.`order_qty`) IS NULL then 0
	        ELSE SUM(`order_data`.`order_qty`)
        END AS order_qty,
        ANY_VALUE(`model_data`.`img_path`) AS `img_path`
        FROM `model_data`
        LEFT JOIN `order_data`
        ON `model_data`.`model_id`=`order_data`.`model_id`
        WHERE " . $q . " GROUP BY `model_data`.`model_id` ");

        $models = [];

        while ($model_data = $model_rs->fetch_assoc()) {

            $models[] = [
                "model_id" => $model_data["model_id"],
                "model_name" => $model_data["model"],
                "product_id" => $model_data["product_id"],
                "product_name" => $model_data["product_name"],
                "brand_id"=>$model_data["brand_id"],
                "brand_name" => $model_data["brand_name"],
                "brand_id" => $model_data["brand_id"],
                "price" => $model_data["price"],
                "qty" => $model_data["qty"],
                "img_path" => "\\timestore\\Img\\" . $model_data["model_id"],
                "order_qty" => $model_data["order_qty"]
            ];
        }

        echo json_encode([
            "models" => $models
        ]);
    }
}
