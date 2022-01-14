<?php
require_once("src/controllers/Controller.php");
require_once("src/exceptions/UserNotFoundException.php");
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

    public function get($variables) {
        $warnings = [];

        if (key_exists("failure", $variables)) {
            $warnings[] = "Niepoprawny e-mail lub hasło!";
        }

        $this->renderingEngine->renderView("signIn", [
            "warnings" => $warnings
        ]);
    }

    public function post($variables, $properties, $body) {
        $email = $properties["e-mail"];
        $password = $properties["password"];

        try {
            $this->processSignIn($email, $password);
        } catch (UserNotFoundException | PasswordMismatchException $exception) {
            $this->httpFlow->redirectTo("/signIn?failure");
        }
    }

    private function processSignIn($email, $password) {
        $user = $this->userService->getUser($email, $password);
        $userId = $user->getId();

        $this->sessionContext->signIn($userId);
        $this->httpFlow->redirectTo("/building");
    }
}
