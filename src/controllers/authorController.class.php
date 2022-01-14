<?php
use \RedBeanPHP\R as R;
require_once '../vendor/autoload.php';
class authorController
{
    public function indexGET()
    {
        $authors = json_encode(R::getAll('SELECT * FROM author'));
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        echo $twig->render('authorIndex.html.twig', ['authors' => print_r($authors)]);
    }
    public function idGET()
    {
        $requestedRoute = explode("/", $_SERVER['REQUEST_URI']);
        $id = $requestedRoute[3];
        $author = json_encode(R::getRow('SELECT * FROM author WHERE id = :id', [':id' => $id]));
        return print_r($author);
    }
}

