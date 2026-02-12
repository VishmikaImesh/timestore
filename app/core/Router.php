<?php

require_once "../app/middleware/auth.php";

class Router
{
    private array $routes = [
        "method" => [
            "GET" => [
                '/timestore/viewProduct/{id}' =>  ["controller" => 'UserPageController', "function" => "viewProduct", "param" => true, "allows" => ["admin", "user"]],
                '/timestore/Img/{id}' =>  ["controller" => 'ImgController', "function" => "loadImg", "param" => true, "allows" => ["admin", "user"]],

                '/timestore/Home' =>  ["controller" => 'UserPageController', "function" => "index", "allows" => ["admin", "user"]],
                '/timestore/profile' =>  ["controller" => 'UserPageController', "function" => "profile", "allows" => ["admin", "user"]],
                '/timestore/checkout/{id}/{id}' =>  ["controller" => 'UserPageController', "function" => "checkout", "param" => true, "allows" => ["admin", "user"]],
                '/timestore/search' =>  ["controller" => 'UserPageController', "function" => "search", "allows" => ["admin", "user"]],
                '/timestore/logIn' =>  ["controller" => 'UserPageController', "function" => "logIn"],

                '/timestore/Img/{id}' =>  ["controller" => 'ImgController', "function" => "loadImg", "param" => true, "allows" => ["admin", "user"]],

                '/timestore/dashboard' =>  ["controller" => 'AdminPageController', "function" => "dashboard", "allows" => ["admin"]],

            ],
            "POST" => [

                '/timestore/api/product/load' => ["controller" => 'ProductController', "function" => "loadProducts", "allows" => ["admin", "user"]],
                '/timestore/api/product/add' => ["controller" => 'ProductController', "function" => "addProduct", "allows" => ["admin"]],
                '/timestore/api/product/delete' => ["controller" => 'ProductController', "function" => "deleteProduct", "allows" => ["admin"]],

                '/timestore/api/brand/load' => ["controller" => 'BrandController', "function" => "loadBrands", "allows" => ["admin", "user"]],
                '/timestore/api/brand/add' => ["controller" => 'BrandController', "function" => "addBrand", "allows" => ["admin"]],

                '/timestore/api/model/load' => ["controller" => 'ProductController', "function" => "loadModels", "allows" => ["admin", "user"]],
                '/timestore/api/model/update' => ["controller" => 'ProductController', "function" => "updateProduct", "allows" => ["admin"]],

                '/timestore/api/order/load' => ["controller" => 'OrderController', "function" => "orders"],
                '/timestore/api/order/details' => ["controller" => 'OrderController', "function" => "orderDetails", "allows" => ["admin"]],
                '/timestore/api/order/new' => ["controller" => 'OrderController', "function" => "newOrder", "allows" => ["admin", "user"]],

                '/timestore/api/message/senders' => ["controller" => 'MessageController', "function" => "loadMsgSenders", "allows" => ["admin"]],
                '/timestore/api/message/load' => ["controller" => 'MessageController', "function" => "loadMessages", "allows" => ["admin"]],
                '/timestore/api/message/userMessages' => ["controller" => 'MessageController', "function" => "loaduserMessages", "allows" => ["admin", "user"]],
                '/timestore/api/message/changeState' => ["controller" => 'MessageController', "function" => "changeMessageState", "allows" => ["admin", "user"]],

                '/timestore/api/user/load' => ["controller" => 'UserController', "function" => "loadCustomers", "allows" => ["admin"]],
                '/timestore/api/user/details' => ["controller" => 'UserController', "function" => "loadUserDetails", "allows" => ["admin", "user"]],
                '/timestore/api/user/logIn' => ["controller" => 'UserController', "function" => "logIn"],
                '/timestore/api/user/userProfile' => ["controller" => 'UserController', "function" => "userProfile", "allows" => ["admin", "user"]],

                '/timestore/api/delivery/load' => ["controller" => 'DeliveryMethodController', "function" => "loadDeliveryDetails", "allows" => ["admin", "user"]],
                '/timestore/api/delivery/update' => ["controller" => 'DeliveryMethodController', "function" => "updateDeliveryDetails", "allows" => ["admin"]],
                '/timestore/api/delivery/delete' => ["controller" => 'DeliveryMethodController', "function" => "deleteDeliveryDetails", "allows" => ["admin"]],
                
            ]
        ],
        "GET_PATH" => BASE . "/app/controllers/Web/",
        "POST_PATH" => BASE . "/app/controllers/Api/"
    ];

    function __construct()
    {
        $this->reRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    function reRoute($reqMethod, $reqUri)
    {

        foreach ($this->routes["method"][$reqMethod] as $route => $details) {
            if (isset($details["param"]) && $details["param"]) {
                $pattern = $this->toRegex($route);
                if(preg_match($pattern, $reqUri, $matches)) {
                    $_GET["id"] = $matches[1];
                    $reqUri = str_replace($matches[1], "{id}", $reqUri);

                    if(isset($matches["2"])){
                        $_GET["qty"]=$matches[2];
                        $reqUri = str_replace($matches[2], "{id}", $reqUri);
                    }
                    break;
                }
            }
        }


        if (isset($this->routes["method"][$reqMethod][$reqUri])) {
            $detals = $this->routes["method"][$reqMethod][$reqUri];

            if (isset($detals["allows"])) {
                $auth = new auth();
                $auth->role($detals["allows"]);
            }

            $function = $detals["function"];

            require_once $this->routes[$_SERVER["REQUEST_METHOD"] . "_PATH"] . $detals["controller"] . ".php";

            $controller = new $detals["controller"]();
            $controller->$function();
        } else {
            require_once $this->routes["GET_PATH"] . "UserPageController.php";
            $controller = new UserPageController();
            $controller->index();
        }
    }

    function toRegex($route)
    {
        $pattern = preg_replace('#\{[^/]+\}#', '([0-9]+)', $route);
        return "#^{$pattern}$#";
    }
}
