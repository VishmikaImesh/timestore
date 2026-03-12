<?php
require_once(BASE."/app/model/brand.php");


class BrandController
{
    private brand $brand;

    public function __construct()
    {
        $this->brand = new brand();
    }

    public function loadBrands()
    {
        $this->brand->load();
    }

    public function addBrand()
    {
        $this->brand->add();
    }
}
