<?php
require_once("Controller.php");

class LoginController implements Controller {
    private $httpFlow;
    private $sessionContext;

    public function __construct($httpFlow, $sessionContext) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
    }

    public function get() {
        $this->sessionContext->init();
        $uuid = $this->sessionContext->getUuid();

        echo $uuid !== null;
    }

    public function post($properties) {
        $password = $properties["password"];

        if (strlen($password) < 8) {
            $this->httpFlow->badRequest();
        } else {
            $this->sessionContext->init();
            $this->sessionContext->setUuid("123");

            $this->httpFlow->redirectTo("/building");
        }
    }
}
