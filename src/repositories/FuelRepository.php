<?php
require_once("src/models/Fuel.php");
require_once("src/models/FuelConsumption.php");

class FuelRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectAll() {
        $query = "SELECT id, name, label, unit FROM fuels;";
        $result = $this->database->executeAndFetchAll($query);

        return $this->mapToFuels($result);
    }

    private function mapToFuels($result) {
        $fuels = [];

        foreach ($result as $row) {
            $fuels[] = $this->mapToFuel($row);
        }

        return $fuels;
    }

    private function mapToFuel($row) {
        $id = $row["id"];
        $name = $row["name"];
        $label = $row["label"];
        $unit = $row["unit"];

        $fuel = new Fuel();
        $fuel->setId($id);
        $fuel->setName($name);
        $fuel->setLabel($label);
        $fuel->setUnit($unit);

        return $fuel;
    }

    public function selectFuelsConsumptionByUserId($userId) {
        $query = "
            SELECT
                f.name AS fuel_name, consumption AS value
            FROM
                 buildings_fuels bf
            JOIN
                fuels f ON f.id = bf.fuel_id
            JOIN
                buildings b ON b.id = bf.building_id
            WHERE
                b.user_id = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $userId);

        return $this->mapToFuelsConsumption($result);
    }

    private function mapToFuelsConsumption($result) {
        $fuelsConsumption = [];

        foreach ($result as $row) {
            $fuelName = $row["fuel_name"];
            $value = $row["value"];
            $fuelConsumption = new FuelConsumption();
            $fuelConsumption->setFuelName($fuelName);
            $fuelConsumption->setValue($value);

            $fuelsConsumption[$fuelName] = $fuelConsumption;
        }

        return $fuelsConsumption;
    }

    public function updateFuelConsumptionByUserId($userId, $fuelsConsumption) {
        $this->database->withinTransaction(function() use ($userId, $fuelsConsumption) {
            $this->retainAllFromUser($userId);
            $this->assignAllByUserId($userId, $fuelsConsumption);
        });
    }

    private function retainAllFromUser($userId) {
        $query = "DELETE FROM buildings_fuels WHERE building_id = (SELECT id FROM buildings WHERE user_id = ?);";
        $this->database->execute($query, $userId);
    }

    private function assignAllByUserId($userId, $fuelsConsumption) {
        $query = "
            INSERT INTO buildings_fuels
                (building_id, fuel_id, consumption)
            VALUES
                ((SELECT id FROM buildings WHERE user_id = ?), (SELECT id FROM fuels WHERE name = ?), ?);
        ";

        foreach ($fuelsConsumption as $fuelConsumption) {
            $value = $fuelConsumption->getValue();

            if ($value > 0) {
                $this->database->execute($query, $userId, $fuelConsumption->getFuelName(), $value);
            }
        }
    }
}
