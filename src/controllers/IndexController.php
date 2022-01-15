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
        $isSignedIn = $this->sessionContext->isSignedIn();
        $this->httpFlow->redirectTo($isSignedIn? "/building" : "/signIn");
    }

    public function post($variables, $properties, $body) {
        $this->httpFlow->methodNotAllowed();
    }
}
