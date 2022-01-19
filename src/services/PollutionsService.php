<?php
class PollutionsService {
    private $pollutionsRepository;

    public function __construct($pollutionsRepository) {
        $this->pollutionsRepository = $pollutionsRepository;
    }

    public function calculateForBuilding($building) {
        echo "<pre>";
        var_dump($building);
        echo "</pre>";
    }
}
