<?php
class FuelService {
    private $fuelRepository;

    public function __construct($fuelRepository) {
        $this->fuelRepository = $fuelRepository;
    }

    public function findAvailableFuels() {
        return $this->fuelRepository->selectAll();
    }

    public function findFuelsConsumptionByUserId($userId) {
        return $this->fuelRepository->selectFuelsConsumptionByUserId($userId);
    }

    public function updateFuelsConsumptionByUserId($userId, $properties) {
        $fuelsConsumption = [];

        foreach ($properties as $fuelName => $value) {
            $fuelConsumption = new FuelConsumption();
            $fuelConsumption->setFuelName($fuelName);
            $fuelConsumption->setValue($value);

            $fuelsConsumption[] = $fuelConsumption;
        }

        $this->fuelRepository->updateFuelConsumptionByUserId($userId, $fuelsConsumption);
    }
}
