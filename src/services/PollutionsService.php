<?php
class PollutionsService {
    private $pollutionsRepository;
    private $heaterRepository;

    public function __construct($pollutionsRepository, $heaterRepository) {
        $this->pollutionsRepository = $pollutionsRepository;
        $this->heaterRepository = $heaterRepository;
    }

    public function findPollutionsByBuildingId($buildingId) {
        $pollutions = array("co2" => 0, "pm10" => 0, "pm25" => 0, "co" => 0, "nox" => 0, "so2" => 0, "bap" => 0);
        $heaters = $this->heaterRepository->selectAllByBuildingId($buildingId);
        $emissionIndicators = $this->resolveEmissionIndicatorsForHeaters($heaters);
        $totalCaloricValues = $this->pollutionsRepository->selectTotalCaloricValuesByBuildingId($buildingId);

        foreach ($totalCaloricValues as $fuelId => $totalCaloricValue) {
            $emissionIndicatorsForCurrentFuel = $emissionIndicators[$fuelId];
            $caloricValuePerEmissionIndicator = $totalCaloricValue / sizeof($emissionIndicatorsForCurrentFuel);

            foreach ($emissionIndicatorsForCurrentFuel as $emissionIndicatorForCurrentFuel) {
                $this->addPartialPollution($pollutions, $emissionIndicatorForCurrentFuel, $caloricValuePerEmissionIndicator);
            }
        }

        return $pollutions;
    }

    private function addPartialPollution(&$pollutions, $emissionIndicator, $caloricValuePerEmissionIndicator) {
        foreach ($pollutions as $substance => $value) {
            $pollutions[$substance] += $emissionIndicator[$substance] * $caloricValuePerEmissionIndicator;
        }
    }

    private function resolveEmissionIndicatorsForHeaters($heaters) {
        $emissionIndicatorsByFuels = [];

        foreach ($heaters as $heater) {
            $emissionIndicators = $this->resolveEmissionIndicatorsForHeater($heater);

            foreach ($emissionIndicators as $fuelId => $emissionIndicator) {
                $emissionIndicatorsByFuels[$fuelId][] = $emissionIndicator;
            }
        }

        return $emissionIndicatorsByFuels;
    }

    private function resolveEmissionIndicatorsForHeater($heater) {
        $emissionRules = $this->pollutionsRepository->selectMatchingEmissionRulesByHeaterId($heater->getId());
        $emissionIndicators = [];

        foreach ($emissionRules as $emissionRule) {
            $emissionIndicatorRules = $this->pollutionsRepository->selectEmissionIndicatorRulesByEmissionRule($emissionRule);

            foreach ($emissionIndicatorRules as $fuelId => $emissionIndicatorRule) {
                $emissionIndicators[$fuelId] = $emissionIndicatorRule;
            }
        }

        return $emissionIndicators;
    }

    public function describePollutions($pollutions) {
        $co2 = $pollutions["co2"];
        $pm10 = $pollutions["pm10"];
        $pm25 = $pollutions["pm25"];

        if ($co2 > 4000*10e6 || $pm10 > 8000 || $pm25 > 6000) {
            $label = "zauważalny";
        } else if ($co2 > 1000*10e6 || $pm10 > 5000 || $pm25 > 4000) {
            $label = "standardowy";
        } else {
            $label = "niewielki";
        }

        return "Twój budynek ma $label wpływ na środowisko";
    }
}
