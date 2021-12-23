<?php
require_once("src/persistence/Database.php");

class PgDatabase implements Database {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function execute($query, ...$parameters) {
        $statement = $this->connection->prepare($query);
        $statement->execute($parameters);
        $statement->closeCursor();
    }

    public function executeAndFetchFirst($query, ...$parameters) {
        $statement = $this->connection->prepare($query);
        $statement->execute($parameters);
        $result = $statement->fetch();
        $statement->closeCursor();

        return $result;
    }

    public function executeAndFetchAll($query, ...$parameters) {
        $statement = $this->connection->prepare($query);
        $statement->execute($parameters);
        $result = $statement->fetchAll();
        $statement->closeCursor();

        return $result;
    }

    /**
     * @throws Exception
     */
    public function withinTransaction($callback) {
        $this->connection->beginTransaction();

        try
        {
            $callback();
        }
        catch (Exception $exception)
        {
            $this->connection->rollBack();

            throw $exception;
        }

        $this->connection->commit();
    }
}