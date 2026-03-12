<?php

require_once(BASE . "/config/connection.php");

class wishlist
{
    public function loadUserWishlist()
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
                echo json_encode(["state" => false, "data" => [], "message" => "User not logged in"]);
                return;
            }

        $email = Database::escape($_SESSION["u"]["email"]);
        
        // Load user's wishlist items with product details
        $query = "SELECT 
            w.`watchlist_id`,
            w.`product_id`,
            md.`model_id`,
            md.`model`,
            md.`price`,
            md.`img_path`,
            b.`brand_name`,
            p.`product_name`
        FROM `watchlist` w
        LEFT JOIN `model_data` md ON w.`product_id` = md.`product_id`
        LEFT JOIN `brand` b ON md.`brand_id` = b.`brand_id`
        LEFT JOIN `product` p ON md.`product_id` = p.`product_id`
        WHERE w.`users_email` = '" . $email . "'
        ORDER BY w.`watchlist_id` DESC";

        $result = Database::search($query);
        $items = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = [
                    "watchlist_id" => intval($row["watchlist_id"]),
                    "product_id" => intval($row["product_id"]),
                    "model_id" => intval($row["model_id"]),
                    "model_name" => $row["model"],
                    "brand_name" => $row["brand_name"],
                    "product_name" => $row["product_name"],
                    "price" => floatval($row["price"]),
                    "img_path" => $row["img_path"]
                ];
            }
        }

            echo json_encode(["state" => true, "data" => $items, "message" => "Wishlist loaded", "count" => count($items)]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "data" => [], "message" => "Error loading wishlist: " . $e->getMessage()]);
        }
    }

    public function toggleWishlist($data)
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
                echo "error";
                return;
            }

        $product_id = intval($data["id"] ?? ($data["product_id"] ?? 0));
        if ($product_id <= 0) {
            echo "error";
            return;
        }

        $email = Database::escape($_SESSION["u"]["email"]);

        $watchlist_rs = Database::search("SELECT * FROM `watchlist` WHERE `product_id`='" . $product_id . "' AND `users_email`='" . $email . "' ");
        $watchlist_num = $watchlist_rs->num_rows;

        if ($watchlist_num == 0) {
            Database::iud("INSERT INTO `watchlist`(`product_id`,`users_email`) VALUE('" . $product_id . "','" . $email . "') ");
            echo "Product added to watchlist Successfully...!";
            return;
        }

            Database::iud("DELETE FROM `watchlist` WHERE `product_id`='" . $product_id . "' AND `users_email`='" . $email . "' ");
            echo "Product removed from watchlist Successfully...!";
        } catch (Exception $e) {
            echo "error";
        }
    }

    public function removeWishlistItem($data)
    {
        try {
            if (!isset($_SESSION["u"]["email"])) {
                echo "error";
                return;
            }

        $watchlist_id = intval($data["id"] ?? ($data["watchlist_id"] ?? 0));
        if ($watchlist_id <= 0) {
            echo "error";
            return;
        }

            $email = Database::escape($_SESSION["u"]["email"]);
            Database::iud("DELETE FROM `watchlist` WHERE `watchlist_id`='" . $watchlist_id . "' AND `users_email`='" . $email . "' ");
            echo "success";
        } catch (Exception $e) {
            echo "error";
        }
    }
}
