<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/style.css">
    <title>Profile</title>
</head>

<body class="bg-body-tertiary">
    <?php include "header.php" ?>

    <div class="container text-secondary ">

        <div class="row mt-5 m-5 d-flex justify-content-center offset-4 ">

            <div class="card col-12 col-md-6 ">
                <div class="card-body d-flex align-content-center justify-content-center  ">


                    <?php
                    include "connection.php";

                    $email = $_SESSION["u"]["email"];

                    $profile_rs = Database::search("SELECT * FROM `users` 
                    INNER JOIN `gender` ON `users`.`gender_id`=`gender`.`id` WHERE `email`='" . $email . "' ");
                    $profile_data = $profile_rs->fetch_assoc();

                    $address_rs = Database::search("SELECT * FROM `user_address`
                    INNER JOIN `cities` ON `user_address`.`cities_id`=`cities`.`city_id` 
                    INNER JOIN `districts` ON `cities`.`district_id`=`districts`.`district_id`
                    INNER JOIN `provinces` ON `districts`.`province_id`=`provinces`.`province_id`
                    WHERE `users_email`='" . $email . "'  ");

                    $address_data = $address_rs->fetch_assoc();

                    $province_rs = Database::search("SELECT * FROM `provinces` ");
                    $province_num = $province_rs->num_rows;

                    $district_rs = Database::search("SELECT * FROM `districts` ");
                    $district_num = $district_rs->num_rows;

                    $city_rs = Database::search("SELECT * FROM `cities` ORDER BY `name_en` ");
                    $city_num = $city_rs->num_rows;

                    ?>

                    <div class="col- ">
                        <div class="row">
                            <div class="col-6">
                                <img src="userprofile/photo_2024-08-23_14-50-42.jpg" class="card-img-top" id="vimg">
                                <input type="file" id="userImg" class="d-none">
                                <button class="btn btn-light rounded-4"><i><img src="icons/close.png" alt="" width="15" height="15"></i></button>
                            </div>
                            <div class="col-6">
                                <label for="" class="text-secondary">First Name</label>
                                <input type="text" class="form-control my-2" value="<?php echo ($profile_data["fname"]); ?>" id="fname">
                                <label for="" class="text-secondary">Last Name</label>
                                <input type="text" class="form-control my-2" value="<?php echo ($profile_data["lname"]); ?>" id="lname">
                            </div>
                        </div>
                        <div class="mt-3 mb-2 ">
                            <label for="" class="text-secondary">Email</label>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Recipient’s username" aria-label="Recipient’s username" aria-describedby="button-addon2" <input type="text" class="form-control mt-2 my-2" value="<?php echo ($profile_data["email"]); ?>" id="email" disabled>
                            <button class="btn btn-outline-danger" type="button" id="button-addon2">Confirm</button>
                        </div>
                        <div>
                            <label for="" class="text-secondary">Mobile</label>
                            <input type="text" class="form-control mt-2 my-2" value="<?php echo ($profile_data["mobile"]); ?>" id="mobile">
                        </div>
                        <div>
                            <label for="" class="text-secondary">Gender</label>
                            <input type="text" class="form-control mt-2 my-2" value="<?php echo ($profile_data["gender"]); ?>" disabled>
                        </div>
                        <div>
                            <label for="" class="text-secondary">Password</label>
                            <input type="text" class="form-control mt-2 my-2" value="<?php echo ($profile_data["password"]); ?>" id="pw">
                        </div>
                        <div>
                            <label for="" class="text-secondary">Address Line 1</label>
                            <input type="text" class="form-control mt-2 my-2" value="<?php echo ($address_data["address_line1"]); ?>" id="ad1">
                        </div>
                        <div>
                            <label for="" class="text-secondary">Address Line 2</label>
                            <input type="text" class="form-control mt-2 my-2" value="<?php echo ($address_data["address_line2"]); ?>" id="ad2">
                        </div>
                        <div class="row mt-3">

                            <div class="col-4">
                                <label for="" class="text-secondary">Province</label>
                                <select name="" id="" class="form-select">
                                    <?php for ($x = 0; $x < $province_num; $x++) {
                                        $province_data = $province_rs->fetch_assoc();
                                    ?>
                                        <option value="<?php echo ($province_data["province_id"]); ?>" <?php if ($province_data["province_id"] == $address_data["province_id"]) { ?> selected <?php } ?>><?php echo ($province_data["name_en"]) ?></option>
                                    <?php
                                    } ?>

                                </select>
                            </div>

                            <div class="col-4">
                                <label for="" class="text-secondary">District</label>
                                <select name="" id="" class="form-select">
                                    <?php for ($x = 0; $x < $district_num; $x++) {
                                        $district_data = $district_rs->fetch_assoc();
                                    ?>
                                        <option value="<?php echo ($district_data["district_id"]); ?>" <?php if ($district_data["district_id"] == $address_data["district_id"]) { ?> selected <?php } ?>><?php echo ($district_data["name_en"]) ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>

                            <div class="col-4">
                                <label for="" class="text-secondary">City</label>
                                <select name="" id="city" class="form-select">
                                    <?php for ($x = 0; $x < $city_num; $x++) {
                                        $city_data = $city_rs->fetch_assoc();
                                    ?>
                                        <option value="<?php echo ($city_data["city_id"]) ?>" <?php if ($city_data["city_id"] == $address_data["city_id"]) { ?> selected <?php } ?>><?php echo ($city_data["name_en"]) ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>

                        </div>
                    </div>




                </div>



                <div class="d-grid mx-3 mb-3">
                    <button class="btn btn-secondary fw-bold rounded-4" onclick="updateProfile();">Update Profile</button>
                </div>

            </div>

        </div>
    </div>


    <?php include "footer.php" ?>

</body>

</html>