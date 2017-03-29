<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 8:38 AM
 */

//Include composer autoload.
require_once __DIR__ . '/../vendor/autoload.php';

//Load database configuration
require_once(__DIR__ . '/../database/config.php');

//Instantiate a DI container
$container = new \Pimple\Container();


/**
 * SimpleLogger initialization through Pimple
 * @return \SimpleLogger\File
 */
$container['logger'] = function(){
    return new \SimpleLogger\File($_SERVER['DOCUMENT_ROOT'] . '\log\log.log');
};

/**
 * Twig template engine initialization through Pimple
 * @return Twig_Loader_Filesystem
 */
$container['twig_loader'] = function () {
    return new Twig_Loader_Filesystem('templates');
};

/**
 * @param $c
 * @return Twig_Environment
 */
$container['twig'] = function ($c) {
    return new Twig_Environment($c['twig_loader'], array(
        'auto_reload' => true,
        'cache' => '/cache'
    ));
};

/**
 * Get MySQL Database instance
 * @return \Database\MySqlDatabase
 */
$container['mysql_instance'] = function(){
    return \Database\MySqlDatabase::getInstance();
};

/**
 * Get MongoDB Database instance
 * @return \Database\MongoDatabase
 */
$container['mongodb_instance'] = function(){
    return \Database\MongoDatabase::getInstance();
};

try {
    switch (DATABASE) {
        case 'mysql':
            $container['employee_service'] = function ($container) {
                return new \GE\Person\EmployeeServiceMySQL($container['mysql_instance'], $container);
            };
            break;
        case 'mongodb':
            $container['employee_service'] = function ($container) {
                return new \GE\Person\EmployeeServiceMongo($container['mongodb_instance'], $container);
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