<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Product</title>
</head>

<body>
    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <div class="container mt-6 min-vh-100">
        <?php
        include "connection.php"; ?>

        <div class="row d-flex justify-content-center">

            <?php
            if (isset($_GET["id"])) {
                $id = $_GET["id"];

                $product_rs = Database::search("SELECT * FROM `product_has_model` 
                JOIN `product` 
                ON `product_has_model`.`product_id`=`product`.`product_id` 
                JOIN `brand` 
                ON  `product`.`brand_id`=`brand`.`brand_id`
                WHERE `model_id`='" . $id . "' ");
                $product_data = $product_rs->fetch_assoc();

                $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                $img_data = $img_rs->fetch_assoc();

                if (isset($_SESSION["u"])) {
                    $cart_rs = Database::search("SELECT * FROM `cart` WHERE `product_id`='" . $id . "' AND `users_email`='" . $_SESSION["u"]["email"] . "'  ");
                    $cart_data = $cart_rs->fetch_assoc();
                }
            ?>
                <div class="card col-12 col-md-3  border-0">
                    <div class="card-body ">
                        <div class=" ">
                            <img src="<?php echo ($img_data["img_path"]) ?>" class="card-img-top" id="vimg">
                        </div>
                    </div>
                </div>


                <div class="card col-12 col-md-6  align-content-center justify-content-center border-0">
                    <div class="card-body">
                        <h6 class="text-secondary"><?php echo $product_data["brand_name"] ?></h6>

                        <div class="d-flex justify-content-between">
                            <h4 class="card-title fw-bold " id="model"><?php echo ($product_data["model"]); ?></h4>
                            <button class="btn btn-light rounded-5"><i><img src="icons/favourites.png" width="20" onclick="addToWishlist(<?php echo ($id); ?>);"></i></button>
                        </div>

                        <h6 class="text-secondary " id="price">Rs.<?php echo ($product_data["price"]); ?></h6>

                        <h6 class="fs-7 text-secondary">
                            <li class="fa fa-star " id="s1"></li> | <?php echo $product_data["sold_count"] ?> Sold
                        </h6>

                        <hr>

                        <div class="d-flex  g-1 mb-3 model-scrollbar">

                            <?php

                            $model_img_rs = Database::search("SELECT * FROM `product_img` JOIN `product_has_model` ON `product_img`.`product_id`=`product_has_model`.`model_id`");
                            $model_img_num = $model_img_rs->num_rows;

                            for ($i = 0; $i < $model_img_num; $i++) {
                                $model_img_data = $model_img_rs->fetch_assoc();
                                $model_id = $model_img_data["model_id"];

                            ?>
                                <div class="col">
                                    <div class="card border-0">
                                        <button class="btn p-0" onclick="changeModel(<?php echo $model_id ?>)">
                                            <img src="<?php echo ($model_img_data["img_path"]) ?>" class="card-img-top" alt="...">
                                        </button>
                                    </div>
                                </div>
                                
                            <?php
                            }
                            ?>
                        </div>

                        <?php if ($product_data["qty"] > 0) {
                        ?>
                            <!-- <div class="btn-group " role="group" aria-label="Basic example">

                                <button type="button" class="btn rounded-0 bg-secondary-subtle ms-1" onclick="qtyDown(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/minus.png" width="15"></i></button>
                                <input type="text" class="qtyInput text-center mt-1" value=1 id="pqty" onkeyup="checkQty(<?php echo ($product_data['qty']) ?>);">
                                <button type="button" class="btn rounded-0  bg-secondary-subtle  me-1" onclick="qtyUp(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/plus.png" width="15"></i></button>

                                <span class="text-danger" id="qtyWarning"></span>
                            </div> -->

                            <div>
                                <?php
                                if (isset($_SESSION["u"])) {
                                ?>
                                    <span class="text-danger"><?php if ($cart_data > 0) { ?> You have <?php echo ($cart_data["cart_qty"]); ?> of this item in your cart <?php } ?></span>
                                <?php
                                }
                                ?>
                            </div>

                        <?php
                        } ?>

                    </div>

                    <?php
                    if ($product_data["qty"] == 0) {
                    ?>
                        <div class="d-flex align-content-center justify-content-center">
                            <h3 class="text-secondary mt-5">#Out of stock</h3>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="row mb-4 px-3">
                        <div class="col-12 col-md-6 d-grid pb-1">
                            <button onclick="getQty();" class="btn text-light  fw-bold rounded-0" data-bs-toggle="modal" data-bs-target="#exampleModal" <?php if ($product_data["qty"] == 0) { ?> disabled <?php } ?> style="background-color: #dc3545;">
                                Buy Now
                            </button>

                            <!-- Modal -->
                            <div class="modal fade align-content-center " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-3">
                                        <div class="modal-body">
                                            <div class="card border-0">
                                                <div class="card-body row ">
                                                    <div class="col-6">
                                                        <img src="<?php echo ($img_data["img_path"]) ?>" class="card-img-top" id="mimg">
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="text-secondary"><?php echo $product_data["brand_name"] ?></h6>
                                                        <h4 class="card-title fw-bold " id="model"><?php echo ($product_data["model"]); ?></h4>
                                                        <h6 class="text-secondary " id="price">Rs.<?php echo ($product_data["price"]); ?></h6>
                                                        <div class="d-flex row mb-2">
                                                            <div class="col-12 ">
                                                                <h6 class=" text-secondary mt-2" id="price">Quantity</h6>
                                                            </div>
                                                            
                                                            <div class="col-12 ">
                                                                <button  type="button" class="btn rounded-0" onclick="qtyDown(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/minus.png" width="20"></i></button>
                                                                <input type="text" class="qtyInput text-center mt-1" value=1 id="pqty" onkeyup="checkQty(<?php echo ($product_data['qty']) ?>);">
                                                                <button  type="button" class="btn rounded-0" onclick="qtyUp(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/plus.png" width="20"></i></button>
                                                            </div>

                                                        </div>
                                                        <span class="text-danger" id="qtyWarning"></span>
                                                        <div class="col-12 d-grid">
                                                            <button type="button" class="btn text-light  fw-bold rounded-0" style="background-color: #dc3545;">Buy Now</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script src="script.js"></script>
                                <script src="bootstrap.bundle.js"></script>
                            </div>

                        </div>
                        <div class="col-12 col-md-6 d-grid pb-1">
                            <button class="btn btn-secondary fw-bold rounded-0" <?php if ($product_data["qty"] == 0) { ?> disabled <?php } ?> onclick="addToCart(<?php echo ($id); ?>);">Add To Cart</button>
                        </div>
                    </div>
                <?php
            }
                ?>
                </div>


        </div>
    </div>

    <?php include "footer.php"; ?>

    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html>