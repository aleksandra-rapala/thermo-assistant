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

    public function findSubscribersByCommunityAndSubscriptionName($community, $subscriptionName) {
        $query = "
            SELECT
                e_mail
            FROM
                 users u
            JOIN
                buildings b ON u.id = b.user_id
            JOIN
                addresses a ON a.id = b.address_id
            JOIN
                buildings_subscriptions bs ON b.id = bs.building_id
            JOIN
                subscriptions s ON bs.subscription_id = s.id
            WHERE
                a.community = ? AND s.name = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $community, $subscriptionName);

        return $this->mapToEmails($result);
    }

    private function mapToEmails($result) {
        $emails = [];

        foreach ($result as $row) {
            $emails[] = $row["e_mail"];
        }

        return $emails;
    }
}
