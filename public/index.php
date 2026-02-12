<?php
define("BASE", dirname(__DIR__));

// require_once(BASE."/app/controllers/Web/ImgController.php");
// $_GET["id"] = 13; // Example model_id for testing
// $imgController = new ImgController();
// $imgController->loadImg();


require_once '../app/core/Router.php';
$router = new Router();
