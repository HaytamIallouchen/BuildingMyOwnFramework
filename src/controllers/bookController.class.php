<?php
require_once '../vendor/autoload.php';
use \RedBeanPHP\R as R;
class bookController extends userService
{
    public function indexGET()
    {
        echo $this->twigLoader()->render('bookIndex.html.twig', ['books' => R::findAll('book')]); 
    }
}