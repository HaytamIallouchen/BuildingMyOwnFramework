<?php
session_start();
require_once '../vendor/autoload.php';
require_once 'services/userService.class.php';
use \RedBeanPHP\R as R;
R::setup(
    'mysql:host=localhost;
    dbname=building_framwork',
    'root',
    ''
);
$requestedRoute = explode("/", $_SERVER['REQUEST_URI']);
if (!$requestedRoute[1]) {
    $requestedRoute[1] = 'home';
    $requestedRoute[2] = 'index';
}

$controller = empty($requestedRoute[1]) ? 'home' : $requestedRoute[1];
$method = empty($requestedRoute[2]) ? 'index' : $requestedRoute[2];

if (file_exists('./Controllers/' . $controller . 'Controller.class.php')) {
    require('./Controllers/' . $controller . 'Controller.class.php');
    $classname = $controller . 'Controller';
    $class = new $classname();
    $methodRequest = $method . $_SERVER['REQUEST_METHOD'];
    
    if (method_exists($class, $methodRequest)) {
        $class->$methodRequest();
    } else {
        http_response_code(404);
        die;
    }
} else {
    http_response_code(404);
    die;
}