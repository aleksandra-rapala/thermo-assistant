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

    public function get() {
        $this->sessionContext->init();

        if ($this->sessionContext->isSignedIn()) {
            $this->httpFlow->redirectTo("/building");
        } else {
            $this->httpFlow->redirectTo("/signIn");
        }
    }

    public function post($properties) {
        // temporary functionality
        // installing an application
        // setting up database tables
        $this->database->execute("CREATE TABLE IF NOT EXISTS users (uuid SERIAL PRIMARY KEY, name VARCHAR(64), surname VARCHAR(64), e_mail VARCHAR(128), password VARCHAR(80));");
        $this->database->execute("CREATE TABLE IF NOT EXISTS buildings (id SERIAL PRIMARY KEY, user_id INT NOT NULL UNIQUE);");
    }
}
