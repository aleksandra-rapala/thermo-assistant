<?php
class UserAlreadyExistsException extends Exception {
    public function __construct() {
        parent::__construct();
    }
}
