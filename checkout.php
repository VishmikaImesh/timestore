<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>buyProduct</title>
</head>

<body class="bg-secondary-subtle">

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php";


    $email = $_SESSION["u"]["email"];;

    if (isset($_GET["id"]) & isset($_GET["qty"])) {
        $id = $_GET["id"];
        $qty = $_GET["qty"];
        
    }



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

    <div class="container min-vh-100 mt-6 ">
        <div class="row">
            <div class="col-12 col-md-7 border-0 ">
                <div class="card m-1">
                    <div class="card-header d-flex justify-content-between pt-2">
                        <h6 class="fw-bold text-secondary m-0">Delivery Method</h6>
                        <h6 class="fw-bold text-primary m-0">Edit</h6>
                    </div>

                    <div class="card-body">
                        <figure>
                            <blockquote class="blockquote">
                                <h5 class="card-text col-12 col-lg-5 "><?php echo $user_data["fname"] . ' ' . $user_data["lname"] ?></h6>
                                    <h6 class="text-secondary col-12 col-lg-6"><?php echo $user_data["mobile"] ?></h6>
                                    <p class="fs-6 "><?php echo $user_data["address_line1"] . ',' . $user_data["address_line2"] . ',' . $user_data["district_name"] . ',' . $user_data["province_name"] . ' Province'  ?></p>
                            </blockquote>
                        </figure>
                    </div>
                </div>

                <div class="card m-1 ">
                    <div class="card-header fw-bold text-secondary">
                        Delivery Method
                    </div>
                    <div class="card-body  row">

                        <div class="card col-8 col-md-6 col-lg-5 col-xl-4 border-0">
                            <button class="btn m-1 p-0 border-secondary-subtle" id="deliverytoption1" onclick="changeDeliveryOption('deliverytoption1',<?php echo $product_data['cart_qty'] * $product_data['price'] ?>,250);">
                                <div class="card-body row  text-start">
                                    <div class="col-1">
                                        <input id="checkDeliveryMethod1" class="btn rounded-5" type="checkbox"></input>
                                    </div>

                                    <div class="col-10">
                                        <h5 class="text-secondary">Standard</h5>
                                        <p class="card-text">Rs.250</p>
                                       
                                    </div>

                                </div>
                            </button>
                        </div>

                        <div class="card col-8 col-md-6 col-lg-5 col-xl-4 border-0">
                            <button class="btn m-1 p-0 border-secondary-subtle" id="deliverytoption2" onclick="changeDeliveryOption('deliverytoption2',<?php echo $product_data['cart_qty'] * $product_data['price'] ?>,500);">
                                <div class="card-body row  text-start">
                                    <div class="col-1">
                                        <input id="checkDeliveryMethod2" class="btn rounded-5" type="checkbox"></input>
                                    </div>

                                    <div class="col-10 ">
                                        <h5 class="text-secondary">Express</h5>
                                        <p class="card-text">Rs.500 </p>
                                        <?php echo date('m-d') ?>
                                    </div>

                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-bold text-secondary">
                        Package Details
                    </div>
                    <div class="card-body">
                        <div class="card mb-3 border-0" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="<?php echo $img_data["img_path"]; ?>" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title text-secondary"><?php echo $product_data["brand_name"] ?></h5>
                                        <h5 class="card-title"><?php echo $product_data["model"] ?></h5>
                                        <h6 class="card-text text-secondary">Rs.<?php echo $product_data["price"] ?> For each Item</h6>
                                        <h6 class="card-text text-secondary"><?php echo $product_data["cart_qty"] ?> Items</h6>
                                        <h6 class="card-text text-secondary">Sub Total : <?php echo $product_data["cart_qty"] * $product_data["price"] ?> </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 col-md-4 border-0">
                <div class="card m-1">
                    <div class="card-header fw-bold text-secondary">
                        Order Summary
                    </div>
                    <div class="card-body row">
                        <h6 class="card-text">Items total: <?php echo $product_data["cart_qty"] * $product_data["price"] ?></h6>
                        <h6 class="card-text" id="deliveryFee">Delivery Fee : </h6>
                        <hr>
                        <h6 class="card-text" id="total">Total : </h6>
                        <div class="col-12 d-grid mt-3">
                            <button type="button" class="btn text-light  fw-bold rounded-0" style="background-color: #dc3545;">Buy Now</button>
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

</body>

</html>