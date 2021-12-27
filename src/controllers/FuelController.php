<?php
require_once("src/controllers/Controller.php");

class FuelController implements Controller {
    private $httpFlow;
    private $renderingEngine;
    private $sessionContext;
    private $fuelService;

    public function __construct($httpFlow, $renderingEngine, $sessionContext, $fuelService) {
        $this->httpFlow = $httpFlow;
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->fuelService = $fuelService;
    }

    public function get() {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $fuelsConsumption = $this->fuelService->findFuelsConsumptionByUserId($userId);

        $this->renderingEngine->renderView("fuels", [
            "fuels" => $this->fuelService->findAvailableFuels(),
            "fuelsConsumption" => $fuelsConsumption
        ]);
    }

    public function post($properties) {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $this->fuelService->updateFuelsConsumptionByUserId($userId, $properties);
    }
}