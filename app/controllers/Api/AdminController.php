<?php

require_once(BASE."/app/model/admin.php");

class AdminController
{
    private admin $admin;

    public function __construct()
    {
        $this->admin = new admin();
    }

    public function getDashboardStats()
    {
        $this->admin->getDashboardStats();
    }
}
