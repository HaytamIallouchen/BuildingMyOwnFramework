<?php
require_once '../vendor/autoload.php';
require_once 'services/UserService.class.php';
use \RedBeanPHP\R as R;
class todoController
{
    public function indexGET()
    {
        $validate = new UserService();
        $validate->validateLoggedIn();

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
            header('location: http://localhost/todo/index');
        }
    }

    public function storetodoPOST()
    {
        R::findOrCreate('todo', [
            'todo' => $_POST['task'],
            'user_id' => $_SESSION['id'],
            'status' => 'not done'
        ]);
        header('location: http://localhost/todo/index');
        die();
    }
    
    private function status($status, $postid)
    {
        $todo = R::load('todo', $postid);
        $todo->status = $status;
        R::store($todo);
        header('location: http://localhost/todo/index');
        die;
    }

    private function rowswapup($postid)
    {
        $user = $_SESSION['id'];
        $upperone = R::find( "todo", "WHERE id < '$postid' AND user_id = '$user' ORDER BY id DESC LIMIT 1");
        foreach ($upperone as $row) {
            $new = $row['id'];
            $tempId = (2000000 + $todoId % 147483647);
            R::exec("UPDATE todo SET id = $tempId WHERE id = $postid");
            R::exec("UPDATE todo SET id = $postid WHERE id = $new");
            R::exec("UPDATE todo SET id = $new WHERE id = $tempId");
        }
        header('location: http://localhost/todo/index');
        die;
    }

    private function rowswapdown($postid)
    {
        $user = $_SESSION['id'];
        $upperone = R::find("todo", "WHERE id > '$postid' AND user_id = '$user' ORDER BY id ASC LIMIT 1");
        foreach ($upperone as $row) {
            $new = $row['id'];
            $tempId = (2000000 + $todoId % 147483647);
            R::exec("UPDATE todo SET id = $tempId WHERE id = $postid");
            R::exec("UPDATE todo SET id = $postid WHERE id = $new");
            R::exec("UPDATE todo SET id = $new WHERE id = $tempId");
        }
        header('location: http://localhost/todo/index');
        die;
    }

    private function delete($postid)
    {
        R::hunt( 'todo', ' id IN ( '. $postid .' ) ', $postid );
        header('location: http://localhost/todo/index');
        die;
    }

    private function editpage($postid)
    {
        $user = $_SESSION['id'];
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, []);
        $todo = R::find("todo", "WHERE id = '$postid' AND user_id = '$user'");
        echo $twig->render('todoEdit.html.twig', ['todo' => $todo]);
    }

    public function editPOST()
    {
        $postid = $_POST['wijzig'];
        $edit = $_POST['edittodo'];
        $user = $_SESSION['id'];
        R::exec("UPDATE todo SET todo = '$edit' WHERE id = '$postid' AND user_id = '$user'");

        header('location: http://localhost/todo/index');
        die;
    }
}
?>