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
            "thermalClasses" => $this->heaterService->findAllThermalClasses(),
            "dataSourceSuggestions" => ["Tabliczka znamionowa", "Dokumentacja techniczna", "Wiedza właściciela"]
        ]);
    }

    public function post($variables, $properties, $body) {
        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);

        if (empty($properties)) {
            $this->heaterService->createByBuildingId($buildingId);
        } else {
            $this->heaterService->updateHeatersByBuildingId($buildingId, $properties);
            $this->httpFlow->redirectTo("/summary");
        }
    }
}
