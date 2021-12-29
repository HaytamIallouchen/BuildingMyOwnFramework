<?php
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
use \RedBeanPHP\R as R;
class userController
{
    public function indexGET()
    {
        $validate = new UserService();
        $validate->validateLoggedIn();
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('userIndex.html.twig', ['name' => $_SESSION['username']]);
    }
    public function loginGET()
    {
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('loginIndex.html.twig');
        // $this->createUser();
    }
    public function loginPOST()
    {
        $row = R::getRow(
            "SELECT * FROM users
            WHERE username = :username
            AND wachtwoord = :wachtwoord", [
                ':username' => $_POST['naam'],
                ':wachtwoord' => hash('sha256', $_POST['pw'])
            ]
        );
        if (!empty($row)) {
            $_SESSION['username'] = $_POST['naam'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['token'] = hash('sha256', uniqid());

            $token = R::load('sessions', $row['id']);
            $token->token = $_SESSION['token'];
            $id = R::store($token);
            header("Location: http://localhost/home/index");
            die;
        } else {
            header('location: http://localhost/user/login');
            die;
        }
    }

    public function logoutGET()
    {
        $user = R::load('sessions', $_SESSION['id']);
        $user->token = '';
        R::store($user);
        session_destroy();
        
        header('location: http://localhost/user/login');
        exit('Logged out. cya');
    }

    private function createUser()
    {
        $user = R::dispence('users');
        $user->username = 'haytam';
        $user->password = hash('sha256', 'haytam');
        $id = R::store($user);
        die;
    }
}