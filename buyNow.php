<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    <?php

    $cart_rs = Database::search("SELECT * FROM `cart` INNER JOIN `product` ON `cart`.`product_id`=`product`.`id` ");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num > 0) {
    ?>

        <div class="vh-100">

            <div class="h-25"></div>

            <div class="container ">

                <div class="row row-cols-1 row-cols-md-2 g-4 d-flex pb-5 pt-4 pb-4 ">

                    <?php

                    for ($x = 0; $x < $cart_num; $x++) {

                        $cart_data = $cart_rs->fetch_assoc();
                        $id = $cart_data["id"];

                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                        $img_data = $img_rs->fetch_assoc();
                        
                    ?>
                        <div class="col">
                            <div class="card mb-3 mx-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4 py-3">
                                        <img src="<?php echo ($img_data["img_path"]); ?>" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title"><?php echo ($cart_data["title"]); ?></h5>
                                                <div class="col-1 col-md-2 d-grid">
                                                    <button class="btn btn-light rounded-4" onclick="removeFromCart(<?php echo ($cart_data['cart_id']); ?>,<?php echo ($cart_data['id']); ?>);"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                                                </div>
                                            </div>
                                            <h2 class="card-title fw-bold"><?php echo ($cart_data["price"]); ?></h2>
                                            <div class=" fs-5">
                                                <li class="fa fa-star checked " id="s1"></li>
                                                <li class="fa fa-star checked " id="s2"></li>
                                                <li class="fa fa-star checked" id="s3"></li>
                                                <li class="fa fa-star checked" id="s4"></li>
                                                <li class="fa fa-star" id="s5"></li>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-12  d-grid pb-1 px-4">
                                            <button class="btn text-light fw-bold rounded-4 mb-3" style="background-color: #dc3545;">Buy Now</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>

            </div>
            <div class="h-25"></div>
        </div>

        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your cart is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">Start Shopping</a>

            </div>
            <div class="h-25 bg-dark ">
                <?php include "footer.php"; ?>
            </div>

        </div>

    <?php
    }

    ?>


    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Cart</title>
</head>

<body>

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <?php include "connection.php"; ?>

    

    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html>