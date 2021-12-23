<?php
require_once("src/models/Building.php");
require_once("src/models/Address.php");

class BuildingService {
    private $buildingRepository;

    public function __construct($buildingRepository) {
        $this->buildingRepository = $buildingRepository;
    }

    public function existsByUserId($userId) {
        return $this->buildingRepository->existsByUserId($userId);
    }

    public function create($userId, $properties) {
        $building = $this->mapToBuilding($properties);
        $this->buildingRepository->insert($userId, $building);
    }

    private function mapToBuilding($properties, $heaters=[]) {
        $area = floatval($properties["area"]);
        $storeys = intval($properties["storeys-count"]);
        $housemates = intval($properties["housemates"]);
        $waterUsage = $properties["water-usage"];
        $energyUsage = $properties["electricity-usage"];
        $destination = $properties["destination"];
        $address = $this->mapToAddress($properties);

        $building = new Building();
        $building->setArea($area);
        $building->setStoreys($storeys);
        $building->setHousemates($housemates);
        $building->setWaterUsage($waterUsage);
        $building->setEnergyUsage($energyUsage);
        $building->setDestination($destination);
        $building->setHeaters($heaters);
        $building->setAddress($address);

        return $building;
    }

    private function mapToAddress($properties) {
        $country = $properties["country"];
        $district = $properties["district"];
        $community = $properties["community"];
        $location = $properties["location"];
        $street = $properties["street"];
        $buildingNo = $properties["building-no"];
        $apartmentNo = $properties["apartment-no"];

        $address = new Address();
        $address->setCountry($country);
        $address->setDistrict($district);
        $address->setCommunity($community);
        $address->setLocation($location);
        $address->setStreet($street);
        $address->setBuildingNo($buildingNo);
        $address->setApartmentNo($apartmentNo);

        return $address;
    }

    public function update($userId, $properties) {
        $building = $this->mapToBuilding($properties);
        $buildingId = $this->buildingRepository->findBuildingIdByUserId($userId);
        $building->setId($buildingId);

        $address = $building->getAddress();
        $addressId = $this->buildingRepository->findAddressIdByBuildingId($buildingId);
        $address->setId($addressId);

        $this->buildingRepository->update($building);
    }
}
