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
}