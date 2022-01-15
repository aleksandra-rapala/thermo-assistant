<?php
require_once("src/controllers/Controller.php");

class HeaterController implements Controller {
    private $renderingEngine;
    private $sessionContext;
    private $buildingService;
    private $heaterService;
    private $httpFlow;

    public function __construct($renderingEngine, $sessionContext, $buildingService, $heaterService, $httpFlow) {
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->buildingService = $buildingService;
        $this->heaterService = $heaterService;
        $this->httpFlow = $httpFlow;
    }

    public function get($variables) {
        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);

        $this->renderingEngine->renderView("heaters", [
            "heaters" => $this->heaterService->findHeatersByBuildingId($buildingId),
            "heaterTypes" => $this->heaterService->findAllHeaterTypes(),
            "thermalClasses" => $this->heaterService->findAllThermalClasses()
        ]);
    }

    public function post($variables, $properties, $body) {
        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $heaterId = $properties["heater-id"];

        if ($this->heaterService->existsByHeaterIdAndBuildingId($heaterId, $buildingId)) {
            $this->heaterService->updateHeaterById($heaterId, $properties);
            $this->httpFlow->redirectTo("/summary");
        } else {
            $this->heaterService->createByBuildingId($buildingId);
        }
    }
}
