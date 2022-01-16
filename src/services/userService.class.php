<?php
require_once '../vendor/autoload.php';
use \RedBeanPHP\R as R;
class userService
{
    public function redirectTo($controller)
    {
        header('Location: /' . $controller. '/index');
        die;
    }
    public function redirectToLogin()
    {
        header('Location: /user/login');
        die;
    }
    public function validateLoggedIn()
    {
        if (empty($_SESSION['token'])) {
            $this->redirectTo('login');
        }
    }
    public function twigLoader()
    {
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        return $twig;
    }
}