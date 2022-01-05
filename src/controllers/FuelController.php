<?php
require_once("src/controllers/Controller.php");

class FuelController implements Controller {
    private $renderingEngine;
    private $sessionContext;
    private $buildingService;
    private $fuelService;
    private $httpFlow;

    public function __construct($renderingEngine, $sessionContext, $buildingService, $fuelService, $httpFlow) {
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->buildingService = $buildingService;
        $this->fuelService = $fuelService;
        $this->httpFlow = $httpFlow;
    }

    public function get($variables) {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);

        $this->renderingEngine->renderView("fuels", [
            "fuels" => $this->fuelService->findAvailableFuels(),
            "fuelsConsumption" => $this->fuelService->findFuelsConsumptionByBuildingId($buildingId),
            "distributors" => $this->fuelService->findDistributorsByBuildingId($buildingId)
        ]);
    }

    public function post($properties) {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $this->fuelService->updateFuelsConsumptionByBuildingId($buildingId, $properties);

        $this->httpFlow->redirectTo("/heaters");
    }
}