<?php
class HeaterService {
    private $heaterRepository;

    public function __construct($heaterRepository) {
        $this->heaterRepository = $heaterRepository;
    }

    public function findHeatersByBuildingId($buildingId) {
        return $this->heaterRepository->selectAllByBuildingId($buildingId);
    }
}
