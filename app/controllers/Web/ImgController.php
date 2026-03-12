<?php
require_once(BASE . "/config/connection.php");

class ImgController
{
    public function loadImg()
    {
        $model_id = $_GET["id"];

        $img_rs = Database::search("SELECT `img_path` FROM `product_img` WHERE `model_id`='" . $model_id . "' ");
        $img_data = $img_rs->fetch_assoc();
        $path =  $img_data["img_path"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);

        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($path));

        readfile($path);
        exit;
    }

    public function loadPoster()
    {
        $poster_id = $_GET["id"];

        $path = BASE . "/app/media/poster/$poster_id.avif";

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);

        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($path));

        readfile($path);
        exit;
    }

    public function loadUserImg()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $email = $_SESSION["u"]["email"] ?? null;
        if (!$email) {
            http_response_code(403);
            exit;
        }

        $img_rs = Database::search("SELECT `path` FROM `user_img` WHERE `email`='" . $email . "' ");
        $img_data = $img_rs->fetch_assoc();
        $img_path = $img_data["path"] ?? null;

        $base_media = BASE . "/app/media/";
        $default_path = $base_media . "icons/profile.png";
        $path = $default_path;

        if ($img_path) {
            $candidate = $base_media . ltrim($img_path, "/\\");
            if (file_exists($candidate)) {
                $path = $candidate;
            }
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);

        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($path));

        readfile($path);
        exit;
    }
}
