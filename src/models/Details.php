<?php
class Details {
    private $id;
    private $area;
    private $storeys;
    private $housemates;
    private $waterUsage;
    private $energyUsage;
    private $destination;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
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
}