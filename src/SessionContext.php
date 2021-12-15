<?php
class SessionContext {
    public function init() {
        session_start();
    }

    public function setUserId($userId) {
        $_SESSION["UUID"] = $userId;
    }

    public function getUserId() {
        return $_SESSION["UUID"];
    }

    public function kill() {
        session_start();
        session_destroy();
        session_unset();
    }

    public function isSignedIn() {
        return $this->getUserId() !== null;
    }
}
