<?php
require_once '../vendor/autoload.php';
use \RedBeanPHP\R as R;
class bookController
{
    public function indexGET()
    {
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);

        // $this->newDatabase();
        $books = R::findAll('book');
        echo $twig->render('bookIndex.html.twig', ['books' => $books]); 
    }

    public function newDatabase()
    {
        R::nuke('book', 'publisher', 'author');

        $book = R::dispense('book');
        $book->title = "Harry Potter";
        $book->author = 'Oos';
        $book->publisher = 'Haytam';
        R::store($book);

        $book = R::dispense('book');
        $book->setAttr('title', 'Lord of the Rings')->
        setAttr('author', 'Oos')->
        setAttr('publisher', 'Haytam');
        R::store($book);

        $publisher = R::dispense('publisher');
        $publisher->name = "Haytam";
        R::store($publisher);

        $author = R::dispense('author');
        $author->name = "Oos";
        R::store($author);
    }
}