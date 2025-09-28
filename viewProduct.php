<?php

session_start();

?>

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

    <div class="container-fluid mt-6 min-vh-100">
        <?php
        include "connection.php"; ?>

        <div class="row d-flex justify-content-center">

            <?php
            if (isset($_GET["id"])) {
                $product_id = $_GET["id"];

                $product_rs = Database::search("SELECT * FROM `product_has_model` 
                JOIN `product` 
                ON `product_has_model`.`product_id`=`product`.`product_id` 
                JOIN `brand` 
                ON  `product`.`brand_id`=`brand`.`brand_id`
                JOIN `product_img` ON `product_has_model`.`model_id`=`product_img`.`product_id`
                WHERE `product`.`product_id`='" . $product_id . "' ");

                $product_data = $product_rs->fetch_assoc();

                if (isset($_SESSION["u"])) {
                    $cart_rs = Database::search("SELECT * FROM `cart` WHERE `product_id`='" . $product_id . "' AND `users_email`='" . $_SESSION["u"]["email"] . "'  ");
                    $cart_data = $cart_rs->fetch_assoc();
                }
            ?>
                <div class="card col-12 col-md-4  border-0">
                    <div class="card-body ">
                        <div class=" ">
                            <img src="<?php echo ($product_data["img_path"]) ?>" class="card-img-top" id="vimg">
                        </div>
                    </div>
                </div>


                <div class="card col-12 col-md-6  align-content-center justify-content-center border-0">
                    <div class="card-body">
                        <h6 class="text-secondary"><?php echo $product_data["brand_name"] ?></h6>

                        <div class="d-flex justify-content-between">
                            <h4 class="card-title fw-bold product-title" id="model"><?php echo ($product_data["model"]); ?></h4>
                            <button class="btn btn-light rounded-5"><i><img src="icons/favourites.png" width="20" onclick="addToWishlist(<?php echo ($product_id); ?>);"></i></button>
                        </div>

                        <h6 class="text-secondary " id="price">Rs.<?php echo ($product_data["price"]); ?></h6>

                        <h6 class="fs-7 text-secondary">
                            <li class="fa fa-star  text-warning" id="s1"></li> | <?php echo $product_data["sold_count"] ?> Sold
                        </h6>

                        <hr class="px-4 border-secondary">

                        <div class="row g-1 mb-3 ">

                            <?php

                            $model_img_rs = Database::search("SELECT * FROM `model_data` WHERE `product_id`='".$product_id."' ");
                          
                            $model_img_num = $model_img_rs->num_rows;

                            for ($i = 0; $i < $model_img_num; $i++) {
                                $model_img_data = $model_img_rs->fetch_assoc();
                                $model_id = $model_img_data["model_id"];

                            ?>
                                <div class="col-2">
                                    <div class="card border-0">
                                        <button class="btn p-0" onclick="changeModel(<?php echo $model_id ?>)">
                                            <p></p>
                                            <img src="<?php echo ($model_img_data["img_path"]) ?>" class="card-img-top" alt="...">
                                        </button>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>

                            <?php if ($product_data["qty"] > 0) {
                            ?>
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
                        <div class="row mb-4">
                            <div class="col-12 col-md-6 d-grid pb-1">
                                <button onclick="getQty();" class="btn text-light  fw-bold " data-bs-toggle="modal" data-bs-target="#exampleModal" <?php if ($product_data["qty"] == 0) { ?> disabled <?php } ?> style="background-color: #dc3545;">
                                    Buy Now
                                </button>
                            </div>
                            <div class="col-12 col-md-6 d-grid pb-1">
                                <button class="btn btn-secondary fw-bold " <?php if ($product_data["qty"] == 0) { ?> disabled <?php } ?> onclick="addToCart(<?php echo ($product_id); ?>);">Add To Cart</button>
                            </div>
                        </div>
                    <?php
                }
                    ?>
                    </div>




                </div>


        </div>
    </div>
    <!-- Modal -->
    <div class="modal mt-4 fade align-content-center " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  p-5">
            <div class="modal-content rounded-3">
                <div class="modal-body">
                    <div class="card  border-0 ">
                        <div class="card-body row ">
                            <div class="col-12 col-md-6">
                                <input class="d-none" id="buying_product_id" value="<?php echo $product_id ?>">
                                <img src="<?php echo ($product_data["img_path"]) ?>" class="card-img-top"  id="mimg">
                                
                            </div>
                            <div class="col-12 col-md-6">
                                <h6 class="text-secondary"><?php echo $product_data["brand_name"] ?></h6>
                                <h4 class="card-title fw-bold " id="model"><?php echo ($product_data["model"]); ?></h4>
                                <h6 class="text-secondary " id="price">Rs.<?php echo ($product_data["price"]); ?></h6>
                                <div class="d-flex row mb-2">
                                    <div class="col-12 ">
                                        <h6 class=" text-secondary mt-2" id="price">Quantity</h6>
                                    </div>

                                    <div class="col-12 ">
                                        <button type="button" class="btn rounded-0" onclick="qtyDown(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/minus.png" width="20"></i></button>
                                        <input type="text" class="qtyInput text-center mt-1" value=1 id="pqty" onkeyup="checkQty(<?php echo ($product_data['qty']) ?>);">
                                        <button type="button" class="btn rounded-0" onclick="qtyUp(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/plus.png" width="20"></i></button>
                                    </div>

                                </div>
                                <span class="text-danger" id="qtyWarning"></span>
                                <div class="col-12 d-grid">
                                    <button  class="btn text-light  fw-bold rounded-0" style="background-color: #dc3545;">Buy Now</button>
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

    <?php include "footer.php"; ?>

    <script src="script/script.js"></script>
    <script src="script/bootstrap.bundle.js"></script>

</body>

</html>