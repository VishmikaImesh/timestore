<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body class="bg">

    <div class="container">
        <div class="row d-flex justify-content-center vh-100 align-content-center ">
            <div class="col-10 col-md-6 col-lg-4 card custom-card mt-5">
                <div class="card-body">
                    <h3 class="text-center mt-3 mb-4 text-light fw-bold">Sign Up</h3>
                    <div class="row">
                        <div class="col-6">
                            <label for="fname">First Name</label>
                            <label class="text-danger fs-7" id="fw"></label>
                            <label id="fn" class="text-warning fs-7 "></label>
                            <input type="text" class="form-control cc mb-3" id="fname">
                        </div>
                        <div class="col-6">
                            <label for="lname">Last Name</label>
                            <label class="text-danger fs-7" id="lw"></label>
                            <label for="" id="ln" class="text-warning fs-7"></label>
                            <input type="text" class="form-control cc mb-3" id="lname">
                        </div>
                    </div>
                    <div class="">
                        <label for="email">Email</label>
                        <label class="text-danger fs-7" id="ew"></label>
                        <label for="" id="em" class="text-warning fs-7"></label>
                        <input type="text" class="form-control cc mb-3" id="email">
                    </div>
                    <div class="">
                        <label for="pw">Mobile</label>
                        <label class="text-danger fs-7" id="mw"></label>
                        <label for="" id="mb" class="text-warning fs-7"></label>
                        <input type="text" class="form-control cc mb-3" id="mobile">
                    </div>
                    <div class="">
                        <label for="pw">Password</label>
                        <label class="text-danger fs-7" id="pw"></label>
                        <label for="" id="p" class="text-warning fs-7"></label>
                        <input type="text" class="form-control cc mb-3" id="password">
                    </div>
                    <div class="">
                        <label for="pwa">Retype Password</label>
                        <label class="text-danger fs-7" id="rpw"></label>
                        <label for="" id="pa" class="text-warning fs-7"></label>
                        <input type="text" class="form-control cc mb-4" id="rtpassword">
                    </div>
                    <div class="">
                        <label for="gender" class="d-block my-2">Gender</label>
                        <label class="text-danger fs-7" id="gw"></label>
                        <label for="male">Male</label>
                        <input type="radio" class=" mb-4 mx-3" id="male" value="1" name="gender">
                        <label for="female">Female</label>
                        <input type="radio" class=" mb-4" id="female" value="2" name="gender">
                    </div>

                    <div class="d-grid mb-2">
                        <button class="btn btn-light fw-bold mb-3 rounded-5" onclick="signUp();">Sign Up</button>
                    </div>

                    <div class="text-center mb-2">
                        <a href="signin.php" class="text-light text-decoration-none">Already Have Account? Sign In Here...</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="script/script.js"></script>
</body>

</html>