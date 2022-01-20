<?php
class PollutionsRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function selectTotalCaloricValuesByBuildingId($buildingId) {
        $query = "
            SELECT
                f.id AS fuel_id, f.caloric_value * bf.consumption AS total_caloric_value
            FROM
                fuels f
            JOIN
                buildings_fuels bf ON f.id = bf.fuel_id
            WHERE
                building_id = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $buildingId);

        return $this->mapToTotalCaloricValues($result);
    }

    private function mapToTotalCaloricValues($result) {
        $totalCaloricValues = [];

        foreach ($result as $row) {
            $fuelId = $row["fuel_id"];
            $totalCaloricValues[$fuelId] = $row["total_caloric_value"];
        }

        return $totalCaloricValues;
    }

    public function selectMatchingEmissionRulesByHeaterId($heaterId) {
        $query = "
            SELECT
                er.id AS id
            FROM
                emission_rules er
            JOIN
                heaters h ON er.heater_type_id = h.heater_type_id
            JOIN
                thermal_classes tc ON tc.id = h.thermal_class_id
            WHERE
                h.id = ?
            AND
                ((tc.fifth_class AND er.fifth_class) OR NOT er.fifth_class)
            AND
                ((tc.eco_project AND er.eco_project) OR NOT er.eco_project)
            ORDER
                BY priority DESC;
        ";

        $result = $this->database->executeAndFetchAll($query, $heaterId);

        return $this->mapToEmissionRules($result);
    }

    private function mapToEmissionRules($result) {
        $emissionRules = [];

        foreach ($result as $row) {
            $emissionRules[] = $row["id"];
        }

        return $emissionRules;
    }

    public function selectEmissionIndicatorRulesByEmissionRule($emissionRule) {
        $query = "
            SELECT
                fuel_id, ei.*
            FROM
                emission_indicator_rules eir
            JOIN
                emission_indicators ei ON eir.emission_indicator_id = ei.id
            WHERE
                emission_rule_id = ?;
        ";

        $result = $this->database->executeAndFetchAll($query, $emissionRule);
        $emissionIndicatorRules = [];

        foreach ($result as $row) {
            $fuelId = $row["fuel_id"];
            $emissionIndicatorRules[$fuelId] = $row;
        }

        return $emissionIndicatorRules;
    }
}
