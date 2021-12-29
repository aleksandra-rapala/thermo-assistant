<?php
class FuelService {
    private $fuelRepository;
    private $addressRepository;

    public function __construct($fuelRepository, $addressRepository) {
        $this->fuelRepository = $fuelRepository;
        $this->addressRepository = $addressRepository;
    }

    public function findAvailableFuels() {
        return $this->fuelRepository->selectAll();
    }

    public function findFuelsConsumptionByBuildingId($buildingId) {
        return $this->fuelRepository->selectFuelsConsumptionByBuildingId($buildingId);
    }

    public function updateFuelsConsumptionByBuildingId($buildingId, $properties) {
        $fuelsConsumption = [];

        foreach ($properties as $fuelName => $value) {
            $fuelConsumption = new FuelConsumption();
            $fuelConsumption->setFuelName($fuelName);
            $fuelConsumption->setValue($value);

            $fuelsConsumption[] = $fuelConsumption;
        }

        $this->fuelRepository->updateFuelConsumptionByBuildingId($buildingId, $fuelsConsumption);
    }

    public function findDistributorsByBuildingId($buildingId) {
        $community = $this->addressRepository->selectCommunityByBuildingId($buildingId);
        $distributors = $this->fuelRepository->selectDistributorsByCommunity($community);

        return $distributors;
    }
}
