<?php

require_once("../app/model/delivery.php");


class deliveryMethodController
{
    public  function loadDeliveryDetails()
    {
        $delivery = new delivery();
        $delivery->loadDeliveryDetails();
    }

    public  function updateDeliveryDetails(){
        $delivery = new delivery();
        $delivery->updateDeliveryDetails($_POST);
    }

    public  function deleteDeliveryDetails(){
        $delivery = new delivery();
        $delivery->deleteDeliveryDetails($_POST["id"]);
    }
}
