<?php
class UserRepository {
    public function findUserByEmail($email) {
        $password = password_hash("example password", CRYPT_SHA256);

        $user = new User();
        $user->setUuid(123);
        $user->setName("Name");
        $user->setSurname("");
        $user->setPassword($password);
        $user->setEmail("email@example.com");

        return $user;
    }
}