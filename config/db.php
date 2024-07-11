<?php

class Connection {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "cac_php";
    private static mixed $con = null;

    private function __construct()
    {
        try{
            self::$con = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database, $this->user, $this->password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true
            ));
        }catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }

    public static function getConnection(): mixed {
        if (self::$con == null) {
            new Connection();
        }
        return self::$con;
    }
}