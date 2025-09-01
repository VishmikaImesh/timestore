<?php

session_start();

if(isset($_SESSION["u"])){
   header("location:index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>

<body class="bg">
    <div class="container">
        <div class="row d-flex justify-content-center vh-100 align-content-center ">
            <div class="col-10 col-md-6 col-lg-4 card custom-card mt-5">
                <div class="card-body">     

                    <h3 class="text-center mt-3 mb-4 text-light fw-bold">Login</h3>
                    <div class="">
                        <label for="email">Email</label>
                        <label for="" id="em" class="text-warning fs-7"></label>
                        <input type="text" class="form-control cc mb-3" id="email" value=<?php if(isset($_COOKIE["email"])){echo($_COOKIE["email"]); } ?>>
                    </div>
                    <div class="">
                        <label for="pw">Password</label>
                        <label for="" id="p" class="text-warning fs-7"></label>
                        <input type="text" class="form-control cc mb-3" id="pw" value=<?php if(isset($_COOKIE["pw"])){echo($_COOKIE["pw"]); } ?>>
                    </div>
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <a href="" class="text-decoration-none text-light">Forgot Password..?</a>
                        </div>
                        <div class="col-6 d-flex justify-align-content-end ">
                            <input type="checkbox" id="rememberMe">
                            <label for="rememberMe">Remember me</label>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button class="btn btn-light mb-3 rounded-5 fw-bold" onclick="signIn();">Sign In</button>
                    </div>
                    <div class="text-center m-3">
                        <a href="signup.php" class="text-light text-decoration-none">Don't Have Account? Sign up Here...</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <script src="script/script.js"></script>
</body>

</html>