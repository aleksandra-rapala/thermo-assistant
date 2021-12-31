<?php
require_once("config.php");
require_once("src/HttpFlow.php");
require_once("src/RoutingService.php");
require_once("src/RenderingEngine.php");
require_once("src/SessionContext.php");
require_once("src/repositories/UserRepository.php");
require_once("src/repositories/BuildingRepository.php");
require_once("src/repositories/ModernizationRepository.php");
require_once("src/repositories/HeaterRepository.php");
require_once("src/repositories/FuelRepository.php");
require_once("src/repositories/AddressRepository.php");
require_once("src/services/UserService.php");
require_once("src/services/BuildingService.php");
require_once("src/services/FuelService.php");
require_once("src/services/HeaterService.php");
require_once("src/controllers/IndexController.php");
require_once("src/controllers/SignInController.php");
require_once("src/controllers/SignUpController.php");
require_once("src/controllers/LogoutController.php");
require_once("src/controllers/BuildingController.php");
require_once("src/controllers/FuelController.php");
require_once("src/controllers/HeaterController.php");
require_once("src/persistence/PgDatabaseFactory.php");

$httpFlow = new HttpFlow();
$routingService = new RoutingService($httpFlow);
$sessionContext = new SessionContext();
$renderingEngine = new RenderingEngine();
$databaseFactory = new PgDatabaseFactory();
$database = $databaseFactory->create(HOST, PORT, DATABASE, USERNAME, PASSWORD);
$userRepository = new UserRepository($database);
$userService = new UserService($userRepository);
$indexController = new IndexController($httpFlow, $sessionContext, $database);
$signInController = new SignInController($httpFlow, $renderingEngine, $sessionContext, $userService);
$signUpController = new SignUpController($httpFlow, $renderingEngine, $sessionContext, $userService);
$logoutController = new LogoutController($httpFlow, $sessionContext);
$modernizationRepository = new ModernizationRepository($database);
$heaterRepository = new HeaterRepository($database);
$addressRepository = new AddressRepository($database);
$buildingRepository = new BuildingRepository($database, $modernizationRepository, $heaterRepository, $addressRepository);
$buildingService = new BuildingService($buildingRepository, $modernizationRepository, $heaterRepository);
$buildingController = new BuildingController($httpFlow, $renderingEngine, $sessionContext, $buildingService);
$fuelRepository = new FuelRepository($database, $addressRepository);
$fuelService = new FuelService($fuelRepository, $addressRepository);
$fuelController = new FuelController($renderingEngine, $sessionContext, $buildingService, $fuelService);
$heaterService = new HeaterService($heaterRepository);
$heaterController = new HeaterController($renderingEngine, $sessionContext, $buildingService, $heaterService);

$routingService->register("", $indexController);
$routingService->register("signIn", $signInController);
$routingService->register("signUp", $signUpController);
$routingService->register("logout", $logoutController);
$routingService->register("building", $buildingController);
$routingService->register("fuels", $fuelController);
$routingService->register("heaters", $heaterController);

$resource = $_SERVER["REQUEST_URI"];
$resource = trim($resource, "/");
$resource = parse_url($resource, PHP_URL_PATH);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $routingService->get($resource);
        break;
    case "POST":
        $routingService->post($resource, $_POST);
        break;
    default:
        $httpFlow->methodNotAllowed();
}
