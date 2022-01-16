<?php
require_once '../vendor/autoload.php';
require_once 'services/userService.class.php';
use \RedBeanPHP\R as R;
class userController extends userService
{
    public function indexGET()
    {
        // $picture = $_SESSION['picture'];
        echo $this->twigLoader()->render('userProfile.html.twig', [
            'username' => $_SESSION['username'],
            'picture' => !empty($_SESSION['picture']) ? print("<img src='/images/profilePictures/$picture' />") : 'Upload a picture at edit',
            'edit' => !empty($_SESSION['id']) ? 'edit' : 'Login first at /user/login',
            'bio' => !empty($_SESSION['bio']) ? $_SESSION['bio']: 'Add a bio at edit'
        ]);
    }

    public function loginGET()
    {
        ?><a href="/home/index">home</a>
        <form method="post">
            Name: <input type="text" name="name" required>
            Password: <input type="text" type="password" name="password" required>
            <input type="submit" name="logging">
        </form>
        <a href="createUser">Nog geen account?</a><?php
    }
    public function loginPOST()
    {
        $row = R::getRow(
            "SELECT * FROM users WHERE username = :username AND wachtwoord = :wachtwoord", [
                ':username' => $_POST['name'],
                ':wachtwoord' => hash('sha256', $_POST['password'])
            ]
        );
        if (!empty($row)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $_POST['name'];
            $_SESSION['token'] = hash('sha256', uniqid());
            // $_SESSION['picture'] = $row['picture'];
            // $_SESSION['bio'] = $row['bio'];

            $token = R::load('sessions', $row['id']);
            $token->token = $_SESSION['token'];
            R::store($token);
            $this->redirectTo('home');
        } else {
            $this->redirectToLogin();
        }
    }

    public function logoutGET()
    {
        $user = R::load('sessions', $_SESSION['id']);
        $user->token = NULL;
        R::store($user);
        session_destroy();
        $this->redirectToLogin();
    }

    public function createUserGET()
    {
        ?> <a href="/user/login">login</a>
        <h1> Create a new account: </h1>
        <form action="user/createUser" method="post">
            Name: <input name="name">
            Password: <input type="password" name="password">
            <input type="submit" value="submit">
        </form> <?php
    }
    public function createUserPOST()
    {
        $user = R::dispence('users');
        $user->username = $_POST['name'];
        $user->password = hash('sha256', $_POST['password']);
        $user->bio && $user->picture = '';
        R::store($user);
        $this->redirectToLogin();
    }

    public function editProfileGET()
    {
        $id = $_SESSION['id'];
        $user = R::getRow("SELECT * FROM users WHERE id = '$id'");
        echo $this->twigLoader()->render('userProfileEdit.html.twig', [
            'picture' => $user['profilePic'],
            'name' => $user['username'],
            'bio' => $user['bio']
        ]);
    }
    public function editProfilePOST()
    {
        $userId = $_SESSION['id'];
        $name = $_POST['username'];
        $bio = $_POST['bio'];
        $picture = basename($_FILES["file"]["name"]);
        $imgFolder = '/src/images/profilePictures/'.$picture;

        R::exec("UPDATE users SET profilePic = '$picture', username = '$name', bio = '$bio' WHERE id = '$userId'");
        move_uploaded_file($_FILES["file"]["tmp_name"], $imgFolder);
        $this->redirectTo('user');
    }
}