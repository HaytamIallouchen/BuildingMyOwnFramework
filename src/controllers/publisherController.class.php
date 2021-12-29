<?php
use \RedBeanPHP\R as R;
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
class publisherController
{
    public function indexGET()
    {
        $validate = new UserService();
        $validate->validateLoggedIn();

        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $publishers = R::findAll('publisher');
        echo $twig->render('publisherIndex.html.twig', ['publishers' => $publishers]);
    }
    public function addGET()
    {
        $validate = new UserService();
        $validate->validateLoggedIn();

        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('addPublisher.html.twig', []);
    }
    public function addPOST()
    {
        $validate = new UserService();
        $validate->validateLoggedIn();

        $pub = R::dispense('publisher');
        $pub->name = $_POST['naam'];
        $id = R::store($pub);
        header('location /publisher/index');
        die;
    }
}