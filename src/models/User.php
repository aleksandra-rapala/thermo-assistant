<?php
class User {
    private $name;
    private $surname;
    private $email;
    private $password;
    private $uuid;

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    public function getUuid() {
        return $this->uuid;
    }
}
