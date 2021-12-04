<?php
interface DatabaseFactory {
    public function create($host, $port, $database, $username, $password);
}
