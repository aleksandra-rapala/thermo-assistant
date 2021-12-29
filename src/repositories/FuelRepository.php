<?php
require_once("src/models/Fuel.php");
require_once("src/models/FuelConsumption.php");
require_once("src/models/Distributor.php");

class FuelRepository {
    private $database;
    private $addressRepository;

    public function __construct($database, $addressRepository) {
        $this->database = $database;
        $this->addressRepository = $addressRepository;
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

    public function selectFuelsConsumptionByBuildingId($buildingId) {
        $query = "
            SELECT
                f.name AS fuel_name, consumption AS value
            FROM
                 buildings_fuels bf
            JOIN
                fuels f ON f.id = bf.fuel_id
            WHERE
                bf.building_id = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $buildingId);

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

    public function updateFuelConsumptionByBuildingId($buildingId, $fuelsConsumption) {
        $this->database->withinTransaction(function() use ($buildingId, $fuelsConsumption) {
            $this->retainAllByBuildingId($buildingId);
            $this->assignAllByBuildingId($buildingId, $fuelsConsumption);
        });
    }

    private function retainAllByBuildingId($buildingId) {
        $query = "DELETE FROM buildings_fuels WHERE building_id = ?;";
        $this->database->execute($query, $buildingId);
    }

    private function assignAllByBuildingId($buildingId, $fuelsConsumption) {
        $query = "
            INSERT INTO buildings_fuels
                (building_id, fuel_id, consumption)
            VALUES
                (?, (SELECT id FROM fuels WHERE name = ?), ?);
        ";

        foreach ($fuelsConsumption as $fuelConsumption) {
            $value = $fuelConsumption->getValue();

            if ($value > 0) {
                $this->database->execute($query, $buildingId, $fuelConsumption->getFuelName(), $value);
            }
        }
    }

    public function selectDistributorsByCommunity($community) {
        $query = "SELECT d.* FROM distributors d JOIN addresses a ON d.address_id = a.id WHERE a.community = ?;";
        $result = $this->database->executeAndFetchAll($query, $community);
        $distributors = [];

        foreach ($result as $row) {
            $distributors[] = $this->mapToDistributor($row);
        }

        return $distributors;
    }

    private function mapToDistributor($result) {
        $id = $result["id"];
        $companyName = $result["company_name"];
        $fuels = $this->selectFuelLabelsByDistributorId($id);
        $address = $this->addressRepository->selectAddressByDistributorId($id);

        $distributor = new Distributor();
        $distributor->setId($id);
        $distributor->setCompanyName($companyName);
        $distributor->setFuels($fuels);
        $distributor->setAddress($address);

        return $distributor;
    }

    private function selectFuelLabelsByDistributorId($distributorId) {
        $query = "SELECT label FROM fuels f JOIN distributors_fuels df ON df.fuel_id = f.id WHERE df.distributor_id = ?;";
        $result = $this->database->executeAndFetchAll($query, $distributorId);
        $labels = [];

        foreach ($result as $row) {
            $labels[] = $row["label"];
        }

        return $labels;
    }
}
