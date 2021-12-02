<?php
require_once("src/controllers/Controller.php");

class BuildingController implements Controller {
    private $httpFlow;
    private $renderingEngine;
    private $sessionContext;

    public function __construct($httpFlow, $renderingEngine, $sessionContext) {
        $this->httpFlow = $httpFlow;
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
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
        // create new building or update existing one
    }
}
