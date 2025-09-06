<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <nav class="d-block">
        <div class="container">

            <ul class="vh-100 sidenav py-3 d-none" id="sidenav">
                <div class="d-flex justify-content-between m-2">
                    <li class="py-3">
                        <a href="index.php" class="text-decoration-none">
                            <h3 class="text-light">TimeStore</h3>
                        </a>
                    </li>
                    <li class="mt-2">
                        <button class="hideSideNav btn " onclick="hideSideNav();"><img src="icons/forward.png" height="20" width="20" alt=""></button>
                    </li>
                </div>

                <div class="input-group bg-light rounded-3 mb-3 pe-3">
                    <input type="text" class="form-control" placeholder="Search">
                    <button class="input-group-text bg-black rounded-5 border-0 m-1" id="basic-addon1"><img src="icons/search.png" height="19" width="19" alt="" onclick="search();"></button>
                </div>

                <?php



                if (!isset($_SESSION["u"])) {
                ?>
                    <li class=" mt-2 mb-2 d-grid  me-3">
                        <a href="signin.php" class="btn btn-dark rounded-4 fw-bold ">Sign in</a>
                    </li>
                    <li class="d-flex justify-content-center">
                        <span><a class="text-decoration-none text-secondary" href="#">Register</a></span>
                    </li>

                <?php
                } else {
                ?> <li class=" mt-2 mb-2 d-grid  me-3">
                        <a class="btn btn-dark rounded-4 fw-bold ">Welcome</a>
                    </li><?php
                        }

                            ?>

                <hr class="me-4">

                <li><a class="dropdown-item d-flex justify-content-center" href="watchlist.php">Watchlist</a></li>

                <?php
                if (isset($_SESSION["u"])) {
                ?>
                    <li><a class="dropdown-item d-flex justify-content-center" href="cart.php">Cart</a></li>
                    <li><a class="dropdown-item d-flex justify-content-center" href="history.php">History</a></li>
                <?php
                }
                ?>
                <li><a class="dropdown-item d-flex justify-content-center" href="watchlist.php">Watchlist</a></li>
                <hr class="me-4">

                <li><a class="dropdown-item text-secondary d-flex justify-content-center" href="#">Setting</a></li>
                <li><a class="dropdown-item text-secondary d-flex justify-content-center" href="#">Seller Log in</a></li>
                <li><a class="dropdown-item text-secondary d-flex justify-content-center" href="#">Buyer Protection</a></li>
                <li><a class="dropdown-item text-secondary d-flex justify-content-center" href="#">Help Center</a></li>
                <li><a class="dropdown-item text-secondary d-flex justify-content-center" href="#">Accessibility</a></li>




            </ul>
        </div>
    </nav>


    <header class="bg-dark py-1 mb-5 d-block">

        <div class="container px-5 mnav">
            <ul class="d-flex mt-3 ">
                <li class="col-3">
                    <a href="index.php" class="text-decoration-none">
                        <h3 class="text-light">TimeStore</h3>
                    </a>
                </li>

                <?php
                $current_page = $_SERVER['PHP_SELF'];
                $search_page = "/timestore/search.php";

                if ($current_page != $search_page) {
                ?>
                    <li class="input-group bg-light rounded-3">
                        <input type="text" class="form-control" placeholder="Search" id="search">
                        <button class="input-group-text bg-black rounded-5 border-0 m-1" id="basic-addon1"><img src="icons/search.png" height="19" width="19" alt="" onclick="searchText();"></button>
                    </li>

                <?php
                } else {
                ?>
                    <li class="col-8">

                    </li>

                <?php
                }

                ?>

                <?php
                if (isset($_SESSION["u"])) {
                ?>
                    <li class="">
                        <a href="cart.php" class="text-decoration-none mx-4"><img src="icons/cart.png" alt="" height="40" width="40"></a>
                    </li>
                <?php
                } ?>

                <li class="nav-item dropdown col-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="icons/profile.png" height="40" width="40" alt="">
                    </a>
                    <ul class="dropdown-menu px-2">
                        <?php



                        if (!isset($_SESSION["u"])) {
                        ?>
                            <li class=" mt-2 mb-2 d-grid ">
                                <a href="signin.php" class="btn btn-dark rounded-4 fw-bold ">Sign in</a>
                            </li>
                            <hr class="">
                            <li class="d-flex justify-content-center">
                                <span><a class="text-decoration-none text-secondary" href="#">Register</a></span>
                            </li>

                        <?php
                        } else {
                        ?> <li class=" mt-2 mb-2 d-grid  me-3">
                                <a class="btn btn-dark rounded-4 fw-bold ms-3 ">Welcome</a>
                            </li>
                            <li><a class="dropdown-item d-flex justify-content-center" href="cart.php">Cart</a></li>
                            <li><a class="dropdown-item d-flex justify-content-center" href="history.php">History</a></li>
                            <li><a class="dropdown-item d-flex justify-content-center" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item d-flex justify-content-center" href="watchlist.php">Watchlist</a></li>
                            <hr class="">
                            <li><a class="dropdown-item text-danger my-1 d-flex justify-content-center" onclick="logOut();">Log Out</a></li>

                        <?php

                        }

                        ?>



                    </ul>
                </li>
            </ul>

        </div>
        <div class="d-flex justify-content-between mx-2">
            <li class="ms-3">
                <a href="index.php" class="text-decoration-none">
                    <h3 class="text-light">TimeStore</h3>
                </a>
            </li>
            <li class="mt-3">
                <button class="hideSideNav btn " onclick="hideSideNav();"><img src="icons/menu.svg" height="20" width="20" alt=""></button>
            </li>
        </div>
        <!-- <div class="ms-4 d-flex justify-content-between">
            <h3 class="text-light showSideNav align-content-center">TimeStore</h3>
            <button onclick="showSideNav();" class="showSideNav btn btn-dark m-2"><img src="icons/menu.svg" alt="" width="30" height="30"></button>
        </div> -->
    </header>




    <script src="script/script.js"></script>
    <script src="script/bootstrap.bundle.js"></script>

</body>

</html>