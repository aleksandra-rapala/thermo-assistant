<?php
require_once("src/controllers/Controller.php");
require_once("src/exceptions/PasswordMismatchException.php");

class SignInController implements Controller {
    private $httpFlow;
    private $renderingEngine;
    private $sessionContext;
    private $userService;

    public function __construct($httpFlow, $renderingEngine, $sessionContext, $userService) {
        $this->httpFlow = $httpFlow;
        $this->renderingEngine = $renderingEngine;
        $this->sessionContext = $sessionContext;
        $this->userService = $userService;
    }

    public function get() {
        $this->renderingEngine->renderView("signIn");
    }

    public function post($properties) {
        $email = $properties["e-mail"];
        $password = $properties["password"];

        try {
            $this->processSignIn($email, $password);
        } catch (PasswordMismatchException $exception) {
            $this->httpFlow->badRequest();
        }
    }

    private function processSignIn($email, $password) {
        $user = $this->userService->getUser($email, $password);
        $uuid = $user->getUuid();

        $this->sessionContext->init();
        $this->sessionContext->setUuid($uuid);

        $this->httpFlow->redirectTo("/building");
    }
}
