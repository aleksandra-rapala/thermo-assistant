<?php
require_once("src/models/Address.php");

class AddressRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectAddressByDistributorId($distributorId) {
        $query = "SELECT * FROM addresses a JOIN distributors d ON a.id = d.address_id WHERE d.id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $distributorId);

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

    public function selectAddressByBuildingId($buildingId) {
        $query = "SELECT * FROM addresses JOIN buildings ON addresses.id = buildings.address_id WHERE buildings.id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $buildingId);

        return $this->mapToAddress($result);
    }

    public function selectCommunityByBuildingId($buildingId) {
        $query = "SELECT community FROM addresses a JOIN buildings b ON b.address_id = a.id WHERE b.id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $buildingId);

        return $result["community"];
    }
}