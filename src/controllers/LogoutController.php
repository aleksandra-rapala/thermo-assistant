<?php
require_once("src/controllers/Controller.php");

class LogoutController implements Controller {
    private $httpFlow;
    private $sessionContext;

    public function __construct($httpFlow, $sessionContext) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
    }

    public function get($variables) {
        $this->sessionContext->signOut();
        $this->httpFlow->redirectTo("/");
    }

    public function post($variables, $properties, $body) {
        $this->sessionContext->signOut();
        $this->httpFlow->redirectTo("/");
    }
}
