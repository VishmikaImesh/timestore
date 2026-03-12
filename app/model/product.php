<?php
require_once(BASE . "/config/connection.php");

class product
{

    public function update($data, $files)
    {
        try {

        Database::setUpconnection();

        if (!isset($data["model_id"])) {
            echo json_encode(["state" => false, "message" => "Missing product ID"]);
            exit;
        }

        $id = $data["model_id"];

        $fields = [];
        $params = [];
        $bindTypes  = "";
        $model_name = "";


        if (isset($data["model_name"])) {
            $fields[] = "model = ?";
            $model_name = $data["model_name"];
            $params[] = $data["model_name"];
            $bindTypes   .= "s";
        }

        if (isset($files["img"])) {

            $img = $files["img"];

            $allowedMime = ["image/jpeg" => "jpg", "image/jpg" => "jpg", "image/png" => "png", "image/avif" => "avif", "image/webp" => "webp"];

            if (!isset($allowedMime[$img["type"]])) {
                echo json_encode(["success" => false, "error" => "Invalid image type"]);
                http_response_code(400);
                exit;
            }

            $ext = $allowedMime[$img["type"]];
            $file_name = ($model_name ? preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '-', strtolower($model_name))) . '_' : '') . uniqid();
            $relative_path = "product/" . $file_name . "." . $ext;
            $dest_path = BASE . "/app/media/" . $relative_path;
            move_uploaded_file($img["tmp_name"], $dest_path);

            $fields[] = "img_path = ?";
            $params[] = $relative_path;
            $bindTypes .= "s";
        }

        if (isset($data["price"])) {
            $fields[] = "price = ?";
            $params[] = $data["price"];
            $bindTypes   .= "d";
        }

        if (isset($data["qty"])) {
            $fields[] = "qty = ?";
            $params[] = intval($data["qty"]);
            $bindTypes   .= "i";
        }

        if (empty($fields)) {
            echo json_encode(["success" => false, "msg" => "No changes detected"]);
            exit;
        }

        $sql = "UPDATE `product_has_model` JOIN `product_img` ON `product_has_model`.`model_id`=`product_img`.`model_id` SET " . implode(", ", $fields) . " WHERE `product_has_model`.`model_id` = ?";
        $params[] = $id;
        $bindTypes   .= "i";

        $stmt = Database::$connection->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => Database::$connection->error]);
            exit;
        }
        $stmt->bind_param($bindTypes, ...$params);
        $ok = $stmt->execute();
        if (!$ok) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $stmt->error]);
            exit;
        }
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "msg" => "Product updated successfully"
        ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "msg" => "Error updating product: " . $e->getMessage()]);
        }
    }

    public function load($data)
    {
        try {
        // Pagination params
        $page = isset($data['page']) ? max(1, intval($data['page'])) : 1;
        $pageSize = isset($data['page_size']) ? max(1, min(100, intval($data['page_size']))) : 20;
        $offset = ($page - 1) * $pageSize;

        // Sorting
        $orderBy = " ORDER BY `added_time` DESC, `model_id` DESC ";
        if (isset($data['sort'])) {
            $sort = intval($data['sort']);
            if ($sort === 1) {
                $orderBy = " ORDER BY `price` ASC, `model_id` ASC ";
            } else if ($sort === 2) {
                $orderBy = " ORDER BY `price` DESC, `model_id` DESC ";
            } else if ($sort === 3) {
                $orderBy = " ORDER BY `revenue` DESC, `model_id` DESC ";
            } else if ($sort === 4) {
                $orderBy = " ORDER BY `added_time` DESC, `model_id` DESC ";
            }
        }

        // Base query
        $product_q = "SELECT 
        `model_data`.`product_id`,
        `model_data`.`product_name`,
        SUM(`model_data`.`qty`) AS `qty`,
        MAX(`model_data`.`price`) AS `price`,
        `model_data`.`brand_name`,
        `model_data`.`brand_id`,
        MAX(`model_data`.`added_time`) AS `added_time`,
        MAX(`model_data`.`model_id`) AS `model_id`,
        CASE WHEN SUM(`order_qty`) IS NULL THEN 0 ELSE SUM(`order_qty`) END AS order_qty,
        CASE WHEN SUM(`order_data`.`price`*`order_qty`) IS NULL THEN 0 ELSE SUM(`order_data`.`price`*`order_qty`) END AS revenue
        FROM `model_data`
        LEFT JOIN `order_data` ON `order_data`.model_id=`model_data`.`model_id` ";

        // Brand filter sanitization
        $where = [];
        if (isset($data['brand']) && trim($data['brand']) !== '') {
            $csv = explode(',', trim($data['brand'], ','));
            $ids = [];
            foreach ($csv as $b) {
                $v = intval($b);
                if ($v > 0) {
                    $ids[] = $v;
                }
            }
            if (!empty($ids)) {
                $product_q .= " WHERE `model_data`.`brand_id` IN (" . implode(',', $ids) . ") ";
            }
        }

        $product_q .= " GROUP BY `product_id` ";
        $product_q .= $orderBy;
        $product_q .= " LIMIT " . $pageSize . " OFFSET " . $offset . " ";

        $product_rs = Database::search($product_q);
        $products = [];

        while ($product_data = $product_rs->fetch_assoc()) {
            $products[] = [
                "product_id" => $product_data["product_id"],
                "product_name" => $product_data["product_name"],
                "brand" => $product_data["brand_name"],
                "brand_id" => $product_data["brand_id"],
                "price" => $product_data["price"],
                "qty" => $product_data["qty"],
                "order_qty" => $product_data["order_qty"],
                "revenue" => $product_data["revenue"],
                "added_time" => $product_data["added_time"],
                "img_path" => "/timestore/Img/" . $product_data["model_id"],
            ];
        }

        echo json_encode([
            "success" => true,
            "data" => [
                "page" => $page,
                "page_size" => $pageSize,
                "models" => $products
            ],
            "error" => null
        ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "data" => ["models" => []],
                "error" => "Error loading products: " . $e->getMessage()
            ]);
        }
    }

    public function add($data, $files)
    {
        try {
        Database::setUpconnection();

        echo (trim($data["model_name"], " -"));
        $model_name = ucwords(strtolower(str_replace("-", " ", $data["model_name"])));
        $price = $data["price"];
        $qty = $data["qty"];

        $brand_id = null;
        if (isset($data["brand_id"])) {
            $brand_id = $data["brand_id"];
        } else if (isset($data["brand_name"])) {
            $brand_name = ucwords(strtolower(str_replace("-", " ", $data["brand_name"])));
            Database::iud("INSERT INTO `brand`(`brand_name`) VALUES('" . $brand_name . "') ");
            $brand_id = Database::$connection->insert_id;
        }

        $product_id = null;
        if (isset($data["product_id"])) {
            $product_id = $data["product_id"];
        } else if (isset($data["product_name"])) {
            $product_name = ucwords(strtolower(str_replace("-", " ", $data["product_name"])));
            Database::iud("INSERT INTO `product`(`product_name`,`brand_id`) VALUES('" . $product_name . "','" . $brand_id . "') ");
            $product_id = Database::$connection->insert_id;
        }


        $img = $files["img"];

        $types = ["image/jpeg", "image/jpg", "image/avif", "image/webp", "image/png"];

        if (!in_array($img["type"], $types)) {
            exit;
        }

        $file_name = $model_name . "_" . uniqid();
        $file_loction = "product/" . $file_name;
        move_uploaded_file($img["tmp_name"], BASE . "/app/media/" . $file_loction);

        Database::iud("INSERT INTO `product_has_model` (`product_id`,`price`,`qty`,`model`) VALUES ('" . $product_id . "', '" . $price . "', '" . $qty . "', '" . $model_name . "')");
        $model_id = Database::$connection->insert_id;

        Database::iud("INSERT INTO `product_img` (`model_id`,`img_path`) VALUES ('" . $model_id . "', '" . $file_loction . "')");

        echo json_encode([
            "success" => true,
            "msg" => "Product added successfully"
        ]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "msg" => "Error adding product: " . $e->getMessage()]);
        }
    }

    public function models($data)
    {
        try {

        $q = "";
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
        WHERE " . $q . " GROUP BY `model_data`.`model_id`
        ORDER BY `model_data`.`model_id` DESC");

        $models = [];

        while ($model_data = $model_rs->fetch_assoc()) {

            $models[] = [
                "model_id" => $model_data["model_id"],
                "model_name" => $model_data["model"],
                "product_id" => $model_data["product_id"],
                "product_name" => $model_data["product_name"],
                "brand_id" => $model_data["brand_id"],
                "brand_name" => $model_data["brand_name"],
                "brand_id" => $model_data["brand_id"],
                "price" => $model_data["price"],
                "qty" => $model_data["qty"],
                "img_path" => "/timestore/Img/" . $model_data["model_id"],
                "order_qty" => $model_data["order_qty"]
            ];
        }

        echo json_encode([
            "models" => $models
        ]);
        } catch (Exception $e) {
            echo json_encode(["models" => [], "error" => "Error loading models: " . $e->getMessage()]);
        }
    }

    public function revenueData($data)
    {
        try {
            $revenue_q ="SELECT DATE(`invoice_date`) as date,SUM(product_price*qty) AS total FROM `invoice` JOIN `invoice_items` ON `invoice`.`invoice_id`=`invoice_items`.`invoice_id` WHERE ";
        
        isset($data['revenuePeriod']) ?  
           $revenue_q = $revenue_q . " `invoice_date` >= CURDATE() - INTERVAL " . intval($data['revenuePeriod']) . " DAY 
            AND `invoice_date` < CURDATE() + INTERVAL 1 DAY AND" 
            : "";

        isset($data['product_id']) ? 
            $revenue_q = $revenue_q . "  `invoice_items`.`product_id`='" . intval($data['product_id']) . "' " 
            : "";
        
        $revenue_q = rtrim($revenue_q, " WHERE ");
        $revenue_q=rtrim($revenue_q, " AND ");

        $revenue_q = $revenue_q . " GROUP BY `date` ORDER BY `date`";


        $revenue_rs = Database::search($revenue_q);

        $revenues = [];
        $dates = [];
        $total=0;

        while ($revenue_data = $revenue_rs->fetch_assoc()) {
            $dates[] = $revenue_data['date'];
            $revenues[] = $revenue_data['total'];
            $total+=$revenue_data['total'];
        }

            return([
                "dates" => $dates,
                "revenues" => $revenues,
                "total" => $total
            ]);
        } catch (Exception $e) {
            return([
                "dates" => [],
                "revenues" => [],
                "total" => 0
            ]);
        }
    }
}
