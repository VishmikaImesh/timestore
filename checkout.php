<?php

session_start();

if (!empty($_GET["id"]) && !empty($_GET["qty"])) {
    $id = $_GET["id"];
    $qty = $_GET["qty"];
} else {
    header("Location:index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Checkout</title>
</head>

<body class="bg-light">

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php";


    $email = $_SESSION["u"]["email"];;

    $user_rs = Database::search("SELECT *,`cities`.`name_en` AS `city_name`,`districts`.`name_en` AS `district_name`, `provinces`.`name_en` AS `province_name`  FROM `users` 
                JOIN `user_address` ON `users`.`email`=`user_address`.`users_email`
                JOIN `cities` ON `user_address`.`city_id`=`cities`.`city_id`
                JOIN `districts` ON `cities`.`district_id`=`districts`.`district_id` 
                JOIN `provinces` ON `districts`.`province_id`=`provinces`.`province_id` WHERE `email`='" . $email . "' ");

    $product_rs = Database::search("SELECT * FROM `cart` JOIN `product_has_model`
                ON `cart`.`product_id`=`product_has_model`.`model_id`
                JOIN `product` 
                ON `product_has_model`.`product_id`=`product`.`product_id` 
                JOIN `brand` 
                ON  `product`.`brand_id`=`brand`.`brand_id`
                WHERE `users_email`='" . $email . "' AND `model_id`='" . $id . "' ");

    $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");

    $user_data = $user_rs->fetch_assoc();
    $product_data = $product_rs->fetch_assoc();
    $img_data = $img_rs->fetch_assoc();

    ?>

    <div class="container-fluid min-vh-100 mt-6 ">
        <div class="row">
            <div class="col-12 col-md-8 border-0 ">
                <div class="card border-0 m-1">
                    <div class="card-body">
                        <div class=" d-flex justify-content-between pt-2 pb-3">
                            <h6 class="fw-bold  m-0">Delivery Address</h6>
                            <h6 class="fw-bold text-primary m-0">Edit</h6>
                        </div>
                        <figure>
                            <blockquote class="blockquote">
                                <h6 class="card-text col-12 col-lg-5 fw-bold "><?php echo $user_data["fname"] . ' ' . $user_data["lname"] ?></h6>
                                <h6 class="text-secondary col-12 col-lg-6"><?php echo $user_data["mobile"] ?></h6>
                                <p class="fs-6 text-secondary"><?php echo $user_data["address_line1"] . ',' . $user_data["address_line2"] . ',' . $user_data["city_name"] . ',' . $user_data["district_name"] . ',' . $user_data["province_name"] . ' Province'  ?></p>
                            </blockquote>
                        </figure>
                    </div>
                </div>

                <div class="card p-1 m-1 mt-3 border-0">

                    <div class="card-body  row">

                        <h6 class="fw-bold">Delivery Method</h6>

                        <div class="card col-10 col-md-6 border-0" >
                            <button class="btn  m-1 p-0 border-secondary-subtle" id="deliverytoption1" onclick="changeDeliveryOption('deliverytoption1',<?php echo $product_data['cart_qty'] * $product_data['price'] ?>,250);">
                                <div class="card-body  row  text-start">
                                    <div class="col-1">
                                        <input id="checkDeliveryMethod1" class="form-check-input" type="checkbox" checked></input>
                                    </div>

                                    <div class="col-10">
                                        <h5 class="text-primary fw-bold">Standard</h5>
                                        <p class="card-title text-success fw-bold">Rs.250</p>
                                        <p class="card-title fw-bold">Guaranteed by</p>
                                        <p class="card-title text-secondary fw-bolder"> <?php echo date('d', strtotime('+4 days')) ?>-<?php echo date('d', strtotime('+7 days')); ?> <?php echo date('F'); ?></p>

                                    </div>

                                </div>
                            </button>
                        </div>

                        <div class="card col-10 col-md-6 border-0 ">
                            <button class="btn m-1 p-0 border-secondary-subtle" id="deliverytoption2" onclick="changeDeliveryOption('deliverytoption2',<?php echo $product_data['cart_qty'] * $product_data['price'] ?>,500);">
                                <div class="card-body row  text-start">
                                    <div class="col-1">
                                        <input id="checkDeliveryMethod2" class="form-check-input" type="checkbox">
                                    </div>

                                    <div class="col-10">
                                        <h5 class="text-primary fw-bold">Express</h5>
                                        <p class="card-title text-success fw-bold">Rs.500</p>
                                        <p class="card-title fw-bold">Guaranteed by</p>
                                        <p class="card-title text-secondary fw-bolder"> <?php echo date('d', strtotime('+2 days')) ?> <?php echo date("F") ?></p>

                                    </div>

                                </div>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="card mt-3 p-2 border-0">
                    <div class="card-body">
                        <div class=" d-flex justify-content-between pb-2">
                            <h6 class="fw-bold  ">Package Details</h6>
                        </div>
                        <div class="card mb-3 border-0">
                            <div class="row g-0">
                                <div class="col-3 mt-3">
                                    <img src="<?php echo $img_data["img_path"]; ?>" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title text-secondary"><?php echo $product_data["brand_name"] ?></h5>
                                        <h5 class="card-title"><?php echo $product_data["model"] ?></h5>
                                        <h6 class="card-text text-secondary">Rs.<?php echo $product_data["price"] ?> For each Item</h6>
                                        <h6 class="card-text text-secondary"><?php echo $product_data["cart_qty"] ?> Items</h6>

                                        <h6>Sub Total : <span class="card-text text-danger">Rs.<?php echo $product_data["cart_qty"] * $product_data["price"] ?></span> </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 col-md-4 mt-1 border-0">
                <div class="card border-0  p-3">

                    <div class="card-body row">
                        <div class="pb-2">
                            <h6 class="fw-bold  ">Order Summary</h6>
                        </div>
                        <hr class="px-4 border-secondary">
                        <div class="d-flex justify-content-lg-between py-1">
                            <h6 class="card-text text-secondary">Items total : </h6>
                            <h6 class="card-text text-secondary">Rs.<?php echo $product_data["cart_qty"] * $product_data["price"] ?></h6>
                        </div>
                        <div class="d-flex justify-content-lg-between py-1">
                            <h6 class="card-text text-secondary">Delivery Fee : </h6>
                            <h6 class="card-text text-secondary" id="deliveryFee"></h6>
                        </div>
                        <hr class="px-4 border-secondary">
                        <div class="d-flex justify-content-lg-between py-1">
                            <h6 class="card-text fw-bold" id="total">Total : </h6>
                            <h6 class="card-text fw-bold" id="total"></h6>
                        </div>

                        <div class="col-12 d-grid mt-3">
                            <button  onclick="paynow(<?php echo $id ?>);" id="payhere-payment" type="button" class="btn text-light  fw-bold" style="background-color: #dc3545;">Buy Now</button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-3">
        <?php include "footer.php" ?>
    </div>

    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>


</body>

</html>