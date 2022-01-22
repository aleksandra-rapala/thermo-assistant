<?php
require_once("src/models/Building.php");
require_once("src/models/Details.php");
require_once("src/models/Address.php");

class BuildingService {
    private $buildingRepository;
    private $modernizationRepository;
    private $heaterRepository;
    private $subscriptionRepository;

    public function __construct($buildingRepository, $modernizationRepository, $heaterRepository, $subscriptionRepository) {
        $this->buildingRepository = $buildingRepository;
        $this->modernizationRepository = $modernizationRepository;
        $this->heaterRepository = $heaterRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function existsByUserId($userId) {
        return $this->buildingRepository->existsByUserId($userId);
    }

    public function findBuildingIdByUserId($userId) {
        return $this->buildingRepository->selectBuildingIdByUserId($userId);
    }

    public function create($userId, $properties) {
        $building = $this->mapToBuilding($properties);
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
        $this->buildingRepository->update($userId, $building);
    }

    public function findByUserId($userId) {
        return $this->buildingRepository->selectByUserId($userId);
    }

    public function findAvailableModernizations() {
        return $this->modernizationRepository->selectAll();
    }

    public function findAvailableUsageOptions() {
        return [
            "little" => "Niewielkie",
            "standard" => "Standardowe",
            "noticeable" => "Zauważalne"
        ];
    }

    public function findAvailableDestinationOptions() {
        return [
            "residential" => "Mieszkalny",
            "service" => "Usługowy",
            "industrial" => "Przemysłowy",
            "residential-n-service" => "Mieszkalno-Usługowy"
        ];
    }

    public function findAvailableHeaterTypes() {
        return $this->heaterRepository->selectAllTypes();
    }

    public function findSubscriptionsByBuildingId($buildingId) {
        return $this->subscriptionRepository->selectAllNamesByBuildingId($buildingId);
    }

    public function subscribe($buildingId, $subscriptionName) {
        $this->subscriptionRepository->subscribe($buildingId, $subscriptionName);
    }

    public function unsubscribe($buildingId, $subscriptionName) {
        $this->subscriptionRepository->unsubscribe($buildingId, $subscriptionName);
    }

    public function isObligatedToRegisterInCEEB($buildingId) {
        $heaters = $this->heaterRepository->selectAllByBuildingId($buildingId);

        foreach ($heaters as $heater) {
            $power = $heater->getPower();

            if ($power != 0 && $power <= 1000) {
                return true;
            }
        }

        return false;
    }
}
