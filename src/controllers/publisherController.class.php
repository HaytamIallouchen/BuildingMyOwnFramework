<?php
use \RedBeanPHP\R as R;
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
class publisherController extends userService
{
    public function indexGET()
    {
        $this->validateLoggedIn();
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $publishers = R::findAll('publisher');
        echo $twig->render('publisherIndex.html.twig', ['publishers' => $publishers]);
    }
    public function addGET()
    {
        $this->validateLoggedIn();
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('publisherAdd.html.twig', []);
    }
    public function addPOST()
    {
        $this->validateLoggedIn();
        $pub = R::dispense('publisher');
        $pub->name = $_POST['naam'];
        $id = R::store($pub);
        die;
    }
}