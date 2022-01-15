<?php
class SecuredController implements Controller {
    private $httpFlow;
    private $sessionContext;
    private $database;
    private $controller;
    private $requiredRoles;

    public function __construct($httpFlow, $sessionContext, $database, $controller) {
        $this->httpFlow = $httpFlow;
        $this->sessionContext = $sessionContext;
        $this->database = $database;
        $this->controller = $controller;
        $this->requiredRoles = [];
    }

    public function get($variables) {
        $userId = $this->sessionContext->getUserId();

        if ($this->hasAllRequiredRoles($userId)) {
            $this->controller->get($variables);
        } else {
            $this->httpFlow->forbidden();
        }
    }

    private function hasAllRequiredRoles($userId) {
        $userRoles = $this->selectRolesByUserId($userId);

        foreach ($this->requiredRoles as $requiredRole) {
            if ( !in_array($requiredRole, $userRoles)) {
                return false;
            }
        }

        return true;
    }

    private function selectRolesByUserId($userId) {
        $query = "SELECT name FROM roles r JOIN users_roles ur ON r.id = ur.role_id WHERE user_id = ?;";
        $result = $this->database->executeAndFetchAll($query, $userId);
        $userRoles = [];

        foreach ($result as $row) {
            $userRoles[] = $row["name"];
        }

        return $userRoles;
    }

    public function post($variables, $properties, $body) {
        $userId = $this->sessionContext->getUserId();

        if ($this->hasAllRequiredRoles($userId)) {
            $this->controller->post($variables, $properties, $body);
        } else {
            $this->httpFlow->forbidden();
        }
    }

    public function requireRole($roleName) {
        $this->requiredRoles[] = $roleName;
    }
}