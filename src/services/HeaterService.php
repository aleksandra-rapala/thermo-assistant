<?php
class HeaterService {
    private $heaterRepository;

    public function __construct($heaterRepository) {
        $this->heaterRepository = $heaterRepository;
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

    public function updateHeatersByBuildingId($buildingId, $properties) {
        $heatersId = $properties["heater-id"];

        foreach ($heatersId as $index => $heaterId) {
            if ($this->heaterRepository->existsByHeaterIdAndBuildingId($heaterId, $buildingId)) {
                $heater = $this->mapToHeater($properties, $index);
                $this->heaterRepository->updateById($heaterId, $heater);
            }
        }
    }

    private function mapToHeater($properties, $index) {
        $heaterType = $properties["type"][$index];
        $power = floatval($properties["power"][$index]);
        $combustionChamber = $properties["combustion-chamber"][$index];
        $efficiency = floatval($properties["efficiency"][$index]);
        $installationYear = intval($properties["installation-year"][$index]);
        $productionYear = intval($properties["production-year"][$index]);
        $dataSource = $properties["data-source"][$index];
        $thermalClass = $properties["thermal-class"][$index];
        $dustExtractor = boolval($properties["dust-extractor"][$index]);

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

    public function createByBuildingId($buildingId) {
        $this->heaterRepository->createByBuildingId($buildingId);
    }
}
