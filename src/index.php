<?php
session_start();
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
$validate = new UserService();
use \RedBeanPHP\R as R;
R::setup(
    'mysql:host=localhost;
    dbname=building_framwork',
    'root',
    ''
);
$linkExplode = explode("/", $_SERVER['REQUEST_URI']);
if (!$linkExplode[1]) {
    $linkExplode[1] = 'Home';
    $linkExplode[2] = 'index';
}

$controller = empty($linkExplode[1]) ? 'Home' : $linkExplode[1];
$method = empty($linkExplode[2]) ? 'index' : $linkExplode[2];

if (file_exists('./Controllers/' . $controller . 'Controller.class.php')) {
    require('./Controllers/' . $controller . 'Controller.class.php');
    $classname = $controller . 'Controller';
    $class = new $classname();
    $methodRequest = $method . $_SERVER['REQUEST_METHOD'];
    
    if (method_exists($class, $methodRequest)) {
        $class->$methodRequest();

    } elseif ($controller == 'author' && is_numeric($linkExplode[2])) {
        $data = R::getRow('SELECT * FROM author WHERE id = :id', [':id' => $linkExplode[2]]);
        print_r(json_encode($data));

    } else {
        http_response_code(404);
        die;
    }
} else {
    http_response_code(404);
    die;
}
