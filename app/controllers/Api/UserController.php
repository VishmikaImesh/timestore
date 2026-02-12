<?php
require_once ("../app/model/customers.php");


class userController{

    public function loadCustomers(){
        $customers = new customers();
        $customers->loadUsers($_POST);
    }

    public function loadCustomerDetails(){
        $customers = new customers();
        $customers->loadUserDetails($_POST);
    }

    public function logIn(){
        $customers = new customers();
        $customers->logIn($_POST);
    }

    public function userLogIn(){
        $customers = new customers();
        $customers->userlogIn($_POST);
    }

    public function userProfile(){
        $customers = new customers();
        $customers->userProfile();
    }
}