<?php
class PollutionsRepository {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }
}
