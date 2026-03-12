<?php

require_once("../app/model/admin.php");

class AdminController
{
    public function getDashboardStats()
    {
        $admin = new admin();
        $admin->getDashboardStats();
    }
}
