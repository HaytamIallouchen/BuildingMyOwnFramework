<?php
use \RedBeanPHP\R as R;
require_once '../vendor/autoload.php';
class authorController extends userService
{
    public function indexGET()
    {
        $author = R::getAll('SELECT * FROM author');
        echo $this->twigLoader()->render('authorIndex.html.twig', [
            'authors' => print_r(json_encode($author))
        ]); 
    }
    public function idGET()
    {
        $route = explode("/", $_SERVER['REQUEST_URI']);
        $author = json_encode(R::getRow('SELECT * FROM author WHERE id = :id', [':id' => $route[3]]));
        return print_r($author) . print('<a href="/author/index">back</a>');
    }
    public function addGET()
    {
        ?> <a href="/author/index">back</a>
        <form action="/author/add" method="post">
            Name: <input name="name">
            <button type="submit" value="submit">Add</button>
        </form> <?php
    }
    public function addPOST()
    {
        $author = R::dispense('author');
        $author->name = $_POST['name'];
        R::store($author);
        $this->redirectTo('author');
    }
}

