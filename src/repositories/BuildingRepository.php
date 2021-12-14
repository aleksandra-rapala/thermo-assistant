<?php
class BuildingRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function existsByUserId($userId) {
        $query = "SELECT COUNT(*) AS count FROM buildings WHERE user_id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $userId);

        return $result["count"] !== 0;
    }

    public function insert($building) {
        var_dump($building);
    }
}
