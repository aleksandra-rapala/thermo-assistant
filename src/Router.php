<?php
class Router {
    private $routes;

    public function __construct() {
        $this->routes = [];
    }

    public function register($resource, $controller) {
        $this->routes[$resource] = $controller;
    }

    public function get($resource) {
        if (array_key_exists($resource, $this->routes)) {
            $this->routes[$resource]->get();
        } else {
            http_response_code(404);
        }
    }
}
