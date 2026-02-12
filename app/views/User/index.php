<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeStore</title>
    <link rel="stylesheet" href="/timestore/public/assets/style/bootstrap.css">
    <link rel="stylesheet" href="/timestore/public/assets/style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>

    <?php
    include "header.php";
    ?>


    <div class="container my-4">
        <div class="bg-black text-white rounded-4 p-5 overflow-hidden position-relative">
            <div class="row align-items-center">
                <div class="col-md-6 py-5 px-md-5 z-1">
                    <h1 class="display-5 fw-bold mb-4">Elegance Defined.<br>The New Collection.</h1>
                    <a href="/timestore/search" class="btn btn-light fw-semibold px-4 py-2 rounded-2">Shop Now</a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://purepng.com/public/uploads/large/purepng.com-gold-watchwatchclockgold-watchgoldaccessory-14215269771117pckb.png" alt="Luxury Watch" class="img-fluid" style="max-height: 350px;">
                </div>
            </div>
        </div>
    </div>

    <div  class="container my-5">
        <h3 class="fw-bold mb-4">Popular Items</h3>

        <div id="populartItemsBody" class="row g-4">

            <div class="col-12 col-sm-6 col-lg-3">

                <a href="/timestore/viewProduct" class="text-decoration-none">
                    <div class="card border-0 h-100">
                        <div class="bg-light rounded-3 p-4 text-center mb-3">
                            <img src="" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                        </div>
                        <div class="card-body px-0 pt-0">
                            <small class="text-muted fw-semibold"></small>
                            <h6 class="card-title  fw-bold mb-1"></h6>
                            <p class="fw-bold text-dark"></p>
                        </div>
                    </div>
                </a>

            </div>



            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-rolex-watchrolex-watchrolexwatchtime-1421526964324i4e6r.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">Rolex</small>
                        <h6 class="card-title fw-bold mb-1">Rolex Cosmograph Daytona</h6>
                        <p class="fw-bold text-dark">$225.00</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-mens-wrist-watchwatchclocktimepiecewrist-watchmens-wrist-watch-1421526966671eclul.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">Omega</small>
                        <h6 class="card-title fw-bold mb-1">Omega Speedmaster Professional</h6>
                        <p class="fw-bold text-dark">$395.00</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-blue-watchwatchclocktimeblue-14215269653453q41k.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">TAG Heuer</small>
                        <h6 class="card-title fw-bold mb-1">TAG Heuer Carrera</h6>
                        <p class="fw-bold text-dark">$299.00</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container my-5">
        <h3 class="fw-bold mb-4">New Items</h3>
        <div class="row g-4">

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-silver-watchwatchclocktimepiecesilver-1421526963701j2x4p.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">Patek Philippe</small>
                        <h6 class="card-title fw-bold mb-1">Patek Philippe Nautilus</h6>
                        <p class="fw-bold text-dark">$340.00</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-silver-watchwatchclocktimepiecesilver-1421526963701j2x4p.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">Audemars Piguet</small>
                        <h6 class="card-title fw-bold mb-1">Audemars Piguet Royal Oak</h6>
                        <p class="fw-bold text-dark">$245.00</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-mens-wrist-watchwatchclocktimepiecewrist-watchmens-wrist-watch-1421526966671eclul.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">Cartier</small>
                        <h6 class="card-title fw-bold mb-1">Cartier Santos</h6>
                        <p class="fw-bold text-dark">$235.00</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 h-100">
                    <div class="bg-light rounded-3 p-4 text-center mb-3">
                        <img src="https://purepng.com/public/uploads/large/purepng.com-black-watchwatchclocktimewrist-watchblack-1421526965688v5l2x.png" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                    </div>
                    <div class="card-body px-0 pt-0">
                        <small class="text-muted fw-semibold">IWC</small>
                        <h6 class="card-title fw-bold mb-1">Portugieser</h6>
                        <p class="fw-bold text-dark">$289.00</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include "footer.php"; ?>

    <script src="/timestore/public/assets/Script/bootstrap.bundle.js"></script>
    <script src="/timestore/public/assets/Script/User/index.js"></script>
</body>

</html>