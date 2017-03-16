<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 15/3/2017
 * Time: 12:01 PM
 */

/**
 * CHOOSE WHICH DATABASE TO USE:
 *  MySQL: 'mysql';
 *  MongoDB: 'mongodb';
 */
$use_database = 'mongodb';

try {
    switch ($use_database) {
        case 'mysql':
            define('DB_HOST', 'localhost');
            define('DB_NAME', 'guest');
            define('DB_USER', 'root');
            define('DB_PASS', 'root');
            include_once 'MySqlDatabase.php';
            $db = new \GE\Person\EmployeeServiceMySQL();
            break;
        case 'mongodb':
            define('DB_HOST', 'localhost');
            define('TABLE_USER', "guest.employees");
            include_once 'MongoDatabase.php';
            $db = new \GE\Person\EmployeeServiceMongo();
            break;
        default:
            throw new Exception("MUST DEFINE A VALID DATABASE");
    }
}catch(Exception $e){
    echo $e->getMessage();
    die();
}