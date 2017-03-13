<?php
/**
 * Created by PhpStorm.
 * User: goran.erhartic
 * Date: 23/2/2017
 * Time: 8:50 AM
 */
require_once(__DIR__ . '/../bootstrap/bootstrap.php');

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/employees', function () {
        $aa = new \GE\Person\Employee();
        $aa->setName("Igor")->setAge(33)->setDepartment('PHP')->setIsActive(true)->setProject("Onboarding");
        return $aa;
    });
    $r->addRoute('GET', '/employee/{id:\d+}', function ($vars) {
        echo "This will return Employee with this id: " . $vars['id'];
    });
    $r->addRoute('GET', '/managers', function () {
        $aa = new \GE\Person\Manager();
        $aa->setName("Goran")->setAge(31)->setProject(array("one", "two", "three"));
        return $aa;
    });
    $r->addRoute('GET', '/manager/{id:\d+}', function ($vars) {
        echo "This will return Manager with this id: " . $vars['id'];
    });
    $r->addRoute('GET', '/', function () {
        echo "WELCOME";
    });
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

        //Twig template engine initialization
        $loader = new Twig_Loader_Filesystem('templates');
        $twig = new Twig_Environment($loader, array(
            'auto_reload' => true,
            'cache' => '/cache'
        ));

        $obj = call_user_func($handler, $vars);
        if (is_file(substr('/templates/' . $_SERVER['REQUEST_URI'], 1) . '.twig')) {
            $template = $twig->load(substr($_SERVER['REQUEST_URI'], 1) . '.twig')->render(array(
                'obj' => $obj
            ));
            echo $template;
        }

        break;
}

