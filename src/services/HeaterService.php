<?php
class HeaterService {
    private $heaterRepository;

    public function __construct($heaterRepository) {
        $this->heaterRepository = $heaterRepository;
    }

    public function existsByHeaterIdAndBuildingId($heaterId, $buildingId) {
        return $this->heaterRepository->existsByHeaterIdAndBuildingId($heaterId, $buildingId);
    }

    public function findAllHeaterTypes() {
        $heaterTypeEntities = $this->heaterRepository->selectAllTypes();
        $heaterTypes = [];

        foreach ($heaterTypeEntities as $heaterTypeEntity) {
            $heaterTypes[$heaterTypeEntity->getName()] = $heaterTypeEntity->getLabel();
        }

        return $heaterTypes;
    }

    public function findAllThermalClasses() {
        $thermalClassEntities = $this->heaterRepository->selectAllThermalClasses();
        $thermalClasses = [];

        foreach ($thermalClassEntities as $thermalClassEntity) {
            $thermalClasses[$thermalClassEntity->getName()] = $thermalClassEntity->getLabel();
        }

        return $thermalClasses;
    }

    public function findHeatersByBuildingId($buildingId) {
        return $this->heaterRepository->selectAllByBuildingId($buildingId);
    }

    public function updateHeaterById($heaterId, $properties) {
        $heater = $this->mapToHeater($properties);
        $this->heaterRepository->updateById($heaterId, $heater);
    }

    private function mapToHeater($properties) {
        $heaterType = $properties["type"];
        $power = floatval($properties["power"]);
        $combustionChamber = $properties["combustion-chamber"];
        $efficiency = floatval($properties["efficiency"]);
        $installationYear = intval($properties["installation-year"]);
        $productionYear = intval($properties["production-year"]);
        $dataSource = $properties["data-source"];
        $thermalClass = $properties["thermal-class"];
        $dustExtractor = boolval($properties["dust-extractor"]);

        $heater = new Heater();
        $heater->setType($heaterType);
        $heater->setPower($power);
        $heater->setCombustionChamber($combustionChamber);
        $heater->setEfficiency($efficiency);
        $heater->setInstallationYear($installationYear);
        $heater->setProductionYear($productionYear);
        $heater->setDataSource($dataSource);
        $heater->setThermalClass($thermalClass);
        $heater->setDustExtractor($dustExtractor);

        return $heater;
    }

    public function createByBuildingId($buildingId, $properties) {
        $heater = $this->mapToHeater($properties);
        $this->heaterRepository->createByBuildingId($buildingId, $heater);
    }
}
