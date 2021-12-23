<?php
require_once("src/models/Building.php");
require_once("src/models/Details.php");
require_once("src/models/Address.php");

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
        $details = $building->getDetails();
        $address = $building->getAddress();

        $detailsId = $this->insertDetails($details);
        $addressId = $this->insertAddress($address);

        $this->insertBuilding($userId, $detailsId, $addressId);
    }

    private function insertDetails($details) {
        $query = "
            INSERT INTO details
                (area, storeys, housemates, water_usage, energy_usage, destination)
            VALUES
                (?, ?, ?, ?, ?, ?)
            RETURNING id;
        ";

        $result = $this->database->executeAndFetchFirst($query,
            $details->getArea(),
            $details->getStoreys(),
            $details->getHousemates(),
            $details->getWaterUsage(),
            $details->getEnergyUsage(),
            $details->getDestination()
        );

        return $result["id"];
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

    private function insertBuilding($userId, $detailsId, $addressId) {
        $query = "
            INSERT INTO buildings
                (user_id, details_id, address_id)
            VALUES
                (?, ?, ?);
        ";

        $this->database->execute($query, $userId, $detailsId, $addressId);
    }

    public function update($userId, $building) {
        $query = "SELECT details_id, address_id FROM buildings WHERE user_id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $userId);

        $addressId = $result["address_id"];
        $detailsId = $result["details_id"];

        $address = $building->getAddress();
        $address->setId($addressId);

        $details = $building->getDetails();
        $details->setId($detailsId);

        $this->updateDetails($details);
        $this->updateAddress($address);
    }

    private function updateDetails($details) {
        $query = "
            UPDATE details SET
                area = ?, storeys = ?, housemates = ?, water_usage = ?, energy_usage = ?, destination = ?
            WHERE
                id = ?;
        ";

        $this->database->execute($query,
            $details->getArea(),
            $details->getStoreys(),
            $details->getHousemates(),
            $details->getWaterUsage(),
            $details->getEnergyUsage(),
            $details->getDestination(),
            $details->getId()
        );
    }

    private function updateAddress($address) {
        $query = "
            UPDATE addresses SET
                country = ?, district = ?, community = ?, location = ?, street = ?, building_no = ?, apartment_no = ?
            WHERE
                id = ?;
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

    public function selectByUserId($userId) {
        $query = "SELECT id FROM buildings WHERE user_id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $userId);
        $buildingId = $result["id"];

        return $this->selectById($buildingId);
    }

    private function selectById($buildingId) {
        $details = $this->selectDetailsByBuildingId($buildingId);
        $address = $this->selectAddressByBuildingId($buildingId);

        $building = new Building();
        $building->setId($buildingId);
        $building->setDetails($details);
        $building->setAddress($address);

        return $building;
    }

    private function selectDetailsByBuildingId($buildingId) {
        $query = "SELECT * FROM details JOIN buildings ON details.id = buildings.details_id WHERE buildings.id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $buildingId);

        return $this->mapToDetails($result);
    }

    private function mapToDetails($result) {
        $id = $result["id"];
        $area = $result["area"];
        $storeys = $result["storeys-count"];
        $housemates = $result["housemates"];
        $waterUsage = $result["water_usage"];
        $energyUsage = $result["energy_usage"];
        $destination = $result["destination"];

        $details = new Details();
        $details->setId($id);
        $details->setArea($area);
        $details->setStoreys($storeys);
        $details->setHousemates($housemates);
        $details->setWaterUsage($waterUsage);
        $details->setEnergyUsage($energyUsage);
        $details->setDestination($destination);

        return $details;
    }
    
    private function selectAddressByBuildingId($buildingId) {
        $query = "SELECT * FROM addresses JOIN buildings ON addresses.id = buildings.address_id WHERE buildings.id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $buildingId);
        
        return $this->mapToAddress($result);
    }

    private function mapToAddress($result) {
        $id = $result["id"];
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
