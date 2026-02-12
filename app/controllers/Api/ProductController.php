<?php

require_once("../app/model/product.php");



class productController
{

    public  function updateProduct()
    {
        $product = new product();
        $product->update($_POST);
    }

    public  function loadProducts()
    {
        $product = new product();
        $product->load($_POST);
    }

    public  function loadModels()
    {
        $product = new product();
        $product->models($_POST);
    }
}
