<?php

if (isset($_SESSION["u"]["email"]) && isset($_POST[""])) {
    $merchant_id = "1226402";
    $order_id = "566546";
    $amount = "42222";
    $currency = "LKR";
    $merchant_secret = "MjQzNDc2NDEyMzIzMDkwMjIwMzg4OTQ2ODM5MDEzMDI0MjQyODQy";

    $hash = strtoupper(
        md5(
            $merchant_id .
                $order_id .
                number_format($amount, 2, '.', '') .
                $currency .
                strtoupper(md5($merchant_secret))
        )
    );

    echo $hash;
}
