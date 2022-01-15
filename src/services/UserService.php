<?php
require_once("src/models/User.php");
require_once("src/exceptions/PasswordMismatchException.php");

class UserService {
    private $userRepository;

    public function __construct($userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws PasswordMismatchException
     */
    public function getUser($email, $password) {
        $user = $this->userRepository->findUserByEmail($email);
        $userPassword = $user->getPassword();

        if (password_verify($password, $userPassword)) {
            return $user;
        } else {
            throw new PasswordMismatchException();
        }
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function addUser($name, $surname, $email, $password) {
        $password = password_hash($password, CRYPT_SHA512);

        $user = new User();
        $user->setName($name);
        $user->setSurname($surname);
        $user->setEmail($email);
        $user->setPassword($password);

        if ($this->userRepository->existsByEmail($email)) {
            throw new UserAlreadyExistsException();
        }

        $this->userRepository->save($user);
    }
}