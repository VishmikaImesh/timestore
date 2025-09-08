<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Watchlist</title>
</head>

<body onload="">

    <div class="fixed-top">
        <?php include "header.php"; ?>
    </div>

    <div class="container mt-5">
        <div class="row d-flex justify-content-center ">
            <?php include "connection.php";
            ?>

            <?php
            if (isset($_SESSION["u"]["email"])) {
                $email = $_SESSION["u"]["email"];
            }

            $search_rs = Database::search("SELECT * FROM `product_has_model` ");
            $search_num = $search_rs->num_rows;
            ?>
            <div class="row row-cols-1 row-cols-md-2 g-5  align-content-center">
                <div class="col-12 col-md-3 mb-5 ">
                    <div class="card border-0 " style="height:65vh;">
                        <div class="card-body ">
                            <h5 class="card-title mb-2">Search Options</h5>
                            <hr>

                            <div class="mb-4">

                                <h6 class="card-title text-body-secondary ">Gender</h6>
                                <div class="">
                                    <input type="checkbox" name="g" value="1" id="male" class=" form-check-input">
                                    <label for="" class="text-secondary form-label ">Male</label>
                                </div>
                                <div class="">
                                    <input type="checkbox" name="g" value="2" id="female" class="form-check-input">
                                    <label for="" class="text-secondary">Female</label>
                                </div>

                            </div>

                            <div class="mb-4 row">
                                <h6 class="card-subtitle mb-2 text-body-secondary">Material</h6>
                                <div class="col-12">


                                    <input type="checkbox" name="mt" value="4" id="steel" class="me-2 form-check-input">
                                    <label for="" class="text-secondary form-label ">Steel</label>

                                </div>

                                <div class="col-12">


                                    <input type="checkbox" name="mt" value="3" id="leather" class="me-2 form-check-input">
                                    <label for="" class="text-secondary">Leather</label>

                                </div>


                            </div>

                            <div class="mb-4 row">
                                <h6 class="card-subtitle mb-2 text-body-secondary">Type</h6>
                                <div class="col-12">


                                    <input type="checkbox" name="ty" value="5" id="digital" class="me-2 form-check-input">
                                    <label for="" class="text-secondary form-label ">Digital</label>

                                </div>

                                <div class="col-12">


                                    <input type="checkbox" name="ty" value="6" id="analog" class="me-2 form-check-input">
                                    <label for="" class="text-secondary">Analog</label>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-9 " id="searchResults">
                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        <?php
                        for ($x = 0; $x < $search_num; $x++) {
                            $search_data = $search_rs->fetch_assoc();
                            $id=$search_data["model_id"];

                            $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`= $id");
                            $img_data = $img_rs->fetch_assoc();
                        ?>
                            <div class="col" id="searchResults">
                                <a href="viewProduct.php?id=<?php echo ($id); ?>" class="card border-0 h-100 text-decoration-none ">
                                    <img src="<?php echo ($img_data["img_path"]) ?>" class="card-img-top" id="vimg">
                                    <div class="card-body">
                                        <div class="justify-content-center d-flex">
                                            <ul class="list-group list-group-flush d-block">
                                                <li class="list-group-item">
                                                    <h5 class="card-title product-title"><?php echo ($search_data["model"]); ?></h5>
                                                </li>
                                                <li class="list-group-item fw-bold ">Rs.<?php echo ($search_data["price"]); ?>.00</li>
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
            </div>

        </div>
    </div>
    <div class="mt-6">
        <?php include "footer.php"; ?>
    </div>


    <script src="script/script.js"></script>

</body>

</html>