<?php
class Distributor {
    private $id;
    private $companyName;
    private $fuels;
    private $address;

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function setFuels($fuels) {
        $this->fuels = $fuels;
    }

    public function getFuels() {
        return $this->fuels;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getAddress() {
        return $this->address;
    }
}
