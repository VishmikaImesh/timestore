<?php

require_once "../app/middleware/auth.php";

class Router
{
    private array $routes = [
        "method" => [
            "GET" => [
                '/viewProduct/{id}' =>  ["controller" => 'UserPageController', "function" => "viewProduct", "param" => true],
                '/Img/{id}' =>  ["controller" => 'ImgController', "function" => "loadImg", "param" => true],
                '/poster/{id}' =>  ["controller" => 'ImgController', "function" => "loadPoster", "param" => true],

                '/Home' =>  ["controller" => 'UserPageController', "function" => "index"],
                '/profile' =>  ["controller" => 'UserPageController', "function" => "profile", "allows" => ["admin", "user"]],
                '/checkout/{id}/{id}' =>  ["controller" => 'UserPageController', "function" => "checkout", "param" => true, "allows" => ["admin", "user"]],
                '/search' =>  ["controller" => 'UserPageController', "function" => "search"],
                '/logIn' =>  ["controller" => 'UserPageController', "function" => "logIn"],
                '/admin/logIn' =>  ["controller" => 'AdminPageController', "function" => "logIn"],

                '/admin/dashboard' =>  ["controller" => 'AdminPageController', "function" => "dashboard", "allows" => ["admin"]],

            ],
            "POST" => [

                '/api/product/load' => ["controller" => 'ProductController', "function" => "loadProducts"],
                '/api/product/add' => ["controller" => 'ProductController', "function" => "addProduct", "allows" => ["admin"]],
                '/api/product/delete' => ["controller" => 'ProductController', "function" => "deleteProduct", "allows" => ["admin"]],
                '/api/product/revenue' => ["controller" => 'ProductController', "function" => "revenueData","allows" => ["admin"]],

                '/api/brand/load' => ["controller" => 'BrandController', "function" => "loadBrands", "allows" => ["admin", "user"]],
                '/api/brand/add' => ["controller" => 'BrandController', "function" => "addBrand", "allows" => ["admin"]],

                '/api/model/load' => ["controller" => 'ProductController', "function" => "loadModels"],
                '/api/model/update' => ["controller" => 'ProductController', "function" => "updateProduct", "allows" => ["admin"]],

                '/api/order/load' => ["controller" => 'OrderController', "function" => "orders"],
                '/api/order/details' => ["controller" => 'OrderController', "function" => "orderDetails", "allows" => ["admin", "user"]],
                '/api/order/new' => ["controller" => 'OrderController', "function" => "newOrder", "allows" => ["admin", "user"]],
                '/api/order/userOrders' => ["controller" => 'OrderController', "function" => "userOrders", "allows" => ["admin", "user"]],
                '/api/order/updateStatusAfterPayment' => ["controller" => 'OrderController', "function" => "updateOrderStatusAfterPayment", "allows" => ["admin", "user"]],
                '/api/order/cancel' => ["controller" => 'OrderController', "function" => "cancelOrder", "allows" => ["admin", "user"]],

                '/api/message/senders' => ["controller" => 'MessageController', "function" => "loadMsgSenders", "allows" => ["admin"]],
                '/api/message/load' => ["controller" => 'MessageController', "function" => "loadMessages", "allows" => ["admin"]],
                '/api/message/userMessages' => ["controller" => 'MessageController', "function" => "loaduserMessages", "allows" => ["admin", "user"]],
                '/api/message/changeState' => ["controller" => 'MessageController', "function" => "changeMessageState", "allows" => ["admin", "user"]],

                '/api/user/load' => ["controller" => 'UserController', "function" => "loadCustomers", "allows" => ["admin"]],
                '/api/user/details' => ["controller" => 'UserController', "function" => "loadUserDetails", "allows" => ["admin", "user"]],
                '/api/user/logIn' => ["controller" => 'UserController', "function" => "logIn"],
                '/api/user/userLogIn' => ["controller" => 'UserController', "function" => "logIn"],
                '/api/user/signUp' => ["controller" => 'UserController', "function" => "signUp"],
                '/api/user/userProfile' => ["controller" => 'UserController', "function" => "userProfile", "allows" => ["admin", "user"]],
                '/api/user/updateProfile' => ["controller" => 'UserController', "function" => "updateUserProfile", "allows" => ["admin", "user"]],
                '/api/user/updateAddress' => ["controller" => 'UserController', "function" => "updateUserAddress", "allows" => ["admin", "user"]],

                '/api/cart/add' => ["controller" => 'CartController', "function" => "addToCart", "allows" => ["admin", "user"]],
                '/api/cart/remove' => ["controller" => 'CartController', "function" => "removeFromCart", "allows" => ["admin", "user"]],

                '/api/wishlist/load' => ["controller" => 'WishlistController', "function" => "loadUserWishlist", "allows" => ["admin", "user"]],
                '/api/wishlist/toggle' => ["controller" => 'WishlistController', "function" => "toggleWishlist", "allows" => ["admin", "user"]],
                '/api/wishlist/remove' => ["controller" => 'WishlistController', "function" => "removeWishlistItem", "allows" => ["admin", "user"]],

                '/api/history/remove' => ["controller" => 'HistoryController', "function" => "removeHistoryItem", "allows" => ["admin", "user"]],

                '/api/search' => ["controller" => 'SearchController', "function" => "search"],

                '/api/delivery/load' => ["controller" => 'DeliveryMethodController', "function" => "loadDeliveryDetails", "allows" => ["admin", "user"]],
                '/api/delivery/update' => ["controller" => 'DeliveryMethodController', "function" => "updateDeliveryDetails", "allows" => ["admin"]],
                '/api/delivery/delete' => ["controller" => 'DeliveryMethodController', "function" => "deleteDeliveryDetails", "allows" => ["admin"]],
                
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
