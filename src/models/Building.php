<?php
class Building {
    private $address;
    private $area;
    private $storeys;
    private $housemates;
    private $waterUsage;
    private $energyUsage;
    private $destination;
    private $heaters;
    private $userId;

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

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getUserId() {
        return $this->userId;
    }
}
