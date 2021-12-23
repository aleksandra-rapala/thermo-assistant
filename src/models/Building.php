<?php
class Building {
    private $id;
    private $address;
    private $area;
    private $storeys;
    private $housemates;
    private $waterUsage;
    private $energyUsage;
    private $destination;
    private $heaters;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setArea($area) {
        $this->area = $area;
    }

    public function getArea() {
        return $this->area;
    }

    public function setStoreys($storeys) {
        $this->storeys = $storeys;
    }

    public function getStoreys() {
        return $this->storeys;
    }

    public function setHousemates($housemates) {
        $this->housemates = $housemates;
    }

    public function getHousemates() {
        return $this->housemates;
    }

    public function setWaterUsage($waterUsage) {
        $this->waterUsage = $waterUsage;
    }

    public function getWaterUsage() {
        return $this->waterUsage;
    }

    public function setEnergyUsage($energyUsage) {
        $this->energyUsage = $energyUsage;
    }

    public function getEnergyUsage() {
        return $this->energyUsage;
    }

    public function setDestination($destination) {
        $this->destination = $destination;
    }

    public function getDestination() {
        return $this->destination;
    }

    public function setHeaters($heaters) {
        $this->heaters = $heaters;
    }

    public function getHeaters() {
        return $this->heaters;
    }

    public function addHeater($heater) {
        array_push($this->heaters, $heater);
    }
}
