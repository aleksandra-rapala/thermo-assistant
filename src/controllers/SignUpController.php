<?php
require_once("Controller.php");
require_once("src/exceptions/UserAlreadyExistsException.php");
require_once("src/exceptions/WeakPasswordException.php");

class SignUpController implements Controller {
    private $httpFlow;
    private $sessionContext;
    private $renderingEngine;
    private $userService;

    public function __construct($httpFlow, $renderingEngine, $sessionContext, $userService) {
        $this->httpFlow = $httpFlow;
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->userService = $userService;
    }

    public function get() {
        $this->renderingEngine->renderView("signUp");
    }

    public function post($properties) {
        $email = $properties["e-mail"];
        $name = $properties["name"];
        $surname = $properties["surname"];
        $password = $properties["password"];

        try {
            $this->processSignUp($name, $surname, $email, $password);
        } catch (UserAlreadyExistsException $exception) {
            $this->httpFlow->badRequest();
        } catch (WeakPasswordException $exception) {
            $this->httpFlow->badRequest();
        }
    }

    private function processSignUp($name, $surname, $email, $password) {
        $uuid = $this->userService->addUser($name, $surname, $email, $password);

        $this->sessionContext->init();
        $this->sessionContext->setUuid($uuid);

        $this->httpFlow->redirectTo("/building");
    }
}