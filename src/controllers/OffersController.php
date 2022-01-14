<?php
require_once("src/controllers/Controller.php");

class OffersController implements Controller {
    private $sessionContext;
    private $buildingService;

    public function __construct($sessionContext, $buildingService) {
        $this->sessionContext = $sessionContext;
        $this->buildingService = $buildingService;
    }

    public function get($variables) {
        $this->sessionContext->ensureAuthorized();

        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $subscriptions = $this->buildingService->findSubscriptionsByBuildingId($buildingId);

        echo json_encode($subscriptions);
    }

    public function post($variables, $properties) {
        $this->sessionContext->ensureAuthorized();

        $userId = $this->sessionContext->getUserId();
        $buildingId = $this->buildingService->findBuildingIdByUserId($userId);
        $subscriptionName = $variables["subscription-name"];
        $subscriptionStatus = boolval($variables["active"]);

        if ($subscriptionStatus) {
            $this->buildingService->subscribe($buildingId, $subscriptionName);
        } else {
            $this->buildingService->unsubscribe($buildingId, $subscriptionName);
        }
    }
}
