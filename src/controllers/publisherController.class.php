<?php
use \RedBeanPHP\R as R;
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
class publisherController extends userService
{
    public function indexGET()
    {
        $this->validateLoggedIn();
        $publishers = R::findAll('publisher');
        echo $this->twigLoader()->render('publisherIndex.html.twig', ['publishers' => $publishers]);
    }
    public function addGET()
    {
        $this->validateLoggedIn();
        ?> <a href="/publisher/index">back</a>
        <form method="post">
            Name: <input name="name">
            <input type="submit" value="submit">
        </form> <?php
    }
    public function addPOST()
    {
        $this->validateLoggedIn();
        $publisher = R::dispense('publisher');
        $publisher->name = $_POST['name'];
        R::store($publisher);
        $this->redirectTo('publisher');
    }
}