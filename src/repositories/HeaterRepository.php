<?php
require_once("src/models/HeaterType.php");
require_once("src/models/Heater.php");

class HeaterRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectAllByBuildingId($buildingId) {
        $query = "SELECT * FROM heaters WHERE building_id = ?;";
        $result = $this->database->executeAndFetchAll($query, $buildingId);
        $heaters = [];

        foreach ($result as $row) {
            $heaters[] = $this->mapToHeater($row);
        }

        return $heaters;
    }

    private function mapToHeater($row) {
        $heater = new Heater();

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
}
