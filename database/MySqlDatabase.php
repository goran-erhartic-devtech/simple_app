<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 13/3/2017
 * Time: 12:34 PM
 */

namespace Database;

class MySqlDatabase extends AbstractDatabase implements DatabaseInterface
{

    /**
     * Get an instance of the Database
     * @return MySqlDatabase
     */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get mysql pdo connection
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * MySqlDatabase constructor.
     */
    private function __construct()
    {
        try {
            $this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *Prevent duplication of connection
     */
    private function __clone()
    {
    }
}
