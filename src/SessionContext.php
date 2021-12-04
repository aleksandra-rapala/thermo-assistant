<?php
class SessionContext {
    public function init() {
        session_start();
    }

    public function setUuid($uuid) {
        $_SESSION["UUID"] = $uuid;
    }

    public function getUuid() {
        return $_SESSION["UUID"];
    }

    public function kill() {
        session_start();
        session_destroy();
        session_unset();
    }

    public function isSignedIn() {
        return $this->getUuid() !== null;
    }
}
