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

    public function insert($userId, $building) {
        $address = $building->getAddress();
        $addressId = $this->insertAddress($address);

        $this->insertDetails($building, $userId, $addressId);
    }

    private function insertAddress($address) {
        $query = "
            INSERT INTO addresses
                (country, district, community, location, street, building_no, apartment_no)
            VALUES
                (?, ?, ?, ?, ?, ?, ?)
            RETURNING id;
        ";

        $result = $this->database->executeAndFetchFirst($query,
            $address->getCountry(),
            $address->getDistrict(),
            $address->getCommunity(),
            $address->getLocation(),
            $address->getStreet(),
            $address->getBuildingNo(),
            $address->getApartmentNo(),
        );

        return $result["id"];
    }

    private function insertDetails($building, $userId, $addressId) {
        $query = "
            INSERT INTO buildings
                (user_id, address_id, area, storeys, housemates, water_usage, energy_usage, destination)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?);
            ";

        $this->database->execute($query,
            $userId,
            $addressId,
            $building->getArea(),
            $building->getStoreys(),
            $building->getHousemates(),
            $building->getWaterUsage(),
            $building->getEnergyUsage(),
            $building->getDestination()
        );
    }

    public function update($building) {
        $address = $building->getAddress();

        $this->updateAddress($address);
        $this->updateDetails($building);
    }

    private function updateAddress($address) {
        $query = "
            UPDATE addresses SET
                country=?, district=?, community=?, location=?, street=?, building_no=?, apartment_no=?
            WHERE
                id=?;
        ";

        $this->database->execute($query,
            $address->getCountry(),
            $address->getDistrict(),
            $address->getCommunity(),
            $address->getLocation(),
            $address->getStreet(),
            $address->getBuildingNo(),
            $address->getApartmentNo(),
            $address->getId()
        );
    }

    private function updateDetails($building) {
        $query = "
            UPDATE buildings SET
                area=?, storeys=?, housemates=?, water_usage=?, energy_usage=?, destination=?
            WHERE
                id=?;
        ";

        $this->database->execute($query,
            $building->getArea(),
            $building->getStoreys(),
            $building->getHousemates(),
            $building->getWaterUsage(),
            $building->getEnergyUsage(),
            $building->getDestination(),
            $building->getId()
        );
    }

    public function findBuildingIdByUserId($userId) {
        $query = "SELECT id FROM buildings WHERE user_id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $userId);

        return $result["id"];
    }

    public function findAddressIdByBuildingId($buildingId) {
        $query = "SELECT address_id FROM buildings WHERE id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $buildingId);

        return $result["address_id"];
    }
}
