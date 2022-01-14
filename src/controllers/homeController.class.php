<?php
require_once '../vendor/autoload.php';
require_once 'services/userService.class.php';
use \RedBeanPHP\R as R;
class homeController
{
    public function indexGET()
    {
        // if (!$_SESSION['id']) {$_SESSION['id'] = '';}
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('homeIndex.html.twig', [
            'username' => !empty($_SESSION['id']) ? $_SESSION['username'] : 'user',
            'profile' => !empty($_SESSION['id']) ? '/user/index' : '/user/login',
            'todo' => !empty($_SESSION['id']) ? '/todo/index' : '/user/login',
            'log' => !empty($_SESSION['id']) ? 'logout' : 'login'
        ]);
    }
}