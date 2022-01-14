<?php
require_once("src/controllers/OffersController.php");

class OffersController implements Controller {
    private $httpFlow;
    private $sessionContext;
    private $offersService;

    public function __construct($httpFlow, $sessionContext, $offersService) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
        $this->offersService = $offersService;
    }

    public function get($variables) {
        $this->httpFlow->methodNotAllowed();
    }

    public function post($variables, $properties, $body) {
        $community = $body["community"];
        $description = $body["description"];
        $category = $body["category"];

        $this->offersService->dispatchNotification($community, $description, $category);
    }
}
