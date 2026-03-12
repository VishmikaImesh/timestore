<?php

require_once(BASE."/app/model/orders.php");



class OrderController{
    private orders $orders;

    public function __construct()
    {
        $this->orders = new orders();
    }

    public  function newOrder(){
        $this->orders->newOrder();
    }

    public  function orderDetails(){
        $this->orders->loadOrdersDetails($_POST);
    }

    public  function orders(){
        $this->orders->loadOrders();
    }

    public  function userOrders(){
        $this->orders->loadUserOrders();
    }

    public  function updateOrderStatusAfterPayment(){
        $this->orders->updateOrderStatusAfterPayment();
    }

    public function cancelOrder(){
        $this->orders->cancelOrder($_POST);
    }
}