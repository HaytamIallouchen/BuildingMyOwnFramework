<?php
require_once '../vendor/autoload.php';
use \RedBeanPHP\R as R;
class bookController
{
    public function indexGET()
    {
        // $this->newDatabase();
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $books = R::findAll('book');
        echo $twig->render('bookIndex.html.twig', ['books' => $books]); 
    }
}