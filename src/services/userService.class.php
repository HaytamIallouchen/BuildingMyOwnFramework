<?php
require_once '../vendor/autoload.php';
use \RedBeanPHP\R as R;
class UserService
{
    public function validateLoggedIn()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /user/login");
        }
    }
}