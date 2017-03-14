<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 13/3/2017
 * Time: 12:34 PM
 */
class MySqlDatabase
{
    public $connection;

    /**
     * MySqlDatabase constructor.
     * @param $connection
     */
    public function __construct($connection = null)
    {
        if ($this->connection === null) {
            try{
                $this->connection = new PDO("mysql:host=localhost;dbname=guest", 'root', 'root');
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
    }
}