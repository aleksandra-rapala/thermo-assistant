<?php
require_once("src/HttpFlow.php");
require_once("src/Router.php");
require_once("src/RenderingEngine.php");
require_once("src/SessionContext.php");
require_once("src/repositories/UserRepository.php");
require_once("src/services/UserService.php");
require_once("src/controllers/IndexController.php");
require_once("src/controllers/SignInController.php");
require_once("src/controllers/SignUpController.php");
require_once("src/controllers/LogoutController.php");
require_once("src/controllers/BuildingController.php");

$httpFlow = new HttpFlow();
$router = new Router($httpFlow);
$sessionContext = new SessionContext();
$renderingEngine = new RenderingEngine();
$userRepository = new UserRepository();
$userService = new UserService($userRepository);
$indexController = new IndexController($httpFlow, $sessionContext);
$signInController = new SignInController($httpFlow, $renderingEngine, $sessionContext, $userService);
$signUpController = new SignUpController($httpFlow, $renderingEngine, $sessionContext, $userService);
$logoutController = new LogoutController($httpFlow, $sessionContext);
$buildingController = new BuildingController($httpFlow, $renderingEngine, $sessionContext);

$router->register("", $indexController);
$router->register("signIn", $signInController);
$router->register("signUp", $signUpController);
$router->register("logout", $logoutController);
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
