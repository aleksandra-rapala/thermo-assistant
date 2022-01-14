<?php
require_once("src/controllers/Controller.php");

class SummaryController implements Controller {
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
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);

        $this->renderingEngine->renderView("summary", [
            "ceeb" => true
        ]);
    }

    public function post($variables, $properties) {
        $this->httpFlow->methodNotAllowed();
    }
}
