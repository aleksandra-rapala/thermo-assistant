<?php
require_once("src/models/Modernization.php");

class ModernizationRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectAllAssignedToBuilding($building, $status) {
        $query = "
            SELECT
                m.id AS id, m.name AS name, m.label AS label
            FROM
                modernizations m
            JOIN
                buildings_modernizations bm ON m.id = bm.modernization_id
            WHERE
                bm.building_id = ? AND bm.status = ?;
        ";

        $buildingId = $building->getId();
        $result = $this->database->executeAndFetchAll($query, $buildingId, $status);
        $modernizations = $this->mapToModernizations($result);

        return $modernizations;
    }

    private function mapToModernizations($result) {
        $modernizations = [];

        foreach ($result as $row) {
            $modernizations[] = $this->mapToModernization($row);
        }

        return $modernizations;
    }

    private function mapToModernization($result) {
        $id = $result["id"];
        $name = $result["name"];
        $label = $result["label"];

        $modernization = new Modernization();
        $modernization->setId($id);
        $modernization->setName($name);
        $modernization->setLabel($label);

        return $modernization;
    }

    public function selectAllByNames($names) {
        $modernizations = [];

        foreach ($names as $name) {
            $modernizations[] = $this->selectByName($name);
        }

        return $modernizations;
    }

    private function selectByName($name) {
        $query = "SELECT id, name, label FROM modernizations WHERE name = ?";
        $result = $this->database->executeAndFetchFirst($query, $name);

        return $this->mapToModernization($result);
    }

    public function update($building) {
        $this->retainAllFromBuilding($building);
        $this->insert($building);
    }

    public function retainAllFromBuilding($building) {
        $this->database->execute("DELETE FROM buildings_modernizations WHERE building_id = ?;", $building->getId());
    }

    public function insert($building) {
        $modernizations = $building->getPlannedModernizations();
        $this->assignAllToBuilding($building, $modernizations, "planned");

        $modernizations = $building->getCompletedModernizations();
        $this->assignAllToBuilding($building, $modernizations, "completed");
    }

    private function assignAllToBuilding($building, $modernizations, $status) {
        $buildingId = $building->getId();

        $query = "
            INSERT INTO buildings_modernizations
                (building_id, modernization_id, status)
            VALUES
                (?, ?, ?);
        ";

        foreach ($modernizations as $modernization) {
            $modernizationId = $modernization->getId();
            $this->database->execute($query, $buildingId, $modernizationId, $status);
        }
    }

    public function selectAll() {
        $query = "SELECT id, name, label FROM modernizations;";
        $result = $this->database->executeAndFetchAll($query);
        $modernizations = $this->mapToModernizations($result);

        return $modernizations;
    }
}
