<?php
require_once("src/HttpFlow.php");
require_once("src/Router.php");
require_once("src/RenderingEngine.php");
require_once("src/SessionContext.php");
require_once("src/controllers/IndexController.php");
require_once("src/controllers/LoginController.php");
require_once("src/controllers/BuildingController.php");

$httpFlow = new HttpFlow();
$router = new Router($httpFlow);
$sessionContext = new SessionContext();
$renderingEngine = new RenderingEngine();
$indexController = new IndexController($httpFlow, $renderingEngine);
$loginController = new LoginController($httpFlow, $sessionContext);
$buildingController = new BuildingController($renderingEngine, $sessionContext);

$router->register("", $indexController);
$router->register("login", $loginController);
$router->register("building", $buildingController);

$resource = $_SERVER["REQUEST_URI"];
$resource = trim($resource, "/");
$resource = parse_url($resource, PHP_URL_PATH);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $router->get($resource);
        break;
    case "POST":
        $router->post($resource, $_POST);
        break;
    default:
        $httpFlow->methodNotAllowed();
}
