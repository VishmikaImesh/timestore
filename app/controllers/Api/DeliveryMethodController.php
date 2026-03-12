<?php

require_once(BASE."/app/model/delivery.php");


class deliveryMethodController
{
    private delivery $delivery;

    public function __construct()
    {
        $this->delivery = new delivery();
    }

    public  function loadDeliveryDetails()
    {
        $this->delivery->loadDeliveryDetails();
    }

    public  function updateDeliveryDetails(){
        $this->delivery->updateDeliveryDetails($_POST);
    }

    public  function deleteDeliveryDetails(){
        $this->delivery->deleteDeliveryDetails($_POST["id"]);
    }
}
