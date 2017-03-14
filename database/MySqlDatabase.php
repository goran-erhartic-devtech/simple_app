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
            $this->connection = new PDO("mysql:host=localhost;dbname=guest", 'root', 'root');
        }
    }

}