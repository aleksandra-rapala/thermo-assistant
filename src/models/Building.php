<?php
class Building {
    private $id;
    private $address;
    private $details;
    private $heaters;
    private $plannedModernizations;
    private $completedModernizations;
    private $heatersToInstall;

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

    public function setPlannedModernizations($plannedModernizations) {
        $this->plannedModernizations = $plannedModernizations;
    }

    public function getPlannedModernizations() {
        return $this->plannedModernizations;
    }

    public function setCompletedModernizations($completedModernizations) {
        $this->completedModernizations = $completedModernizations;
    }

    public function getCompletedModernizations() {
        return $this->completedModernizations;
    }

    public function setHeatersToInstall($heatersToInstall) {
        $this->heatersToInstall = $heatersToInstall;
    }

    public function getHeatersToInstall() {
        return $this->heatersToInstall;
    }
}
