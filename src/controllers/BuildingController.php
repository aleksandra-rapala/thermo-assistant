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

    public function get() {
        $this->sessionContext->init();

        if ($this->sessionContext->isSignedIn()) {
            $uuid = $this->sessionContext->getUuid();

            $this->renderingEngine->renderView("building", ["uuid" => $uuid]);
        } else {
            $this->httpFlow->redirectTo("/signIn");
        }
    }

    public function post($properties) {
        $uuid = $this->sessionContext->getUuid();
        $building_exists = $this->buildingService->existsByUserId($uuid);

        if ($building_exists) {
            $this->buildingService->update($uuid, $properties);
        } else {
            $this->buildingService->create($uuid, $properties);
        }
    }
}
