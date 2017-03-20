<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 8:38 AM
 */

//Include composer autoload.
require_once __DIR__ . '/../vendor/autoload.php';
require_once(__DIR__ . '/../database/config.php');

//Twig template engine initialization
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'auto_reload' => true,
    'cache' => '/cache'
));

$container = new \Pimple\Container();
$container['logger'] = function(){
    return new \SimpleLogger\File($_SERVER['DOCUMENT_ROOT'] . '\log\log.log');
};

try {
    switch (DATABASE) {
        case 'mysql':
            define('DB_NAME', 'guest');
            include_once './../database/MySqlDatabase.php';
            $container['db'] = function ($container) {
                return new \GE\Person\EmployeeServiceMySQL($container);
            };
            break;
        case 'mongodb':
            include_once './../database/MongoDatabase.php';
            $container['db'] = function ($container) {
                return new \GE\Person\EmployeeServiceMongo($container);
            };
            break;
        default:
            $container['logger']->error("Failed to connect to database!!!");
            throw new Exception("MUST DEFINE A VALID DATABASE");
    }
}catch(Exception $e){
    echo $e->getMessage();
    die();
}