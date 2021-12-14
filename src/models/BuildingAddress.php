<?php
class BuildingAddress {
    private $country;
    private $district;
    private $community;
    private $location;
    private $street;
    private $buildingNo;
    private $apartmentNo;

    public function setStreet($street) {
        $this->street = $street;
    }

    public function getStreet() {
        return $this->street;
    }
}
