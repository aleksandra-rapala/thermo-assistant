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

    public function findByUserId($userId) {
        $query = "SELECT * FROM buildings WHERE user_id = ?;";
        $details_result = $this->database->executeAndFetchFirst($query, $userId);
        $query = "SELECT * FROM addresses JOIN buildings on addresses.id = buildings.address_id WHERE user_id = ?;";
        $address_result = $this->database->executeAndFetchFirst($query, $userId);

        return $this->mapToBuilding($details_result, $address_result);
    }

    private function mapToBuilding($details_result, $address_result) {
        $id = $details_result["id"];
        $area = $details_result["area"];
        $storeys = $details_result["storeys-count"];
        $housemates = $details_result["housemates"];
        $waterUsage = $details_result["water_usage"];
        $energyUsage = $details_result["energy_usage"];
        $destination = $details_result["destination"];
        $address = $this->mapToAddress($address_result);

        $building = new Building();
        $building->setId($id);
        $building->setArea($area);
        $building->setStoreys($storeys);
        $building->setHousemates($housemates);
        $building->setWaterUsage($waterUsage);
        $building->setEnergyUsage($energyUsage);
        $building->setDestination($destination);
        $building->setHeaters([]);
        $building->setAddress($address);

        return $building;
    }

    private function mapToAddress($result) {
        $id = $result["address_id"];
        $country = $result["country"];
        $district = $result["district"];
        $community = $result["community"];
        $location = $result["location"];
        $street = $result["street"];
        $buildingNo = $result["building_no"];
        $apartmentNo = $result["apartment_no"];

        $address = new Address();
        $address->setId($id);
        $address->setCountry($country);
        $address->setDistrict($district);
        $address->setCommunity($community);
        $address->setLocation($location);
        $address->setStreet($street);
        $address->setBuildingNo($buildingNo);
        $address->setApartmentNo($apartmentNo);

        return $address;
    }
}
