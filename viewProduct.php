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

    <?php include "header.php";
    include "connection.php";

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $id . "' ");
        $product_data = $product_rs->fetch_assoc();

        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
        $img_data = $img_rs->fetch_assoc();

        if (isset($_SESSION["u"])) {
            $cart_rs = Database::search("SELECT * FROM `cart` WHERE `product_id`='" . $id . "' AND `users_email`='" . $_SESSION["u"]["email"] . "'  ");
            $cart_data = $cart_rs->fetch_assoc();
        }




    ?>

        <div class="container">

            <div class="row mt-5 m-5 d-flex justify-content-center h-75">

                <div class="card col-4 col-md-3">
                    <div class="card-body">
                        <div class="py-5 ">
                            <img src="<?php echo ($img_data["img_path"]) ?>" class="card-img-top" id="vimg">
                        </div>
                    </div>

                </div>

                <div class="card col-8 col-md-6 d-flex align-content-center justify-content-center ">
                    <div class="card-body">
                        <h4 class="card-title fw-bold"><?php echo ($product_data["title"]); ?></h4>
                        <h2 class="card-title fw-bold"></h2>
                        <h6 class="text-secondary "> <?php echo ($product_data["price"]); ?></h6>
                        <h6 class="fs-7 text-secondary">Price Down Before Taxes</h6>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <div class="justify-content-center d-flex m-2 fs-5">

                                <li class="fa fa-star " id="s1"></li>
                                <li class="fa fa-star " id="s2"></li>
                                <li class="fa fa-star " id="s3"></li>
                                <li class="fa fa-star" id="s4"></li>
                                <li class="fa fa-star" id="s5"></li>

                            </div>
                            <div class="justify-content-end d-flex">
                                <button class="btn btn-light rounded-5"><i><img src="icons/favourites.png" width="20" onclick="addToWishlist(<?php echo ($id); ?>);"></i></button>
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">Modal Title</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($product_data["qty"] > 0) {
                        ?>
                            <div class="btn-group" role="group" aria-label="Basic example">

                                <button type="button" class="btn rounded-5 ms-1" onclick="qtyDown(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/minus.png" width="15"></i></button>
                                <input type="text" class="qtyInput text-center" value=1 id="pqty" onkeyup="checkQty(<?php echo ($product_data['qty']) ?>);">
                                <button type="button" class="btn rounded-5 me-1" onclick="qtyUp(<?php echo ($product_data['qty']) ?>);"><i><img src="icons/plus.png" width="15"></i></button>

                                <span class="text-danger" id="qtyWarning"></span>
                            </div>
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
                    if (isset($_SESSION["u"])) {
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
                                <button onclick="getQty();" class="btn text-light  fw-bold rounded-4" data-bs-toggle="modal" data-bs-target="#exampleModal" <?php if ($product_data["qty"] == 0) { ?> disabled <?php } ?> style="background-color: #dc3545;">
                                    Buy Now
                                </button>

                                

                                <!-- Modal -->
                                <div class="modal fade align-content-center" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"><?php echo ($product_data["title"]); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card col-4 col-md-3">

                                                    <div class="card-body">
                                                        <div class="">
                                                            <img src="<?php echo ($img_data["img_path"]) ?>" class="card-img-top" id="vimg">
                                                        </div>
                                                        <input type="text" id="getQty">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary">Buy Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-md-6 d-grid pb-1">
                                <button class="btn btn-secondary fw-bold rounded-4" <?php if ($product_data["qty"] == 0) { ?> disabled <?php } ?> onclick="addToCart(<?php echo ($id); ?>);">Add To Cart</button>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

            </div>

        </div>


        <div class="fixed-bottom">
            <?php include "footer.php"; ?>
        </div>


    <?php
    } else {
    ?>
        <h2 class="d-block text-center mt-5">Something went wrong return to home and try again...!</h2><br>
        <div class="d-flex justify-content-center align-content-centerm-3">
            <a href="index.php" class="text-light bg-dark p-2 rounded-2 btn fw-bold mb-4">Home</a>
        </div>

    <?php
    }

    ?>





<script src="script.js"></script>
<script src="bootstrap.bundle.js"></script>

</body>

</html>