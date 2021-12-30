<?php
require_once("src/controllers/Controller.php");

class HeaterController implements Controller {
    private $renderingEngine;
    private $sessionContext;
    private $heaterService;

    public function __construct($renderingEngine, $sessionContext, $heaterService) {
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->heaterService = $heaterService;
    }

    public function get() {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $buildingId = 1;
        $heaters = $this->heaterService->findHeatersByBuildingId($buildingId);

        $this->renderingEngine->renderView("heaters", [
            "heaters" => $heaters,
            "heaterTypes" => $this->heaterService->findAllHeaterTypes(),
            "thermalClasses" => $this->heaterService->findAllThermalClasses()
        ]);
    }

    public function post($properties) {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $heaterId = 1;
        $heaters = $this->heaterService->createByBuildingId(1, $properties);
    }
}
