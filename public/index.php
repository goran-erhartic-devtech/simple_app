<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 8:50 AM
 */
require_once(__DIR__ . '/../bootstrap/bootstrap.php');

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($container) {
//start of Employee CRUD
    //GET ALL
    $r->addRoute('GET', '/employees', function () use ($container) {
        $template = $container['twig']->load('employees.twig')->render(array(
            'obj' => $container['employee_service']->getAll()
        ));
        echo $template;
    });
    //GET ONE BY ID
    $r->addRoute('GET', '/employee/{id:\d+}', function ($vars) use ($container) {
        $template = $container['twig']->load('employee.twig')->render(array(
            'obj' => $container['employee_service']->getOne($vars['id'])
        ));
        echo $template;
    });
    //CREATE
    $r->addRoute('POST', '/employee', function () use ($container) {
        return $container['employee_service']->create($_POST);
    });
    //DELETE
    $r->addRoute('DELETE', '/employee/{id:\d+}', function ($vars) use ($container) {
        return $container['employee_service']->delete($vars['id']);
    });
    //UPDATE
    $r->addRoute('PUT', '/employee/{id:\d+}', function ($vars) use ($container) {
        parse_str(file_get_contents('php://input'), $_PUT);
        return $container['employee_service']->update($vars['id'], $_PUT);
    });
//end of Employee CRUD

//    $r->addRoute('GET', '/managers', function () {
//        $aa = new \GE\Person\Manager();
//        $aa->setName("Goran")->setAge(31)->setProject(array("one", "two", "three"));
//        return $aa;
//    });
//    $r->addRoute('GET', '/manager/{id:\d+}', function ($vars) {
//        echo "This will return Manager with this id: " . $vars['id'];
//    });
//    $r->addRoute('GET', '/', function () {
//        echo "WELCOME";
//    });
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo 'Method not allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        call_user_func($handler, $vars);
        break;
}
