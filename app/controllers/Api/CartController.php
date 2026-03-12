<?php

require_once(BASE."/app/model/cart.php");

class CartController
{
    private cart $cart;

    public function __construct()
    {
        $this->cart = new cart();
    }

    public function addToCart()
    {
        $this->cart->addToCart($_POST);
    }

    public function removeFromCart()
    {
        $this->cart->removeFromCart($_POST);
    }
}
