<?php
class ThermalClass {
    private $id;
    private $name;
    private $label;
    private $ecoProject;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setEcoProject($ecoProject) {
        $this->ecoProject = $ecoProject;
    }

    public function isEcoProject() {
        return $this->ecoProject;
    }
}
