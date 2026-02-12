<?php

require_once("../app/model/orders.php");



class OrderController{

    public  function newOrder(){
        $orders = new orders();
        $orders->newOrder();
    }

    public  function orderDetails(){
        $orders = new orders();
        $orders->loadOrdersDetails($_POST);
    }

    public  function orders(){
        $orders = new orders();
        $orders->loadOrders();
    }
}