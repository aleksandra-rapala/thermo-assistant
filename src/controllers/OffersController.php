<?php
require_once("src/controllers/Controller.php");

class OffersController implements Controller {
    private $httpFlow;
    private $offersService;

    public function __construct($httpFlow, $offersService) {
        $this->httpFlow = $httpFlow;
        $this->offersService = $offersService;
    }

    public function get($variables) {
        $this->httpFlow->methodNotAllowed();
    }

    public function post($variables, $properties, $body) {
        $community = $body["community"];
        $message = $body["message"];
        $category = $body["category"];

        $this->offersService->dispatchNotification($community, $message, $category);
    }
}
