<?php
require_once ("../app/model/customers.php");


class userController{

    

    public function loadCustomers(){
        $customers = new customers();
        $customers->loadUsers($_POST);
    }

    public function loadUserDetails(){
        $customers = new customers();
        $customers->loadUserDetails($_POST);
    }

    public function logIn(){
        $customers = new customers();
        $customers->logIn($_POST);
    }

    public function signUp(){
        $customers = new customers();
        $customers->signUp($_POST);
    }

    public function userProfile(){
        $customers = new customers();
        $customers->userProfile();
    }

    public function updateUserProfile(){
        $customers = new customers();
        $customers->updateUserProfile($_POST);
    }

    public function updateUserAddress(){
        $customers = new customers();
        $customers->updateUserAddress($_POST);
    }

    public function signOut(){
        // Destroy the session
        session_start();
        $_SESSION = [];
        session_destroy();

        // Clear remember me cookies if they exist
        if (isset($_COOKIE['email'])) {
            setcookie('email', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['pw'])) {
            setcookie('pw', '', time() - 3600, '/');
        }

        echo json_encode([
            "state" => true,
            "message" => "Sign out successful"
        ]);
    }
}