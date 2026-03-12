<?php

require_once("../app/model/cart.php");

class CartController
{
    public function addToCart()
    {
        $cart = new cart();
        $cart->addToCart($_POST);
    }

    public function removeFromCart()
    {
        $cart = new cart();
        $cart->removeFromCart($_POST);
    }
}
