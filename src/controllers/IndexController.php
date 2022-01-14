<?php
require_once("src/controllers/Controller.php");

class IndexController implements Controller {
    private $httpFlow;
    private $sessionContext;

    public function __construct($httpFlow, $sessionContext) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
    }

    public function get($variables) {
        if ($this->sessionContext->isSignedIn()) {
            $this->httpFlow->redirectTo("/building");
        } else {
            $this->httpFlow->redirectTo("/signIn");
        }
    }

    public function post($variables, $properties) {
        $this->httpFlow->methodNotAllowed();
    }
}
