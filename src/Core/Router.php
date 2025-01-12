<?php

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/owners', 'Pamutlabor\Module\Owner\OwnerListController@process');
    $r->addRoute(['GET', 'POST'], '/owner[/{id}]', 'Pamutlabor\Module\Owner\OwnerFormController@process');
    $r->addRoute(['GET', 'POST'], '/', 'Pamutlabor\Module\TaskManager\TaskManagerListController@process');
    $r->addRoute(['GET', 'POST'], '/project[/{id:\d+}]', 'Pamutlabor\Module\TaskManager\TaskManagerFormController@process');
    $r->addRoute(['POST'], '/project/delete', 'Pamutlabor\Module\TaskManager\TaskManagerDeleteController@process');    
});

// Fetch method and URI
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
        http_response_code(404);
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo "405 Method Not Allowed. Allowed: " . implode(', ', $allowedMethods);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $vars = array_merge(['variable' => $vars], ['get' => $_GET], ['post' => $_POST]);

        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$class, $method] = explode('@', $handler);
            if (class_exists($class) && method_exists($class, $method)) {
                $instance = new $class();
                echo call_user_func_array([$instance, $method], $vars);
            } else {
                http_response_code(500);
                echo "Handler not found.";
            }
        } else {
            http_response_code(500);
            echo "Invalid handler format.";
        }
        break;
}