<?php
require_once (BASE."/app/model/customers.php");


class userController{
    private customers $customers;

    public function __construct()
    {
        $this->customers = new customers();
    }

    

    public function loadCustomers(){
        $this->customers->loadUsers($_POST);
    }

    public function loadUserDetails(){
        $this->customers->loadUserDetails($_POST);
    }

    public function logIn(){
        $this->customers->logIn($_POST);
    }

    public function signUp(){
        $this->customers->signUp($_POST);
    }

    public function userProfile(){
        $this->customers->userProfile();
    }

    public function updateUserProfile(){
        $this->customers->updateUserProfile($_POST);
    }

    public function updateUserAddress(){
        $this->customers->updateUserAddress($_POST);
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