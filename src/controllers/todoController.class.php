<?php
require_once '../vendor/autoload.php';
require_once 'services/userService.class.php';
use \RedBeanPHP\R as R;
class todoController extends userService
{
    public function indexGET()
    {
        $this->validateLoggedIn();
        $todos = R::getAll('SELECT * FROM todo WHERE user_id = :id', [':id' => $_SESSION['id']]);
        echo $this->twigLoader()->render('todoIndex.html.twig', ['todos' => $todos]);
    }

    public function storetodoPOST()
    {
        R::findOrCreate('todo', [
            'todo' => $_POST['task'],
            'user_id' => $_SESSION['id'],
            'status' => 'not done'
        ]);
        $this->redirectTo('todo');
    }

    public function indexPOST()
    {
        $postid = explode('-', $_POST['todoOption'])[0];
        $function = explode('-', $_POST['todoOption'])[1];
        $optionArg = explode('-', $_POST['todoOption'])[2];
        $this->$function($postid, $optionArg);
    }

    public function status($postid, $status)
    {
        $todo = R::load('todo', $postid);
        $todo->status = $status;
        R::store($todo);
        $this->redirectTo('todo');
    }

    public function rowSwap($postId, $direction)
    {
        $user = $_SESSION['id'];
        if ($direction == 'up') {
            $rowInWay = "WHERE id < $postId AND user_id = $user ORDER BY id DESC LIMIT 1";
        } else {
            $rowInWay = "WHERE id > $postId AND user_id = $user ORDER BY id ASC LIMIT 1";
        }
        $secondTodo = R::find("todo", $rowInWay);
        foreach ($secondTodo as $todo) {
            $new = $todo['id'];
            $tempId = (2000000 + $todoId % 147483647);
            R::exec("UPDATE todo SET id = $tempId WHERE id = $postId");
            R::exec("UPDATE todo SET id = $postId WHERE id = $new");
            R::exec("UPDATE todo SET id = $new WHERE id = $tempId");
        }
        $this->redirectTo('todo');
    }

    public function delete($postId)
    {
        R::hunt( 'todo', ' id IN ( '. $postId .' ) ', $postId );
        $this->redirectTo('todo');
    }

    public function edit($postId)
    {
        $user = $_SESSION['id'];
        $todo = R::find("todo", "WHERE id = $postId AND user_id = $user");
        echo $this->twigLoader()->render('todoEdit.html.twig', ['todo' => $todo]);
    }

    public function editPOST()
    {
        $postId = $_POST['postid'];
        $edit = $_POST['todo'];
        $user = $_SESSION['id'];
        R::exec("UPDATE todo SET todo = '$edit' WHERE id = '$postId' AND user_id = '$user'");
        $this->redirectTo('todo');
    }
}
?>