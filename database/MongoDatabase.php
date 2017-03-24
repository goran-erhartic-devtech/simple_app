<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 14/3/2017
 * Time: 3:25 PM
 */

namespace Database;

class MongoDatabase extends AbstractDatabase implements DatabaseInterface
{

    /**
     * Get an instance of the Database
     * @return MongoDatabase
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get MongoDB connection
     * @return \MongoDB\Driver\Manager
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * MongoDatabase constructor.
     */
    private function __construct()
    {
        try {
            $this->db = new \MongoDB\Driver\Manager("mongodb://" . DB_USER . ":" . DB_PASS . "@" . DB_HOST . ":27017");
        } catch (\MongoConnectionException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Prevent duplication of connection
     */
    private function __clone()
    {
    }
}
