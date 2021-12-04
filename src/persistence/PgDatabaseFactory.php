<?php
require_once("src/persistence/DatabaseFactory.php");
require_once("src/persistence/PgDatabase.php");

class PgDatabaseFactory implements DatabaseFactory {
    public function create($host, $port, $database, $username, $password) {
        $connectionString = "pgsql:host=$host;port=$port;dbname=$database;";
        $options = ["sslmode" => "prefer"];
        $connection = $this->establishConnection($connectionString, $username, $password, $options);

        return new PgDatabase($connection);
    }

    private function establishConnection($connectionString, $username, $password, $options) {
        try {
            $connection = new PDO($connectionString, $username, $password, $options);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $connection;
        } catch (PDOException $exception) {
            die("Could not connect with the database");
        }
    }
}