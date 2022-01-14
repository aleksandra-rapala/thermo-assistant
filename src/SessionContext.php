<?php
class SessionContext {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function signIn($userId) {
        $sessionId = hash("sha256", openssl_random_pseudo_bytes(32));
        $query = "INSERT INTO sessions (user_id, ssid) VALUES (?, ?);";
        $this->database->execute($query, $userId, $sessionId);

        setcookie("sessionId", $sessionId, time() + 86400 * 30, "/");
    }

    public function getUserId() {
        $sessionId = $_COOKIE["sessionId"];
        $query = "SELECT user_id FROM sessions WHERE ssid = ?;";
        $result = $this->database->executeAndFetchFirst($query, $sessionId);

        return $result["user_id"];
    }

    public function isSignedIn() {
        return isset($_COOKIE["sessionId"]);
    }

    public function signOut() {
        $sessionId = $_COOKIE["sessionId"];
        $query = "DELETE FROM sessions WHERE ssid = ?;";
        $this->database->execute($query, $sessionId);

        setcookie("sessionId", "", 0, "/");
    }
}
