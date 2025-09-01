<?php

include "connection.php";

if (isset($_POST["g"])) {
    $g = $_POST["g"];
}

if (isset($_POST["mt"])) {
    $mt = $_POST["mt"];
}

if (isset($_POST["type"])) {
    $type = $_POST["type"];
}

if (isset($_POST["search"])) {
    $search = $_POST["search"];
}


$search_rs = Database::search("SELECT * FROM `product` WHERE `gender_id`='" . $g . "' ");
$search_no = $search_rs->num_rows;

for ($i = 0; $i < $search_no; $i++) {
    $search_data = $search_rs->fetch_assoc();

    $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $search_data["id"] . "' ");
    $img_data=$img_rs->fetch_assoc();
   
?>
    <div class="col">
        <a href="viewProduct.php?id=<?php echo ($search_data["id"]); ?>" class="card h-100 text-decoration-none">
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


