<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 20/3/2017
 * Time: 3:51 PM
 */

namespace Database;

interface DatabaseInterface
{
    static function getInstance();
    public function getConnection();
}