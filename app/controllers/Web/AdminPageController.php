<?php

class AdminPageController{
    public function dashboard()
    {
        require_once(BASE . "/app/views/Admin/dashboard.php");
    }

    public function logIn()
    {
        require_once(BASE . "/app/views/Admin/signin.php");
    }
}