<?php
class Building {
    private $address;
    private $details;
    private $heaters;
    private $userId;

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

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getUserId() {
        return $this->userId;
    }
}
