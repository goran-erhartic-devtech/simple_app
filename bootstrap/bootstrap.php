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

//SimpleLogger initialization through Pimple
$container['logger'] = function(){
    return new \SimpleLogger\File($_SERVER['DOCUMENT_ROOT'] . '\log\log.log');
};

//Twig template engine initialization through Pimple
$container['twig_loader'] = function () {
    return new Twig_Loader_Filesystem('templates');
};

$container['twig'] = function ($c) {
    return new Twig_Environment($c['twig_loader'], array(
        'auto_reload' => true,
        'cache' => '/cache'
    ));
};

try {
    switch (DATABASE) {
        case 'mysql':
            define('DB_NAME', 'guest');
            $container['db'] = function ($container) {
                return new \GE\Person\EmployeeServiceMySQL($container);
            };
            break;
        case 'mongodb':
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