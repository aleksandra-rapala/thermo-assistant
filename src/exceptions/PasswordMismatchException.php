<?php
class PasswordMismatchException extends Exception {
    public function __construct() {
        parent::__construct();
    }
}