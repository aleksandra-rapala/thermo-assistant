<?php
class Building {
    private $id;
    private $address;
    private $details;
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

    public function setDetails($details) {
        $this->details = $details;
    }

    public function getDetails() {
        return $this->details;
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
