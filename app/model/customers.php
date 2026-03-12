<?php

require_once(BASE . "/config/connection.php");

class customers
{

    public function loadUsers($data)
    {
        try {
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
        } catch (Exception $e) {
            echo json_encode(["error" => "Error loading users: " . $e->getMessage()]);
        }
    }

    public function userProfile()
    {
        try {
            $email = $_SESSION["u"]["email"] ?? null;
        
        if (!$email) {
            echo json_encode([
                "error" => "User not logged in"
            ]);
            return;
        }

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
        } catch (Exception $e) {
            echo json_encode(["error" => "Error loading user profile: " . $e->getMessage()]);
        }
    }

    public function updateUserProfile($data)
    {
        try {
            $email = $_SESSION["u"]["email"] ?? null;
        if (!$email) {
            echo json_encode([
                "state" => false,
                "message" => "User not logged in"
            ]);
            return;
        }

        $first_name = trim($data["first_name"] ?? "");
        $last_name = trim($data["last_name"] ?? "");
        $mobile = trim($data["mobile"] ?? "");

        if ($first_name === "" || $last_name === "") {
            echo json_encode([
                "state" => false,
                "message" => "First name and last name are required"
            ]);
            return;
        }

        Database::iud("UPDATE `users` SET `fname`='" . $first_name . "', `lname`='" . $last_name . "', `mobile`='" . $mobile . "' WHERE `email`='" . $email . "' ");

        echo json_encode([
            "state" => true,
            "message" => "Profile updated"
        ]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error updating profile: " . $e->getMessage()]);
        }
    }

    public function updateUserAddress($data)
    {
        try {
            $email = $_SESSION["u"]["email"] ?? null;
        if (!$email) {
            echo json_encode([
                "state" => false,
                "message" => "User not logged in"
            ]);
            return;
        }

        $line_one = trim($data["line_one"] ?? "");
        $line_two = trim($data["line_two"] ?? "");
        $city = trim($data["city"] ?? "");
        $district = trim($data["district"] ?? "");
        $province = trim($data["province"] ?? "");
        $postal_code = trim($data["postal_code"] ?? "");

        if ($line_one === "" || $city === "" || $district === "" || $province === "" || $postal_code === "") {
            echo json_encode([
                "state" => false,
                "message" => "All required address fields must be filled"
            ]);
            return;
        }

        $address_rs = Database::search("SELECT `email` FROM `user_address_data` WHERE `email`='" . $email . "' ");

        if ($address_rs->num_rows > 0) {
            Database::iud("UPDATE `user_address_data` SET `address_line1`='" . $line_one . "', `address_line2`='" . $line_two . "', `city_en`='" . $city . "', `district_en`='" . $district . "', `province_en`='" . $province . "', `postcode`='" . $postal_code . "' WHERE `email`='" . $email . "' ");
        } else {
            Database::iud("INSERT INTO `user_address_data` (`email`, `address_line1`, `address_line2`, `city_en`, `district_en`, `province_en`, `postcode`) VALUES ('" . $email . "', '" . $line_one . "', '" . $line_two . "', '" . $city . "', '" . $district . "', '" . $province . "', '" . $postal_code . "')");
        }

        echo json_encode([
            "state" => true,
            "message" => "Address updated"
        ]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error updating address: " . $e->getMessage()]);
        }
    }

    public function loadUserDetails($data)
    {
        try {
            // Validate email parameter
            if (!isset($data["email"]) || empty($data["email"])) {
            echo json_encode([
                "state" => false,
                "message" => "Email parameter is required"
            ]);
            return;
        }

        $email = $data["email"];
        $order_rs = Database::search("SELECT * FROM `order_data` WHERE `email`='" . $email . "' ");
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

        $users_rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "' ");

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
            "state" => true,
            "user" => $user,
            "address" => $address,
            "orders" => $order_details
        ]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error loading user details: " . $e->getMessage()]);
        }
    }

    public function logIn($data)
    {
        try {
            $email = $data["email"];
            $password = $data["password"];

            // First, try to authenticate as a user
            $user_rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "' AND `password`='" . $password . "' ");

            if ($user_rs->num_rows == 1) {
                session_start();
                $user_data = $user_rs->fetch_assoc();
                $_SESSION["u"] = [
                    "email" => $user_data["email"],
                    "role" => "user",
                ];

                if ($data["rememberMe"] == 1) {
                    setcookie("email", $email, time() + 60 * 60 * 24 * 7);
                    setcookie("pw", $password, time() + 60 * 60 * 24 * 7);
                }

                echo json_encode(["state" => true, "message" => "Login successful"]);
                return;
            }

            // If user login fails, try to authenticate as an admin
            $admin_rs = Database::search("SELECT * FROM `admin` WHERE `email`='" . $email . "' AND `password`='" . $password . "' ");

            if ($admin_rs->num_rows == 1) {
                session_start();
                $admin_data = $admin_rs->fetch_assoc();
                $_SESSION["u"] = [
                    "email" => $admin_data["email"],
                    "role" => "admin",
                ];

                if ($data["rememberMe"] == 1) {
                    setcookie("email", $email, time() + 60 * 60 * 24 * 7);
                    setcookie("pw", $password, time() + 60 * 60 * 24 * 7);
                }

                echo json_encode(["state" => true, "message" => "Login successful"]);
                return;
            }

            echo json_encode(["state" => false, "message" => "Invalid email or password"]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "message" => "Error during login: " . $e->getMessage()]);
        }
    }

    public function signUp($data)
    {
        try {
            $fname = trim($data["f"] ?? "");
        $lname = trim($data["l"] ?? "");
        $email = trim($data["e"] ?? "");
        $mobile = trim($data["m"] ?? "");
        $pw = $data["pw"] ?? "";
        $pwa = $data["pwa"] ?? "";
        $gender = $data["g"] ?? null;

        $errors = "";

        if ($fname === "") {
            $errors .= "a";
        } elseif (strlen($fname) > 50) {
            $errors .= "b";
        }

        if ($lname === "") {
            $errors .= "c";
        } elseif (strlen($lname) > 50) {
            $errors .= "d";
        }

        if ($pw === "") {
            $errors .= "e";
        } elseif (strlen($pw) < 5 || strlen($pw) > 20) {
            $errors .= "f";
        }

        if ($pwa === "") {
            $errors .= "g";
        } elseif ($pw !== $pwa) {
            $errors .= "h";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors .= "i";
        }

        if ($mobile === "") {
            $errors .= "j";
        }

        if ($errors !== "") {
            echo $errors;
            return;
        }

        $fname = Database::escape($fname);
        $lname = Database::escape($lname);
        $email = Database::escape($email);
        $mobile = Database::escape($mobile);
        $pw = Database::escape($pw);
        $gender = Database::escape($gender);

        Database::iud("INSERT INTO `users`(`fname`,`lname`,`password`,`mobile`,`gender_id`,`email`) VALUES('" . $fname . "','" . $lname . "','" . $pw . "','" . $mobile . "','" . $gender . "','" . $email . "')");

        echo "success";
        } catch (Exception $e) {
            echo "error";
        }
    }

}
