<?php
class Modernization {
    private $id;
    private $name;
    private $label;

    public function setId($id): void {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setLabel($label): void {
        $this->label = $label;
    }

    public function getLabel() {
        return $this->label;
    }
}
