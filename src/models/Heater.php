<?php
class Heater {
    private $type;
    private $power;
    private $combustionChamber;
    private $efficiency;
    private $installationYear;
    private $productionYear;
    private $dataSource;
    private $thermalClass;
    private $ecoProject;
    private $dustExtractor;

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setPower($power) {
        $this->power = $power;
    }

    public function getPower() {
        return $this->power;
    }

    public function setCombustionChamber($combustionChamber) {
        $this->combustionChamber = $combustionChamber;
    }

    public function getCombustionChamber() {
        return $this->combustionChamber;
    }

    public function setEfficiency($efficiency) {
        $this->efficiency = $efficiency;
    }

    public function getEfficiency() {
        return $this->efficiency;
    }

    public function setInstallationYear($installationYear) {
        $this->installationYear = $installationYear;
    }

    public function getInstallationYear() {
        return $this->installationYear;
    }

    public function setProductionYear($productionYear) {
        $this->productionYear = $productionYear;
    }

    public function getProductionYear() {
        return $this->productionYear;
    }

    public function setDataSource($dataSource) {
        $this->dataSource = $dataSource;
    }

    public function getDataSource() {
        return $this->dataSource;
    }

    public function setThermalClass($thermalClass) {
        $this->thermalClass = $thermalClass;
    }

    public function getThermalClass() {
        return $this->thermalClass;
    }

    public function setEcoProject($ecoProject) {
        $this->ecoProject = $ecoProject;
    }

    public function getEcoProject() {
        return $this->ecoProject;
    }

    public function setDustExtractor($dustExtractor) {
        $this->dustExtractor = $dustExtractor;
    }

    public function hasDustExtractor() {
        return $this->dustExtractor;
    }
}
