<?php
require_once("config.php");
require_once("src/persistence/Database.php");

class PgDatabase implements Database {
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;
    private $connection;

    public function __construct() {
        $this->host = HOST;
        $this->port = PORT;
        $this->database = DATABASE;
        $this->username = USERNAME;
        $this->password = PASSWORD;
    }

    public function connect() {
        try {
            $connection = new PDO(
                "pgsql:host=$this->host;port=$this->port;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode"  => "prefer"]
            );

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection = $connection;
        } catch (PDOException $exception) {
            die("Connection failed: " . $exception->getMessage());
        }
    }

    public function execute($query, ...$parameters) {
        $parametersCount = count($parameters);
        $statement = $this->connection->prepare($query);

        for ($i = 0; $i < $parametersCount; $i++) {
            $statement->bindParam($i + 1, $parameters[$i]);
        }

        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();

        return $result;
    }
}