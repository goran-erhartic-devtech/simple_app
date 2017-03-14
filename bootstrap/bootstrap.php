<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 8:38 AM
 */

//Include composer autoload.
require_once __DIR__ . '/../vendor/autoload.php';
require_once(__DIR__ . '/../database/MySqlDatabase.php');

//initiate connection to database and enable functions from service class
$db = new \GE\Person\EmployeeServiceMySQL();

//Twig template engine initialization
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'auto_reload' => true,
    'cache' => '/cache'
));