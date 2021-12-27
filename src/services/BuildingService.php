<?php
require_once("src/models/Building.php");
require_once("src/models/Details.php");
require_once("src/models/Address.php");

class BuildingService {
    private $buildingRepository;
    private $modernizationRepository;
    private $heaterRepository;

    public function __construct($buildingRepository, $modernizationRepository, $heaterRepository) {
        $this->buildingRepository = $buildingRepository;
        $this->modernizationRepository = $modernizationRepository;
        $this->heaterRepository = $heaterRepository;
    }

    public function existsByUserId($userId) {
        return $this->buildingRepository->existsByUserId($userId);
    }

    public function create($userId, $properties) {
        $building = $this->mapToBuilding($properties);
        $building->setHeaters([]);

        $this->buildingRepository->insert($userId, $building);
    }

    private function mapToBuilding($properties) {
        $details = $this->mapToDetails($properties);
        $address = $this->mapToAddress($properties);

        $building = new Building();
        $building->setDetails($details);
        $building->setAddress($address);

        $modernizations = $properties["planned-modernizations"] ?: [];
        $building->setPlannedModernizations($modernizations);

        $modernizations = $properties["completed-modernizations"] ?: [];
        $building->setCompletedModernizations($modernizations);

        $heatersToInstall = $properties["heaters-to-install"] ?: [];
        $building->setHeatersToInstall($heatersToInstall);

        return $building;
    }

    private function mapToDetails($properties) {
        $area = floatval($properties["area"]);
        $storeys = intval($properties["storeys"]);
        $housemates = intval($properties["housemates"]);
        $waterUsage = $properties["water-usage"];
        $energyUsage = $properties["energy-usage"];
        $destination = $properties["destination"];

        $details = new Details();
        $details->setArea($area);
        $details->setStoreys($storeys);
        $details->setHousemates($housemates);
        $details->setWaterUsage($waterUsage);
        $details->setEnergyUsage($energyUsage);
        $details->setDestination($destination);

        return $details;
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
        $building->setHeaters([]);

        $this->buildingRepository->update($userId, $building);
    }

    public function findByUserId($userId) {
        return $this->buildingRepository->selectByUserId($userId);
    }

    public function findAvailableModernizations() {
        return $this->modernizationRepository->selectAll();
    }

    public function findAvailableUsageOptions() {
        return ["little" => "Niewielkie", "standard" => "Standardowe", "noticeable" => "Zauważalne"];
    }

    public function findAvailableDestinationOptions() {
        return ["residential" => "Mieszkalny", "service" => "Usługowy", "industrial" => "Przemysłowy", "residential-n-service" => "Mieszkalno-Usługowy"];
    }

    public function findAvailableHeaterTypes() {
        return $this->heaterRepository->selectAllTypes();
    }
}