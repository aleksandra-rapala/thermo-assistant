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
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $pollutions = $this->pollutionsService->findPollutionsByBuildingId($buildingId);

        $substances = [
            array("label" => "CO₂", "value" => $pollutions["co2"]),
            array("label" => "BaP", "value" => $pollutions["bap"]),
            array("label" => "Nox", "value" => $pollutions["nox"]),
            array("label" => "SO₂", "value" => $pollutions["so2"]),
            array("label" => "PM10", "value" => $pollutions["pm10"]),
            array("label" => "PM2,5", "value" => $pollutions["pm25"])
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
