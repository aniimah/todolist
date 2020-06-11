<?php
include_once('Server.php');

class Todo
{

    private $username;
    private $userId;
    private $server;
    public $db;

    function __construct()
    {
        $this->server = new Server();
        $this->db = $this->server->db;
        $this->username = $_SESSION['username'];

        $query = "SELECT * FROM User WHERE Username='$this->username' LIMIT 1";
        $results = $this->db->query($query);
        $user = $results->fetch_assoc();

        if ($user) {
            $this->userId = $user['userId'];
        } else {
            Server::setError("A service error occured.");
            header('location: login.php');
        }
    }

    function getTodoList($order_by = null)
    {
        $todoList = array();
        $query = null;

        if (!$order_by) {
            $query = "SELECT * FROM Todo WHERE userId='$this->userId'";
        } else {
            $query = "SELECT * FROM Todo WHERE userId='$this->userId' ORDER BY '$order_by'";
        }
        $results = $this->db->query($query);

        if ($results->num_rows > 0) {

            while ($todo = $results->fetch_assoc()) {
                array_push($todoList, $todo);
            }
        }


        return $todoList;
    }

    function getTodayList()
    {
        $todoList = array();
        $date = date("Y-m-d");


        $query = "SELECT * FROM Todo WHERE userId='$this->userId' AND due_date='$date' AND completed=0";

        $results = $this->db->query($query);

        if ($results->num_rows > 0) {

            while ($todo = $results->fetch_assoc()) {
                array_push($todoList, $todo);
            }
        }


        return $todoList;
    }

    function getCompleted()
    {
        $todoList = array();

        $query = "SELECT * FROM Todo WHERE userId='$this->userId' AND completed=1";

        $results = $this->db->query($query);

        if ($results->num_rows > 0) {

            while ($todo = $results->fetch_assoc()) {
                array_push($todoList, $todo);
            }
        }


        return $todoList;
    }

    function getCategoryTask($category)
    {
        $todoList = array();

        $query = "SELECT * FROM Todo WHERE userId='$this->userId' AND category='$category' AND completed=0";

        $results = $this->db->query($query);

        if ($results->num_rows > 0) {

            while ($todo = $results->fetch_assoc()) {
                array_push($todoList, $todo);
            }
        }


        return $todoList;
    }

    function getCategoryList()
    {
        $groups = array();

        $query = "SELECT category FROM Todo WHERE userId='$this->userId'";

        $results = $this->db->query($query);

        if ($results->num_rows > 0) {

            while ($todo = $results->fetch_assoc()) {
                array_push($groups, $todo);
            }
        }


        return $groups;
    }

    function createTodoItem()
    {
        if (isset($_POST['addTask'])) {
            $title = mysqli_real_escape_string($this->db, $_POST['title']);
            $task = mysqli_real_escape_string($this->db, $_POST['task']);
            $category = mysqli_real_escape_string($this->db, $_POST['category']);
            $due = mysqli_real_escape_string($this->db, $_POST['date']);

            $query = "INSERT INTO Todo (title, task, category, completed, date_created, due_date, userId)
             VALUES ('$title', '$task', '$category', false, now(), '$due', '$this->userId')";
            $result = $this->db->query($query);

            if ($result !== TRUE) {
                Server::setError("Failed to create task");
            } else {
                return $this->getTodoList();
            }
        }
    }

    function updateTodoItem($todoId, $task = null, $category = null, $completed = null, $due = null)
    {
        $due_date = strtotime($due) ? $due : null;
        $columns = array(
            "task=" . $task ? $task : null,
            "category" . $category ? $category : null,
            "completed" . $completed ? $completed : null,
            "due_date" . $due_date ? $due_date : null
        );
        $bind = array();

        foreach ($columns as $column) {
            if ($column != null) {
                array_push($bind, $column);
            }
        }

        $cols = implode(", ", $columns) ? count($columns) > 0 : null;

        if ($cols) {
            $query = "UPDATE Todo SET $cols WHERE todoId='$todoId'";
            $result = $this->db->query($query);

            if ($result !== TRUE) {
                Server::setError("Failed to Update task");
            } else {
                return $this->getTodoList();
            }
        } else {
            return $this->getTodoList();
        }
    }

    function setAsDoneItem()
    {
        $done = mysqli_real_escape_string($this->db, $_GET['done']);
        $todoId = mysqli_real_escape_string($this->db, $_GET['id']);

        if ($done === 'yes') {
            $query = "UPDATE Todo SET completed=1 WHERE todoId='$todoId'";;
            $result = $this->db->query($query);

            if ($result !== TRUE) {
                Server::setError("Failed to update status");
                
            } else {
                return $this->getTodoList();
            }
        }
    }

    function deleteTodoItem()
    {
        $todoId = mysqli_real_escape_string($this->db, $_GET['delete']);
        $query = "DELETE FROM Todo WHERE todoId='$todoId'";
        $result = $this->db->query($query);

        if ($result !== TRUE) {
            Server::setError("Failed to delete task");
        } else {
            return $this->getTodoList();
        }
    }
}
