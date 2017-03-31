<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 13/3/2017
 * Time: 12:34 PM
 */

namespace Database;

use \MongoDB\Driver\Manager;

class Database extends AbstractDatabase implements DatabaseInterface
{

    /**
     * Get an instance of the Database
     * @return Database
     */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get database connection
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * Database constructor.
     */
    private function __construct()
    {
        if(DATABASE == 'mysql') {
            try {
                $this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
                $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }elseif (DATABASE == 'mongodb'){
            try {
                $this->db = new Manager("mongodb://" . DB_USER . ":" . DB_PASS . "@" . DB_HOST . ":27017");
            } catch (\MongoConnectionException $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     *Prevent duplication of connection
     */
    private function __clone()
    {
    }
}
