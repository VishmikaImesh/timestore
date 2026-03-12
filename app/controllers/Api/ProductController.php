<?php

require_once("../app/model/product.php");

class productController
{
    private $product;

    private function __construct() {
        $this->product = new product();
    }

    public function addProduct()
    {
        $this->product->add($_POST,$_FILES);
    }

    public  function updateProduct()
    {
        $this->product->update($_POST,$_FILES);
    }

    public  function loadProducts()
    {
        $this->product->load($_POST);
    }

    public  function loadModels()
    {
        $this->product->models($_POST);
    }

    public function revenueData()
    {
        $revenueData=$this->product->revenueData($_POST);
        echo json_encode($revenueData);
    }
}
