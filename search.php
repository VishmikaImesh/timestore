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

    <?php include "connection.php";
    ?>

    <?php
    if (isset($_SESSION["u"]["email"])) {
        $email = $_SESSION["u"]["email"];
    }


    $search_rs = Database::search("SELECT * FROM `product` ");
    $search_num = $search_rs->num_rows;


    if ($search_num > 0) {
    ?>
        <div class="container pb-5   mt-6">
            <div class="row vh-100 d-flex justify-content-center ">

                <div class="col-4 col-md-2 ">
                    <div class="card" style="height:65vh;">
                        <div class="card-body bg-body-tertiary ">
                            <h5 class="card-title mb-2">Search Options</h5>
                            <hr>

                            <div>
                                <h6 class="card-subtitle mb-2 text-body-secondary">Search</h6>
                                <input type="text" class="form-control mb-4" value="<?php echo ($_GET["search"]); ?>" id="productSearch">
                            </div>

                            <div class="mb-4 row">
                                <h6 class="card-subtitle mb-2 text-body-secondary">Gender</h6>
                                <div class="col-12">

                                    <input type="radio" name="g" value="1" id="male" class="me-2 form-check-input">
                                    <label for="" class="text-dark form-label ">Male</label>

                                </div>

                                <div class="col-12">

                                    <input type="radio" name="g" value="2" id="female" class="form-check-input">
                                    <label for="" class="text-dark">Female</label>

                                </div>


                            </div>

                            <div class="mb-4 row">
                                <h6 class="card-subtitle mb-2 text-body-secondary">Material</h6>
                                <div class="col-12">

                                   
                                    <input type="radio" name="mt" value="4" id="steel" class="me-2 form-check-input">
                                    <label for="" class="text-dark form-label ">Steel</label>

                                </div>

                                <div class="col-12">

                                    
                                    <input type="radio" name="mt" value="3" id="leather" class="me-2 form-check-input">
                                    <label for="" class="text-dark">Leather</label>

                                </div>


                            </div>

                            <div class="mb-4 row">
                                <h6 class="card-subtitle mb-2 text-body-secondary">Type</h6>
                                <div class="col-12">

                                    
                                    <input type="radio" name="ty" value="5" id="digital" class="me-2 form-check-input">
                                    <label for="" class="text-dark form-label ">Digital</label>

                                </div>

                                <div class="col-12">

                                    
                                    <input type="radio" name="ty" value="6" id="analog" class="me-2 form-check-input">
                                    <label for="" class="text-dark">Analog</label>
                                </div>


                            </div>


                            <div class="d-grid">
                                <button class="btn btn-dark fw-bold rounded-5" onclick="search();">Search</button>
                            </div>


                        </div>
                    </div>

                </div>

                <div class="col-8 col-md-10">

                    <div class="row row-cols-1 row-cols-md-4 g-4 flex-row  " id="searchResults">

                        <?php

                        for ($x = 0; $x < $search_num; $x++) {
                            $search_data = $search_rs->fetch_assoc();


                            $img_rs = Database::search("SELECT * FROM `product_img` ");
                            $img_data = $img_rs->fetch_assoc();
                        ?>

                            <div class="col" id="searchResults">
                                <a href="viewProduct.php?id=<?php echo ($id); ?>" class="card h-100 text-decoration-none" >
                                    <img src="<?php echo ($img_data["img_path"]) ?>" class="card-img-top" id="vimg">
                                    <div class="card-body">
                                        <div class="justify-content-center d-flex">
                                            <ul class="list-group list-group-flush d-block">
                                                <li class="list-group-item">
                                                    <h5 class="card-title"><?php echo ($search_data["title"]); ?></h5>
                                                </li>
                                                <li class="list-group-item fw-bold ">Rs.<?php echo ($search_data["price"]); ?>.00</li>
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


            </div>
            <div class=" mt-6"></div>
        </div>

        <!-- <?php include "footer.php"; ?> -->


    <?php

    } else {
    ?>
        <div class="vh-100">
            <div class=" container d-flex flex-column justify-content-center h-75">
                <h1>Your watchlist is empty</h1>
                <a href="index.php" class="btn btn-dark col-2 fw-bold">return to home</a>

            </div>


        </div>



    <?php
    }
    ?>

    <?php include "footer.php"; ?>


    <script src="script/script.js"></script>

</body>

</html>