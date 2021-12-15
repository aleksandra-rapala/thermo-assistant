<?php
require_once("src/models/User.php");

class UserRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByEmail($email) {
        $query = "SELECT userId, name, surname, e_mail, password FROM users WHERE e_mail = ?;";
        $result = $this->database->executeAndFetchFirst($query, $email);

        if (empty($result)) {
            throw new UserNotFoundException();
        }

        $userId = $result["userId"];
        $name = $result["name"];
        $surname = $result["surname"];
        $email = $result["e_mail"];
        $password = $result["password"];

        $user = new User();
        $user->setUserId($userId);
        $user->setName($name);
        $user->setSurname($surname);
        $user->setEmail($email);
        $user->setPassword($password);

        return $user;
    }

    public function existsByEmail($email) {
        $query = "SELECT COUNT(*) AS count FROM users WHERE e_mail = ?;";
        $result = $this->database->executeAndFetchFirst($query, $email);

        return $result["count"] !== 0;
    }

    public function save($user) {
        $query = "INSERT INTO users (name, surname, e_mail, password) VALUES (?, ?, ?, ?);";
        $name = $user->getName();
        $surname = $user->getSurname();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $this->database->execute($query, $name, $surname, $email, $password);
    }
}
