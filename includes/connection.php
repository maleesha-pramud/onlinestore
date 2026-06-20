<?php

class Database {
    public static $connection;

    public static function setUpConnection() {
        if (!isset(Database::$connection)) {
            Database::$connection = new mysqli('host.docker.internal', 'root', 'maleesha@2005', 'online_store', 3306);
        }
    }

    public static function search($query) {
        Database::setUpConnection();
        $rs = Database::$connection->query($query);
        return $rs;
    }

    public static function iud($query) { 
        Database::setUpConnection();
        Database::$connection->query($query);
    }

    public static function escape($text) {
        Database::setUpConnection();
        return Database::$connection->real_escape_string($text);
    }

    public static function getInsertedId() {
        return Database::$connection->insert_id;
    }

}

require_once dirname(__FILE__) . '/validation.php';
