<?php
require_once '../vendor/autoload.php';
use \RedBeanPHP\R as R;
class dataBase
{
    public function newDatabase()
    {
        $nuke = R::nuke('book', 'publisher', 'author');

        $book = R::dispense('book');
        $book->title = "Harry Potter";
        $book->author = 'Oos';
        $book->publisher = 'Haytam';
        $id = R::store($book);

        $book = R::dispense('book');
        $book->
        setAttr('title', 'Lord of the Rings')->
        setAttr('author', 'Oos')->
        setAttr('publisher', 'Haytam');
        $id = R::store($book);

        $publisher = R::dispense('publisher');
        $publisher->name = "Haytam";
        $id = R::store($publisher);

        $author = R::dispense('author');
        $author->name = "Oos";
        $id = R::store($author);
    }
    public function createBook($titel, $aut, $pub)
    {
        $book = R::dispense('book');
        $book->title = $titel;
        $book->author = $aut;
        $book->publisher = $pub;
        $id = R::store($book);
    }
    public function createPub()
    {
        $publisher = R::dispense('publisher');
        $publisher->name = $_POST['naam'];
        $id = R::store($publisher);
    }
    public function createAut()
    {
        $author = R::dispense('author');
        $author->name = $_POST['naam'];
        $id = R::store($author);
    }
}