<?php
class Address {
    private $country;
    private $district;
    private $community;
    private $location;
    private $street;
    private $buildingNo;
    private $apartmentNo;

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setDistrict($district) {
        $this->district = $district;
    }

    public function getDistrict() {
        return $this->district;
    }

    public function setCommunity($community) {
        $this->community = $community;
    }

    public function getCommunity() {
        return $this->community;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setBuildingNo($buildingNo) {
        $this->buildingNo = $buildingNo;
    }

    public function getBuildingNo() {
        return $this->buildingNo;
    }

    public function setApartmentNo($apartmentNo) {
        $this->apartmentNo = $apartmentNo;
    }

    public function getApartmentNo() {
        return $this->apartmentNo;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function getStreet() {
        return $this->street;
    }
}
