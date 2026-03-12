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

    public  function userOrders(){
        $orders = new orders();
        $orders->loadUserOrders();
    }

    public  function updateOrderStatusAfterPayment(){
        $orders = new orders();
        $orders->updateOrderStatusAfterPayment();
    }

    public function cancelOrder(){
        $orders = new orders();
        $orders->cancelOrder($_POST);
    }
}