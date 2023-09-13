<?php 

    class Dbh {
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $dbName = "justpost_db";
        protected static $mysqli;

        protected function dbconnect() {
            self::$mysqli = new mysqli($this->host,$this->username,$this->password,$this->dbName);
            return self::$mysqli;
        }
    }