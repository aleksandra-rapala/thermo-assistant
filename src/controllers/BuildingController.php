<?php
require_once("Controller.php");

class BuildingController implements Controller {
    private $renderingEngine;
    private $sessionContext;

    public function __construct($renderingEngine, $sessionContext) {
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
    }

    public function get() {
        $this->sessionContext->init();
        $uuid = $this->sessionContext->getUuid();

        $this->renderingEngine->renderView("building", ["uuid" => $uuid]);
    }

    public function post($properties) {
        // create new building or update existing one
    }
}
