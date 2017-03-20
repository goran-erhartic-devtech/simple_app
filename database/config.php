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
define('DATABASE', 'mysql');

define('DB_USER', 'root'); //MySQL -> root ; MongoDB -> admin
define('DB_PASS', 'root'); //MySQL -> root ; MongoDB -> admin
define('DB_HOST', 'localhost');
define('TABLE_USER', "guest.employees");