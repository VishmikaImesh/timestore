<?php

require_once(BASE . "/config/connection.php");

class customers
{

    public function loadUsers($data)
    {
        $query = "SELECT users.*, user_status.status AS status_txt, user_status.status_id,
        (SELECT COUNT(*) FROM `order_data` WHERE `order_data`.`email` = `users`.`email`) AS order_count,
        (SELECT SUM(`price` * `order_qty`) FROM `order_data` WHERE `order_data`.`email` = `users`.`email`) AS total_spent
        FROM `users` 
        JOIN `user_status` ON `users`.`status`=`user_status`.`status_id`";

        if (isset($data["status"]) && $data["status"] != 0) {
            $query .= " WHERE `users`.`status`='" . $data["status"] . "'";
        }

        $users_rs = Database::search($query);

        $users = [];
        $active_count = 0;
        $blocked_count = 0;

        while ($users_data = $users_rs->fetch_assoc()) {
            $users[] = [

                "first_name" => $users_data["fname"],
                "last_name" => $users_data["lname"],
                "email" => $users_data["email"],
                "mobile" => $users_data["mobile"],
                "joined_date" => date("M d ,Y", strtotime($users_data["joined_date"])),
                "status" => $users_data["status_txt"],
                "order_count" => $users_data["order_count"],
                "total_spent" => $users_data["total_spent"]
            ];

            if ($users_data["status_id"] == 1) {
                $active_count++;
            } else if ($users_data["status_id"] == 2) {
                $blocked_count++;
            }
        }

        echo json_encode([
            "users" => $users,
            "active" => $active_count,
            "blocked" => $blocked_count
        ]);
    }

    public function userProfile()
    {
        $email = "imesh@gmail.com";

        $users_rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "' ");

        $user = [];
        while ($users_data = $users_rs->fetch_assoc()) {
            $user = [
                "first_name" => $users_data["fname"],
                "last_name" => $users_data["lname"],
                "email" => $users_data["email"],
                "mobile" => $users_data["mobile"]
            ];
        }

        $address_rs = Database::search("SELECT * FROM `user_address_data` WHERE `email`='" . $email . "' ");
        $address = null;
        while ($address_data = $address_rs->fetch_assoc()) {
            $address = [
                "line_one" => $address_data["address_line1"],
                "line_two" => $address_data["address_line2"],
                "city" => $address_data["city_en"],
                "district" => $address_data["district_en"],
                "province" => $address_data["province_en"],
                "postal_code" => $address_data["postcode"]
            ];
        }

        echo json_encode([
            "user" => $user,
            "address" => $address
        ]);
    }

    public function loadUserDetails($data)
    {

        $order_rs = Database::search("SELECT * FROM `order_data` WHERE `email`='" . $data["email"] . "' ");
        $order_details = null;
        $total_spent = 0;
        $order_count = $order_rs->num_rows;

        while ($order_data = $order_rs->fetch_assoc()) {
            $order_details[] = [
                "order_id" => $order_data["order_id"],
                "total" => $order_data["price"] * $order_data["order_qty"],
                "ordered_date" => $order_data["ordered_date"],
                "status" => $order_data["status"]
            ];
            $total_spent += $order_data["price"] * $order_data["order_qty"];
        };

        $users_rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $data["email"] . "' ");

        $user = [];
        while ($users_data = $users_rs->fetch_assoc()) {
            $user = [
                "first_name" => $users_data["fname"],
                "last_name" => $users_data["lname"],
                "email" => $users_data["email"],
                "mobile" => $users_data["mobile"],
                "total_spent" => $total_spent,
                "order_count" => $order_count
            ];
        }

        $address_rs = Database::search("SELECT * FROM `user_address_data` WHERE `email`='" . $data["email"] . "' ");
        $address = null;
        while ($address_data = $address_rs->fetch_assoc()) {
            $address = [
                "line_one" => $address_data["address_line1"],
                "line_two" => $address_data["address_line2"],
                "city" => $address_data["city_en"],
                "district" => $address_data["district_en"],
                "province" => $address_data["province_en"],
                "postal_code" => $address_data["postcode"]
            ];
        }

        echo json_encode([
            "user" => $user,
            "address" => $address,
            "orders" => $order_details
        ]);
    }

    public function logIn($data)
    {
        $email = $data["email"];
        $password = $data["password"];

        $user_rs = Database::search("SELECT * FROM `admin` WHERE `email`='" . $email . "' AND `password`='" . $password . "' ");

        if ($user_rs->num_rows == 1) {

            session_start();
            $user_data = $user_rs->fetch_assoc();
            $_SESSION["u"] = [
                "email" => $user_data["email"],
                "role" => "admin",
            ];

            if ($data["rememberMe"] == 1) {
                setcookie("email", $email, time() + 60 * 60 * 24 * 7);
                setcookie("pw", $password, time() + 60 * 60 * 24 * 7);
            };

            echo ("success");
        }
    }

}
