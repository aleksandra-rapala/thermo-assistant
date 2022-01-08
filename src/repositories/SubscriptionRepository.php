<?php
class SubscriptionRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectAllNamesByBuildingId($buildingId) {
        $query = "
            SELECT
                name
            FROM
                subscriptions s
            JOIN
                buildings_subscriptions bs ON bs.subscription_id = s.id
            WHERE
                building_id = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $buildingId);

        return $this->mapToNames($result);
    }

    private function mapToNames($result) {
        $names = [];

        foreach ($result as $row) {
            $names[] = $row["name"];
        }

        return $names;
    }

    public function subscribe($buildingId, $subscriptionName) {
        $query = "
            INSERT INTO buildings_subscriptions
                (building_id, subscription_id)
            VALUES
                (?, (SELECT id FROM subscriptions WHERE name = ?));
        ";

        $this->database->execute($query, $buildingId, $subscriptionName);
    }

    public function unsubscribe($buildingId, $subscriptionName) {
        $query = "
            DELETE FROM
                buildings_subscriptions
            WHERE
                building_id = ?
            AND
                subscription_id = (SELECT id FROM subscriptions WHERE name = ?);
        ";

       $this->database->execute($query, $buildingId, $subscriptionName);
    }
}
