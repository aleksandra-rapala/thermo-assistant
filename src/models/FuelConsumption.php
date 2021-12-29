<?php
class FuelConsumption {
    private $fuelName;
    private $value;

    public function setFuelName($fuelName) {
        $this->fuelName = $fuelName;
    }

    public function getFuelName() {
        return $this->fuelName;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}
