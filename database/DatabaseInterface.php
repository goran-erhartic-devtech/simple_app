<?php

/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 20/3/2017
 * Time: 3:51 PM
 */
interface DatabaseInterface
{
    static function getInstance();
    public function getConnection();
}