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
 * @return \Database\Database
 */
$container['db_instance'] = function(){
    return \Database\Database::getInstance();
};

try {
    switch (DATABASE) {
        case 'mysql':
            $container['employee_service'] = function ($container) {
                return new \GE\Person\EmployeeServiceMySQL($container['db_instance'], $container);
            };
            break;
        case 'mongodb':
            $container['employee_service'] = function ($container) {
                return new \GE\Person\EmployeeServiceMongo($container['db_instance'], $container);
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