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
    define('DB_USER', 'admin'); //MySQL -> root ; MongoDB -> admin
    define('DB_PASS', 'admin'); //MySQL -> root ; MongoDB -> admin
    define('DB_HOST', 'localhost');
    switch ($use_database) {
        case 'mysql':
            define('DB_NAME', 'guest');
            include_once 'MySqlDatabase.php';
            $db = new \GE\Person\EmployeeServiceMySQL();
            break;
        case 'mongodb':
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