<?php

class UserPageController
{
    public function index()
    {
        require_once(BASE . "\app\\views\User\\index.php");
    }

    public function logIn()
    {
        require_once(BASE . "\app\\views\User\\signin.php");
    }

    public function viewProduct()
    {
        require_once(BASE . "\app\\views\User\\viewProduct.php");
    }

    public function profile()
    {
        require_once(BASE . "\app\\views\User\\profile.php");
    }

    public function checkout()
    {
        require_once(BASE . "\app\\views\User\\checkout.php");
    }

    public function search()
    {
        require_once(BASE . "\app\\views\User\\search.php");
    }


}
