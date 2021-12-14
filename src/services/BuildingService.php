<?php
require_once("src/models/Building.php");
require_once("src/models/BuildingAddress.php");

class BuildingService {
    private $buildingRepository;

    public function __construct($buildingRepository) {
        $this->buildingRepository =  $buildingRepository;
    }

    public function existsByUserId($userId) {
        return $this->buildingRepository->existsByUserId($userId);
    }

    public function create($userId, $properties) {
        $street = $properties["street"];

        $address = new BuildingAddress();
        $address->setStreet($street);

        $building = new Building();
        $building->setUserId($userId);
        $building->setAddress($address);

        $this->buildingRepository->insert($building);
    }

    public function update($userId, $properties) {
        var_dump($properties);
    }
}
