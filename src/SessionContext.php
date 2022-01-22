<?php
class SessionContext {
    private $database;
    private $httpFlow;

    public function __construct($database, $httpFlow) {
        $this->database = $database;
        $this->httpFlow = $httpFlow;
    }

    public function signIn($userId) {
        $sessionId = $this->generateRandomSessionId();

        $this->createSession($userId, $sessionId);
        $this->createCookie($sessionId);
    }

    private function generateRandomSessionId() {
        return hash("sha256", openssl_random_pseudo_bytes(32));
    }

    private function createSession($userId, $sessionId) {
        $query = "INSERT INTO sessions (user_id, ssid) VALUES (?, ?);";
        $this->database->execute($query, $userId, $sessionId);
    }

    private function createCookie($sessionId) {
        setcookie("sessionId", $sessionId, time() + 86400 * 30, "/");
    }

    public function getUserId() {
        $userId = null;

        if (isset($_COOKIE["sessionId"])) {
            $sessionId = $_COOKIE["sessionId"];
            $userId = $this->selectUserIdBySessionId($sessionId);
        }

        if ($userId === null) {
            $this->httpFlow->unauthorized();
        }

        return $userId;
    }

    public function isSignedIn() {
        $userId = null;

        if (isset($_COOKIE["sessionId"])) {
            $sessionId = $_COOKIE["sessionId"];
            $userId = $this->selectUserIdBySessionId($sessionId);
        }

        return $userId !== null;
    }

    private function selectUserIdBySessionId($sessionId) {
        $query = "SELECT user_id FROM sessions WHERE ssid = ?;";
        $result = $this->database->executeAndFetchFirst($query, $sessionId);

        return $result["user_id"];
    }

    public function signOut() {
        $sessionId = $_COOKIE["sessionId"];

        $this->removeSession($sessionId);
        $this->removeCookie();
    }

    private function removeSession($sessionId) {
        $query = "DELETE FROM sessions WHERE ssid = ?;";
        $this->database->execute($query, $sessionId);
    }

    private function removeCookie() {
        setcookie("sessionId", "", 0, "/");
    }
}
