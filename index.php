<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body class="">

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>


    <?php include "connection.php" ?>


    <div id="carouselExampleAutoplaying" class="carousel slide mt-6" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="poster/homepage-ga-010-gd-010-pc-(1).avif" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="poster/homepage-gbm-2100a-pc.avif" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="poster/homepage-gm-2110d-pc.avif" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>



    <div class="container ">

        <div id="carouselExampleFade" class="carousel slide carousel-fade">

            <div class="d-block d-flex justify-content-center mt-5">
                <h5 class="text-dark d-block">Popular Items</h5>
            </div>

            <div class="carousel-inner">

                <?php

                for ($i = 0; $i < 2; $i++) {
                    $offset = $i * 4;
                ?>
                    <div class="carousel-item <?php if ($i == 0) { ?> active <?php } ?>">

                        <div class="row row-cols-1 row-cols-md-4 g-4 m-5">
                            <?php

                            $product_rs = Database::search("SELECT * FROM  `product` ");
                            $product_num = $product_rs->num_rows;

                            for ($x = 0; $x < $product_num; $x++) {
                                $product_data = $product_rs->fetch_assoc();
                                $id = $product_data["product_id"];

                                $price_data=Database::search("SELECT `price` FROM `product_has_model` WHERE `product_id`='".$id."' ");
                                $price=$price_data->fetch_assoc();

                                $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                                $img_data = $img_rs->fetch_assoc();
                            ?>
                                <div class="col">
                                    <a href="viewProduct.php?id=<?php echo $id ?>" class="card border-0 text-decoration-none h-100">
                                        <img src="<?php echo ($img_data["img_path"]) ?>" class="border-0 " id="vimg">
                                        <div class="card-body">
                                            <div class="justify-content-center d-flex">
                                                <ul class="list-group list-group-flush d-block">
                                                    <li class="list-group-item">
                                                        <h5 class="card-title "><?php echo ($product_data["product_name"]); ?></h5>
                                                        
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                    </div>

                <?php

                }

                ?>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div id="carouselExampleFade1" class="carousel slide carousel-fade">

            <div class="d-block d-flex justify-content-center mt-5">
                <h5 class="text-dark d-block">New Items</h5>
            </div>

            <div class="carousel-inner">

                <?php
                for ($i = 0; $i < 2; $i++) {
                    $offset = $i * 4;
                ?>
                    <div class="carousel-item <?php if ($i == 0) { ?> active <?php } ?>">

                        <div class="row row-cols-1 row-cols-md-4 g-4 m-5">
                            <?php

                            $product_rs = Database::search("SELECT * FROM `product_has_model` ORDER BY `sold_count` DESC LIMIT 4 OFFSET $offset ");
                            $product_num = $product_rs->num_rows;

                            for ($x = 0; $x < $product_num; $x++) {
                                $product_data = $product_rs->fetch_assoc();
                                $id = $product_data["model_id"];

                                $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $id . "' ");
                                $img_data = $img_rs->fetch_assoc();
                            ?>
                                <div class="col border-0">
                                    <a href="viewProduct.php?id=<?php echo $id ?>" class="card border-0 h-100 text-decoration-none">
                                        <img src="<?php echo ($img_data["img_path"]) ?>" class="" id="vimg">
                                        <div class="card-body">
                                            <div class="justify-content-center d-flex">
                                                <ul class="list-group list-group-flush d-block">
                                                    <li class="list-group-item">
                                                        <h5 class="card-title"><?php echo ($product_data["model"]); ?></h5>
                                                    </li>
                                                    <!-- <li class="list-group-item fw-bold ">Rs.<?php echo ($product_data["price"]); ?>.00</li> -->
                                                </ul>

                                            </div>

                                            <div class="justify-content-center d-flex m-2">
                                            </div>

                                        </div>

                                    </a>
                                </div>


                            <?php
                            }


                            ?>
                        </div>

                    </div>

                <?php

                }


                ?>




            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade1" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade1" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="d-flex justify-content-center mb-5  m-3 pb-5">
            <a href="search.php?search=" class="text-light bg-dark p-2 rounded-2 btn fw-bold mb-4">Shop with us</a>
        </div>

    </div>


    <div class="sticky-bottom ">
        <?php include "footer.php"; ?>
    </div>

    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>
</body>

</html>