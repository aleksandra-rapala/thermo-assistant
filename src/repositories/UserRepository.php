<?php
class UserRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function findUserByEmail($email) {
        $query = "SELECT uuid, name, surname, e_mail, password FROM users WHERE e_mail = ?;";
        $result = $this->database->execute($query, $email);
    }

    public function existsByEmail($email) {
        $query = "SELECT COUNT(*) FROM users WHERE e_mail = ?;";
        $result = $this->database->execute($query, $email);
    }

    public function save($user) {
        $query = "INSERT INTO users (name, surname, e_mail, password) VALUES (?, ?, ?, ?);";
        $result = $this->database->execute($query, $user->getName(), $user->getSurname(), $user->getEmail(), $user->getPassword());
    }
}
