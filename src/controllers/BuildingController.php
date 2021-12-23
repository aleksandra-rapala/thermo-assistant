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
            $userId = $this->sessionContext->getUserId();
            $building = $this->buildingService->getByUserId($userId);

            $this->renderingEngine->renderView("building", [
                "userId" => $userId,
                "building" => $building,
                "address" => $building->getAddress()
            ]);
        } else {
            $this->httpFlow->redirectTo("/signIn");
        }
    }

    public function post($properties) {
        $this->sessionContext->init();

        $userId = $this->sessionContext->getUserId();
        $building_exists = $this->buildingService->existsByUserId($userId);

        if ($building_exists) {
            $this->buildingService->update($userId, $properties);
        } else {
            $this->buildingService->create($userId, $properties);
        }
    }
}
