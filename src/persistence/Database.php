<?php
interface Database {
    public function execute($query, ...$parameters);
}