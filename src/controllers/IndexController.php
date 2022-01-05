<?php
require_once("src/controllers/Controller.php");

class IndexController implements Controller {
    private $httpFlow;
    private $sessionContext;
    private $database;

    public function __construct($httpFlow, $sessionContext, $database) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
        $this->database = $database;
    }

    public function get($variables) {
        $this->sessionContext->init();

        if ($this->sessionContext->isSignedIn()) {
            $this->httpFlow->redirectTo("/building");
        } else {
            $this->httpFlow->redirectTo("/signIn");
        }
    }

    public function post($properties) {
        $this->httpFlow->methodNotSupported();
    }
}
