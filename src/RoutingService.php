<?php
class RoutingService {
    private $routes;
    private $httpFlow;

    public function __construct($httpFlow) {
        $this->routes = [];
        $this->httpFlow = $httpFlow;
    }

    public function register($resource, $controller) {
        $this->routes[$resource] = $controller;
    }

    public function get($resource, $variables) {
        if (array_key_exists($resource, $this->routes)) {
            $this->routes[$resource]->get($variables);
        } else {
            $this->httpFlow->notFound();
        }
    }

    public function post($resource, $variables, $properties) {
        if (array_key_exists($resource, $this->routes)) {
            $this->routes[$resource]->post($variables, $properties);
        } else {
            $this->httpFlow->notFound();
        }
    }
}
