<?php
require_once '../vendor/autoload.php';
require_once 'services/userService.class.php';
use \RedBeanPHP\R as R;
class todoController extends userService
{
    public function indexGET()
    {
        $this->validateLoggedIn();
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $todos = R::getAll('SELECT * FROM todo WHERE user_id = :id', [':id' => $_SESSION['id']]);
        echo $twig->render('todoIndex.html.twig', ['todos' => $todos]);
    }

    public function indexPOST()
    {
        if (isset($_POST['down'])) {
            $this->rowswapdown($_POST['down']);
        } else if (isset($_POST['up'])) {
            $this->rowswapup($_POST['up']);
        } else if (isset($_POST['done'])) {
            $this->status('done', $_POST['done']);
        } else if (isset($_POST['notdone'])) {
            $this->status('notdone', $_POST['notdone']);
        } else if (isset($_POST['edit'])) {
            $this->editpage($_POST['edit']);
        } else if (isset($_POST['delete'])) {
            $this->delete($_POST['delete']);
        } else {
            $this->redirectTo('todo');
        }
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
    
    public function status($status, $postid)
    {
        $todo = R::load('todo', $postid);
        $todo->status = $status;
        R::store($todo);
        $this->redirectTo('todo');
    }

    public function rowswapup($postId)
    {
        $user = $_SESSION['id'];
        $upperOne = R::find( "todo", "WHERE id < '$postId' AND user_id = '$user' ORDER BY id DESC LIMIT 1");
        foreach ($upperOne as $row) {
            $new = $row['id'];
            $tempId = (2000000 + $todoId % 147483647);
            R::exec("UPDATE todo SET id = $tempId WHERE id = $postId");
            R::exec("UPDATE todo SET id = $postId WHERE id = $new");
            R::exec("UPDATE todo SET id = $new WHERE id = $tempId");
        }
        $this->redirectTo('todo');
    }

    public function rowswapdown($postId)
    {
        $user = $_SESSION['id'];
        $underOne = R::find("todo", "WHERE id > '$postId' AND user_id = '$user' ORDER BY id ASC LIMIT 1");
        foreach ($underOne as $row) {
            $new = $row['id'];
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

    public function editpage($postId)
    {
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $user = $_SESSION['id'];
        $todo = R::find("todo", "WHERE id = '$postId' AND user_id = '$user'");
        echo $twig->render('todoEdit.html.twig', ['todo' => $todo]);
    }

    public function editPOST()
    {
        $postId = $_POST['wijzig'];
        $edit = $_POST['edittodo'];
        $user = $_SESSION['id'];

        R::exec("UPDATE todo SET todo = '$edit' WHERE id = '$postId' AND user_id = '$user'");
        $this->redirectTo('todo');
    }
}
?>