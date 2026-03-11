<?php

require_once(BASE . "/config/connection.php");

class admin
{
    public function getDashboardStats()
    {
        // Total Revenue
        $revenue_query = "SELECT SUM(COALESCE(SUM(`order_qty`*`price`), 0) + COALESCE(`delivery_fee`, 0)) as total_revenue 
                         FROM `order_data` 
                         LEFT JOIN `invoice` ON `order_data`.`order_id` = `invoice`.`order_id`";
        $revenue_result = Database::search($revenue_query);
        $revenue_data = $revenue_result->fetch_assoc();
        $total_revenue = $revenue_data["total_revenue"] ?: 0;

        // Total Orders
        $orders_query = "SELECT COUNT(DISTINCT `order_id`) as total_orders FROM `order`";
        $orders_result = Database::search($orders_query);
        $orders_data = $orders_result->fetch_assoc();
        $total_orders = intval($orders_data["total_orders"] ?: 0);

        // Orders with status = 1 (Pending)
        $pending_query = "SELECT COUNT(DISTINCT `order_id`) as pending_orders FROM `order` WHERE `order_status` = 1";
        $pending_result = Database::search($pending_query);
        $pending_data = $pending_result->fetch_assoc();
        $pending_orders = intval($pending_data["pending_orders"] ?: 0);

        // Orders with status = 3 (Shipped) - assuming 3 = shipped
        $shipped_query = "SELECT COUNT(DISTINCT `order_id`) as shipped_orders FROM `order` WHERE `order_status` = 3";
        $shipped_result = Database::search($shipped_query);
        $shipped_data = $shipped_result->fetch_assoc();
        $shipped_orders = intval($shipped_data["shipped_orders"] ?: 0);

        // Revenue growth percentage (vs previous period - simplified as last 7 days vs 7 before)
        $current_revenue_query = "SELECT SUM(COALESCE(SUM(`order_qty`*`price`), 0) + COALESCE(`delivery_fee`, 0)) as period_revenue 
                                 FROM `order_data` 
                                 LEFT JOIN `invoice` ON `order_data`.`order_id` = `invoice`.`order_id`
                                 LEFT JOIN `order` ON `order_data`.`order_id` = `order`.`order_id`
                                 WHERE `order`.`ordered_date` >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $current_revenue_result = Database::search($current_revenue_query);
        $current_revenue_data = $current_revenue_result->fetch_assoc();
        $current_revenue = floatval($current_revenue_data["period_revenue"] ?: 0);

        $previous_revenue_query = "SELECT SUM(COALESCE(SUM(`order_qty`*`price`), 0) + COALESCE(`delivery_fee`, 0)) as period_revenue 
                                  FROM `order_data` 
                                  LEFT JOIN `invoice` ON `order_data`.`order_id` = `invoice`.`order_id`
                                  LEFT JOIN `order` ON `order_data`.`order_id` = `order`.`order_id`
                                  WHERE `order`.`ordered_date` >= DATE_SUB(NOW(), INTERVAL 14 DAY) 
                                  AND `order`.`ordered_date` < DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $previous_revenue_result = Database::search($previous_revenue_query);
        $previous_revenue_data = $previous_revenue_result->fetch_assoc();
        $previous_revenue = floatval($previous_revenue_data["period_revenue"] ?: 1); // Avoid division by 0

        $revenue_growth = $previous_revenue > 0 ? round((($current_revenue - $previous_revenue) / $previous_revenue) * 100, 1) : 0;

        // Total Users
        $users_query = "SELECT COUNT(*) as total_users FROM `users`";
        $users_result = Database::search($users_query);
        $users_data = $users_result->fetch_assoc();
        $total_users = intval($users_data["total_users"] ?: 0);

        echo json_encode([
            "state" => true,
            "data" => [
                "total_revenue" => floatval($total_revenue),
                "total_orders" => $total_orders,
                "pending_orders" => $pending_orders,
                "shipped_orders" => $shipped_orders,
                "revenue_growth" => $revenue_growth,
                "total_users" => $total_users
            ],
            "message" => "Dashboard statistics loaded"
        ]);
    }
}
