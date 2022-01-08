<?php
require_once("src/controllers/Controller.php");

class OffersController implements Controller {
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
        echo json_encode($this->buildingService->findSubscriptionsByBuildingId(3));
    }

    public function post($variables, $properties) {
        $subscriptionName = $variables["subscription-name"];
        $subscriptionStatus = $variables["active"];

        if ($subscriptionStatus) {
            $this->buildingService->subscribe(3, $subscriptionName);
        } else {
            $this->buildingService->unsubscribe(3, $subscriptionName);
        }
    }
}
