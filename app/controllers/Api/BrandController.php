<?php
require_once(BASE."/app/model/brand.php");


class BrandController
{

    public function loadBrands()
    {
        $brand = new brand();
        $brand->load();
    }

    public function addBrand()
    {
        $brand = new brand();
        $brand->add();
    }
}
