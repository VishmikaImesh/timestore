<?php
require_once(BASE . "/config/connection.php");
class Img
{

    public function loadImg()
    {
        echo ("Model ID: " . $_GET["model_id"]);
        $model_id = $_GET["model_id"];

        $img_rs = Database::search("SELECT `img_path` FROM `product_img` WHERE `model_id`='" . $model_id . "' ");
        $img_data = $img_rs->fetch_assoc();
        $path = BASE . "\app\media\\" . $img_data["img_path"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);

        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($path));

        readfile($path);
        exit;
    }
}
