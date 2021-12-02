<?php
require_once("src/controllers/Controller.php");

class IndexController implements Controller {
    private $httpFlow;
    private $sessionContext;

    public function __construct($httpFlow, $sessionContext) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
    }

    public function get() {
        $this->sessionContext->init();

        if ($this->sessionContext->isSignedIn()) {
            $this->httpFlow->redirectTo("/building");
        } else {
            $this->httpFlow->redirectTo("/signIn");
        }
    }

    public function post($properties) {
        $this->httpFlow->methodNotAllowed();
    }
}
