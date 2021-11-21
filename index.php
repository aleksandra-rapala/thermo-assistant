<?php
require_once "src/Router.php";
require_once "src/controllers/IndexController.php";
require_once "src/controllers/LoginController.php";

$router = new Router();
$indexController = new IndexController();
$loginController = new LoginController();

$router->register("", $indexController);
$router->register("login", $loginController);

$resource = $_SERVER["REQUEST_URI"];
$resource = trim($resource, "/");
$resource = parse_url($resource, PHP_URL_PATH);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $router->get($resource);
        break;
    default:
        http_response_code(405);
}
