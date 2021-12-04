<?php
require_once("src/models/User.php");

class UserRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function findUserByEmail($email) {
        $query = "SELECT uuid, name, surname, e_mail, password FROM users WHERE e_mail = ?;";
        $result = $this->database->execute($query, $email);
        $result = $result[0];

        $user = new User();
        $user->setUuid($result["uuid"]);
        $user->setName($result["name"]);
        $user->setSurname($result["surname"]);
        $user->setEmail($result["e_mail"]);
        $user->setPassword($result["password"]);

        return $user;
    }

    public function existsByEmail($email) {
        $query = "SELECT COUNT(*) FROM users WHERE e_mail = ?;";
        $result = $this->database->execute($query, $email);

        return $result[0]["count"] !== 0;
    }

    public function save($user) {
        $query = "INSERT INTO users (name, surname, e_mail, password) VALUES (?, ?, ?, ?);";
        $this->database->execute($query, $user->getName(), $user->getSurname(), $user->getEmail(), $user->getPassword());
    }
}
