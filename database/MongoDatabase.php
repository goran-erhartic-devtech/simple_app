<?php
//*** MONGODB TO BE ADDED ***

class MongoDatabase
{
    private $db;
    private static $_instance;

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct()
    {
        try {
            $this->db = new MongoDB\Driver\Manager("mongodb://".DB_USER.":".DB_PASS."@" . DB_HOST . ":27017");
        } catch (MongoConnectionException $e) {
            echo $e->getMessage();
        }
    }

    // Prevent duplication of connection
    private function __clone()
    {
    }

    // Get mysql pdo connection
    public function getConnection()
    {
        return $this->db;
    }
}