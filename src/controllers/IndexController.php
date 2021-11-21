<?php
require_once("Controller.php");

class IndexController implements Controller {
    private $httpFlow;
    private $renderingEngine;

    public function __construct($httpFlow, $renderingEngine) {
        $this->httpFlow = $httpFlow;
        $this->renderingEngine = $renderingEngine;
    }

    public function get() {
        $this->renderingEngine->renderView("login");
    }

    public function post($properties) {
        $this->httpFlow->methodNotAllowed();
    }
}
