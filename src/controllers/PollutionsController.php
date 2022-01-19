<?php
require_once("src/controllers/Controller.php");

class PollutionsController implements Controller {
    private $httpFlow;
    private $sessionContext;
    private $buildingService;
    private $pollutionsService;

    public function __construct($httpFlow, $sessionContext, $buildingService, $pollutionsService) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
        $this->buildingService = $buildingService;
        $this->pollutionsService = $pollutionsService;
    }

    public function get($variables) {
        $userId = 1;
        $building = $this->buildingService->findByUserId($userId);
        $pollutions = $this->pollutionsService->calculateForBuilding($building);

        $substances = [
            array("label" => "CO₂", "value" => 0),
            array("label" => "BaP", "value" => 0),
            array("label" => "Nox", "value" => 0),
            array("label" => "SO₂", "value" => 0),
            array("label" => "PM10", "value" => 0),
            array("label" => "PM2,5", "value" => 0)
        ];

        $summary = "Twój budynek ma umiarkowany wpływ na środowisko";

        $pollutionsResult = array(
            "substances" => $substances,
            "summary" => $summary
        );

        echo json_encode($pollutionsResult);
    }

    public function post($variables, $properties, $body) {
        $this->httpFlow->methodNotAllowed();
    }
}
