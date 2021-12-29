<?php
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
require_once 'UserController.class.php';
use \RedBeanPHP\R as R;

class homeController
{
    public function indexGET()
    {
        if (!$_SESSION['id']) {$_SESSION['id'] = '';}
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('homeIndex.html.twig', [
            'naam' => is_numeric($_SESSION['id']) ? $_SESSION['username'] : 'user',
            'profile' => is_numeric($_SESSION['id']) ? '/user/index' : '/user/login',
            'todo' => is_numeric($_SESSION['id']) ? '/todo/index' : '/user/login',
            'log' => is_numeric($_SESSION['id']) ? 'logout' : 'login'
        ]);
    }

    public function logoutPOST()
    {
        $user = R::load('sessions', $_SESSION['id']);
        $user->token = '';
        R::store($user);

        session_destroy();
        $validate = new UserService;
        $validate->validateLoggedIn();
        exit('Logged out. cya');
    }
}