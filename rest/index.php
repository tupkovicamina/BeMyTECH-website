<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once '../vendor/autoload.php';
require_once "services/UserService.php";
require_once "services/ProductService.php";
require_once "services/CategoryService.php";
require_once "services/OrderService.php";
require_once "services/SupplierService.php";
require_once "services/AddressService.php";
require_once "services/CartService.php";
require_once "services/FormService.php";

Flight::register('user_service', "UserService");
Flight::register('product_service', "ProductService");
Flight::register('category_service', "CategoryService");
Flight::register('supplier_service', "SupplierService");
Flight::register('order_service', "OrderService");
Flight::register('address_service', "AddressService");
Flight::register('cart_service', "CartService");
Flight::register('form_service', "FormService");
Flight::register('order_product_service', "OrderProductService");

//MIDDLEWARE
Flight::route('/*', function(){
    //perform JWT decode
    $path = Flight::request()->url;
  
    if ($path == '/login' || $path == '/signup' || $path == '/docs.json') return TRUE; // exclude login route from middleware
   
    $headers = getallheaders();
    if (!isset($headers['Authorization'])){
        Flight::json(["message" => "Authorization is missing"], 403);
        return FALSE;
    }else{
        try {
        $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
        Flight::set('user', $decoded);
        return TRUE;
        } catch (\Exception $e) {
        Flight::json(["message" => "Authorization token is not valid"], 403);
        return FALSE;
        }
    }
});

require_once 'routes/UserRoutes.php';
require_once 'routes/ProductRoutes.php';
require_once 'routes/CategoryRoutes.php';
require_once 'routes/SupplierRoutes.php';
require_once 'routes/OrderRoutes.php';
require_once 'routes/AddressRoutes.php';
require_once 'routes/CartRoutes.php';
require_once 'routes/FormRoutes.php';

Flight::map('query', function($name, $default_value = null) {
    $request = Flight::request();
    $query_params = @$request->query->getData()[$name];
    $query_params = $query_params ? $query_params : $default_value;
    return $query_params;
});

Flight::route('GET /docs.json', function(){
    $openapi = \OpenApi\scan('routes');
    header('Content-Type: application/json');
    echo $openapi->toJson();
});


Flight::start();
?>