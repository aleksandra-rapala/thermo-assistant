<?php
require_once("src/models/HeaterType.php");
require_once("src/models/Heater.php");
require_once("src/models/ThermalClass.php");

class HeaterRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectAllByBuildingId($buildingId) {
        $query = "
        SELECT
            h.id AS id, t.name AS type, power, combustion_chamber, efficiency, installation_year, production_year, data_source, tc.name AS thermal_class, dust_extractor
        FROM
            heaters h
        JOIN
            heater_types t ON t.id = h.heater_type_id
        JOIN
            thermal_classes tc ON tc.id = h.thermal_class_id
        WHERE
              building_id = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $buildingId);
        $heaters = [];

        foreach ($result as $row) {
            $heaters[] = $this->mapToHeater($row);
        }

        return $heaters;
    }

    private function mapToHeater($row) {
        $id = $row["id"];
        $type = $row["type"];
        $power = $row["power"];
        $combustionChamber = $row["combustion_chamber"];
        $efficiency = $row["efficiency"];
        $installationYear = $row["installation_year"];
        $productionYear = $row["production_year"];
        $dataSource = $row["data_source"];
        $thermalClass = $row["thermal_class"];
        $dustExtractor = $row["dust_extractor"];

        $heater = new Heater();
        $heater->setId($id);
        $heater->setType($type);
        $heater->setPower($power);
        $heater->setCombustionCHamber($combustionChamber);
        $heater->setEfficiency($efficiency);
        $heater->setInstallationYear($installationYear);
        $heater->setProductionYear($productionYear);
        $heater->setDataSource($dataSource);
        $heater->setThermalClass($thermalClass);
        $heater->setDustExtractor($dustExtractor);

        return $heater;
    }

    public function selectAllToInstall($building) {
        $query = "
            SELECT
                ht.name AS name
            FROM
                heater_types ht
            JOIN
                heaters_to_install hti ON ht.id = hti.heater_type_id
            WHERE
                building_id = ?;
        ";

        $buildingId = $building->getId();
        $result = $this->database->executeAndFetchAll($query, $buildingId);

        return $this->mapToNames($result);
    }

    private function mapToNames($result) {
        $names = [];

        foreach ($result as $row) {
            $names[] = $row["name"];
        }

        return $names;
    }

    public function selectAllTypes() {
        $query = "SELECT id, name, label FROM heater_types;";
        $result = $this->database->executeAndFetchAll($query);

        return $this->mapToHeaterTypes($result);
    }

    private function mapToHeaterTypes($result) {
        $types = [];

        foreach ($result as $row) {
            $types[] = $this->mapToHeaterType($row);
        }

        return $types;
    }

    private function mapToHeaterType($result) {
        $id = $result["id"];
        $name = $result["name"];
        $label = $result["label"];

        $heaterType = new HeaterType();
        $heaterType->setId($id);
        $heaterType->setName($name);
        $heaterType->setLabel($label);

        return $heaterType;
    }

    public function update($building) {
        $this->retainAllFromBuilding($building);
        $this->insert($building);
    }

    public function retainAllFromBuilding($building) {
        $this->database->execute("DELETE FROM heaters_to_install WHERE building_id = ?;", $building->getId());
    }

    public function insert($building) {
        $this->assignAllToBuilding($building, $building->getHeatersToInstall());
    }

    private function assignAllToBuilding($building, $heatersToInstall) {
        $buildingId = $building->getId();

        $query = "
            INSERT INTO heaters_to_install
                (building_id, heater_type_id)
            VALUES
                (?, (SELECT id FROM heater_types WHERE name = ?));
        ";

        foreach ($heatersToInstall as $heaterToInstall) {
            $this->database->execute($query, $buildingId, $heaterToInstall);
        }
    }

    public function selectAllThermalClasses() {
        $query = "SELECT id, name, label, eco_project FROM thermal_classes;";
        $result = $this->database->executeAndFetchAll($query);
        $thermalClasses = [];

        foreach ($result as $row) {
            $thermalClasses[] = $this->mapToThermalClass($row);
        }

        return $thermalClasses;
    }

    private function mapToThermalClass($result) {
        $id = $result["id"];
        $name = $result["name"];
        $label = $result["label"];
        $ecoProject = $result["eco_project"];

        $thermalClass = new ThermalClass();
        $thermalClass->setId($id);
        $thermalClass->setName($name);
        $thermalClass->setLabel($label);
        $thermalClass->setEcoProject($ecoProject);

        return $thermalClass;
    }

    public function updateById($heaterId, $heater) {
        $query = "
            UPDATE
                heaters
            SET
                heater_type_id = (SELECT id FROM heater_types WHERE name = ?), power = ?, combustion_chamber = ?, efficiency = ?, installation_year = ?, production_year = ?, data_source = ?, dust_extractor = ?
            WHERE
                id = ?;
        ";

        $this->database->execute($query,
            $heater->getType(),
            $heater->getPower(),
            $heater->getCombustionChamber(),
            $heater->getEfficiency(),
            $heater->getInstallationYear(),
            $heater->getProductionYear(),
            $heater->getDataSource(),
            $heater->hasDustExtractor()? "true" : "false",
            $heaterId
        );
    }

    public function createByBuildingId($buildingId, $heater) {
        $query = "
            INSERT INTO heaters
                (building_id, heater_type_id, power, combustion_chamber, efficiency, installation_year, production_year, data_source, dust_extractor, thermal_class_id)
            VALUES
                (?, (SELECT id FROM heater_types WHERE name = ?), ?, ?, ?, ?, ?, ?, ?, (SELECT id FROM thermal_classes WHERE name = ?));
        ";

        $this->database->execute($query,
            $buildingId,
            $heater->getType(),
            $heater->getPower(),
            $heater->getCombustionChamber(),
            $heater->getEfficiency(),
            $heater->getInstallationYear(),
            $heater->getProductionYear(),
            $heater->getDataSource(),
            $heater->hasDustExtractor()? "true" : "false",
            $heater->getThermalClass()
        );
    }

    public function existsByHeaterIdAndBuildingId($heaterId, $buildingId) {
        $query = "SELECT count(*) AS count FROM heaters WHERE id = ? AND building_id = ?;";
        $result = $this->database->executeAndFetchFirst($query, $heaterId, $buildingId);

        return $result["count"] === 1;
    }
}
