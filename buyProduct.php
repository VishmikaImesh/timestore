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

<?php include "connection.php";

$user_rs = Database::search("SELECT *,`cities`.`name_en` AS `city_name`,`districts`.`name_en` AS `district_name`, `provinces`.`name_en` AS `province_name`  FROM `users` 
                JOIN `user_address` ON `users`.`email`=`user_address`.`users_email`
                JOIN `cities` ON `user_address`.`city_id`=`cities`.`city_id`
                JOIN `districts` ON `cities`.`district_id`=`districts`.`district_id` 
                JOIN `provinces` ON `districts`.`province_id`=`provinces`.`province_id` ");

$user_data = $user_rs->fetch_assoc();
?>

<body class="bg-secondary-subtle">

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <div class="container min-vh-100 mt-6 ">
        <div class="row">
            <div class="col-12 col-md-7 border-0 ">
                <div class="card m-1">
                    <div class="card-header">
                        Delivery1
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
                    <div class="card-header">
                        Delivery2
                    </div>
                    <div class="card-body  row">

                        <div class="card col-8 col-md-6 col-lg-5 col-xl-4 border-0">
                            <button class="btn m-1 p-0 border-secondary-subtle" id="deliverytoption1" onclick="changeDeliveryOption('deliverytoption1');">
                                <div class="card-body text-start">
                                    <h5 class="text-secondary">Standard</h5>
                                    <p class="card-text">Rs.250</p>
                                    <?php echo date('m-d') ?>
                                </div>
                            </button>
                        </div>

                        <div class="card col-8 col-md-6 col-lg-5 col-xl-4 border-0">
                            <button class="btn m-1 p-0 border-secondary-subtle" id="deliverytoption2" onclick="changeDeliveryOption('deliverytoption2');">
                                <div class="card-body  text-start">
                                    <h5 class="text-secondary">Express</h5>
                                    <p class="card-text">Rs.500</p>
                                    <?php echo date('m-d') ?>
                                </div>
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-12 col-md-4 border-0">
                <div class="card m-1">
                    <div class="card-header">
                        Delivery3
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