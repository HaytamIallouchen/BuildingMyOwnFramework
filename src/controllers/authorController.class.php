<?php
use \RedBeanPHP\R as R;
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
class authorController
{
    public function indexGET()
    {
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $authors = R::getAll('SELECT * FROM author');
        echo $twig->render('authorIndex.html.twig', ['authors' => json_encode($authors) . PHP_EOL]);
    }
    
    public function addPOST()
    {
        $aut = R::dispense('author');
        $aut->name = $_POST['name'];
        R::store($pub);
        header('location /author/index');
        die;
    }
}

