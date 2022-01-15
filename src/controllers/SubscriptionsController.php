<?php
require_once("src/controllers/Controller.php");

class SubscriptionsController implements Controller {
    private $sessionContext;
    private $buildingService;

    public function __construct($sessionContext, $buildingService) {
        $this->sessionContext = $sessionContext;
        $this->buildingService = $buildingService;
    }

    public function get($variables) {
        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $subscriptions = $this->buildingService->findSubscriptionsByBuildingId($buildingId);

        echo json_encode($subscriptions);
    }

    public function post($variables, $properties, $body) {
        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $subscriptionName = $variables["name"];
        $subscriptionStatus = boolval($variables["active"]);

        if ($subscriptionStatus) {
            $this->buildingService->subscribe($buildingId, $subscriptionName);
        } else {
            $this->buildingService->unsubscribe($buildingId, $subscriptionName);
        }
    }
}
