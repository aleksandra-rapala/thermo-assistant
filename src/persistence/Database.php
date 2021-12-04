<?php
interface Database {
    public function execute($query, ...$parameters);
    public function executeAndFetchFirst($query, ...$parameters);
    public function executeAndFetchAll($query, ...$parameters);
}
