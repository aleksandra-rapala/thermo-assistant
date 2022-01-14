<?php
require_once("src/controllers/Controller.php");
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

    public function get($variables) {
        $warnings = [];

        if (key_exists("user-exists", $variables)) {
            $warnings[] = "Taki użytkownik już istnieje!";
        }

        if (key_exists("weak-password", $variables)) {
            $warnings[] = "Takie hasło jest zbyt słabe!";
        }

        $this->renderingEngine->renderView("signUp", [
            "warnings" => $warnings
        ]);
    }

    public function post($variables, $properties) {
        $email = $properties["e-mail"];
        $name = $properties["name"];
        $surname = $properties["surname"];
        $password = $properties["password"];

        try {
            $this->processSignUp($name, $surname, $email, $password);
        } catch (UserAlreadyExistsException $exception) {
            $this->httpFlow->redirectTo("/signUp?user-exists");
        } catch (WeakPasswordException $exception) {
            $this->httpFlow->redirectTo("/signUp?weak-password");
        }
    }

    private function processSignUp($name, $surname, $email, $password) {
        $this->userService->addUser($name, $surname, $email, $password);
        $this->httpFlow->redirectTo("/");
    }
}
