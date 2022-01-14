<?php
require_once("src/controllers/Controller.php");

class BuildingController implements Controller {
    private $httpFlow;
    private $renderingEngine;
    private $sessionContext;
    private $buildingService;

    public function __construct($httpFlow, $renderingEngine, $sessionContext, $buildingService) {
        $this->httpFlow = $httpFlow;
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->buildingService = $buildingService;
    }

    public function get($variables) {
        $userId = $this->sessionContext->getUserId();
        $building = $this->buildingService->findByUserId($userId);

        $this->renderingEngine->renderView("building", [
            "building" => $building,
            "details" => $building->getDetails(),
            "address" => $building->getAddress(),
            "modernizations" => $this->buildingService->findAvailableModernizations(),
            "usageOptions" => $this->buildingService->findAvailableUsageOptions(),
            "destinationOptions" => $this->buildingService->findAvailableDestinationOptions(),
            "heaterTypes" => $this->buildingService->findAvailableHeaterTypes()
        ]);
    }

    public function post($variables, $properties) {
        $userId = $this->sessionContext->getUserId();
        $buildingExists = $this->buildingService->existsByUserId($userId);

        if ($buildingExists) {
            $this->buildingService->update($userId, $properties);
        } else {
            $this->buildingService->create($userId, $properties);
        }

        $this->httpFlow->redirectTo("/fuels");
    }
}
