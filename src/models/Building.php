<?php
class Building {
    private $id;
    private $address;
    private $details;
    private $plannedModernizations;
    private $completedModernizations;
    private $heatersToInstall;

    public function hasCompletedModernization($modernizationName) {
        return in_array($modernizationName, $this->completedModernizations);
    }

    public function hasPlannedModernization($modernizationName) {
        return in_array($modernizationName, $this->plannedModernizations);
    }

    public function hasHeaterToInstall($heaterTypeName) {
        return in_array($heaterTypeName, $this->heatersToInstall);
    }

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
