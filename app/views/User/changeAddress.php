<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shipping Address</title>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <?php include "header.php";


    if (isset($_SESSION["u"])) {

        $email = $_SESSION["u"]["email"];

        include "connection.php";

        $address_rs = Database::search("SELECT * FROM `user_address_data` WHERE `users_email`='" . $email . "' ");
        $address_data = $address_rs->fetch_assoc();

    ?>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">

                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 p-md-5 bg-white rounded-4">
                            <h1 class="fw-bold mb-3 text-dark">Update Shipping Address</h1>
                            <p class="text-muted fs-5 mb-4 border-bottom pb-3">Ensure your delivery address is accurate for timely shipping.</p>

                            <form id="addressUpdateForm" method="POST" action="update_address_process.php">

                                <div class="mb-3">
                                    <label for="addressLine1" class="form-label fw-bold text-primary">Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg border-primary" id="addressLine1" name="addressLine1"
                                        value="<?php echo $address_data["address_line1"]  ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="addressLine2" class="form-label fw-bold text-secondary">Address Line 2 (Optional)</label>
                                    <input type="text" class="form-control form-control-lg" id="addressLine2" name="addressLine2"
                                        value="<?php echo $address_data["address_line2"] ?>">
                                </div>

                                <div class="row g-3 mb-4">

                                    <div class="col-md-6">
                                        <label for="district" class="form-label fw-bold text-secondary">District</label>
                                        <input type="text" class="form-control form-control-lg" id="district" name="district"
                                            value="<?php echo $address_data["province_en"]  ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="province" class="form-label fw-bold text-primary">Province <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg border-primary" id="province" name="province"
                                            value="<?php echo $address_data["district_en"]  ?>" required>
                                    </div>


                                    <div class="col-md-6">
                                        <label for="city" class="form-label fw-bold text-primary">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg border-primary" id="city" name="city"
                                            value="<?php echo $address_data["city_en"]  ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="postalCode" class="form-label fw-bold text-primary">Postal Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg border-primary" id="postalCode" name="postalCode"
                                            value="<?php echo $address_data["postcode"]  ?>" required>
                                    </div>
                                </div>

                                <hr class="my-4 border-secondary">

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="window.history.back()">
                                        <i class="bi bi-x-circle me-2"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-danger btn-lg">
                                        Save Address <i class="bi bi-check-circle ms-2"></i>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    ?>



    <?php include "footer.php"; ?>
    <script src="script/bootstrap.bundle.js"></script>

</body>

</html>