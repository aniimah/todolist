<?php
include_once('./server/Auth.php');
include_once('./server/Todo.php');

if (!isset($_SESSION['username'])) {
  header('location: login.php');
}
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header("location: login.php");
}

$todo = new Todo();
$todoList = array();

if (isset($_GET['today'])) {
  $todoList = $todo->getTodayList();
} else if (isset($_GET['completed'])) {
  $todoList = $todo->getCompleted();
} else if (isset($_GET['category'])) {
  $category = $_GET['category'];
  $todoList = $todo->getCategoryTask($category);
} else if (isset($_GET['delete'])) {
  $todoList = $todo->deleteTodoItem();
} else if (isset($_GET['done'])) {
  $todoList = $todo->setAsDoneItem();
} else {
  $todoList = $todo->getTodoList();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $todoList = $todo->createTodoItem();
}

require_once("header.php");
?>
<?php include_once("errors.php"); ?>
<nav class="navbar navbar-dark sticky-top bg-dark bg-light  flex-md-nowrap shadow">
  <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="index.php">To-do list</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="index.php?logout='1'">Sign out</a>
    </li>
  </ul>
</nav>


<div class="container-fluid">
  <div class="row">

    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">
              <span data-feather="home"></span>
              All tasks <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?today=1">
              <span data-feather="file"></span>
              Today's tasks
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?completed=1">
              <span data-feather="shopping-cart"></span>
              Completed
            </a>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Todo Categories</span>

        </h6>
        <ul class="nav flex-column mb-2">
          <?php
          $groupes = $todo->getCategoryList();
          $categories = array();
          if ($groupes) :
          ?>
            <?php foreach ($groupes as $key => $group) : ?>
              <?php
              if (!in_array($group['category'], $categories)) {
                array_push($categories, $group['category']);
              }
              ?>
            <?php endforeach ?>

            <?php foreach ($categories as $key => $group) : ?>
              <li class="nav-item">
                <a class="nav-link" href="index.php?category=<?php echo $group; ?>">
                  <span data-feather="file-text"></span>
                  <?php echo $group; ?>
                </a>
              </li>
            <?php endforeach ?>
          <?php endif ?>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 bg-c-white">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addTask">Add task</a>
        </div>
      </div>
    </main>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 mt-5">


      <?php if (count($todoList) > 0) : ?>
        <div class="accordion bg-c-white" id="todoAccordion">
          <?php foreach ($todoList as $item) : ?>
            <div class="card">
              <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" id="heading<?php echo $item['todoId']; ?>">
                <h2 class="mb-0">
                  <button class="btn btn-block text-left title" type="button" data-toggle="collapse" data-target="#collapse<?php echo $item['todoId']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $item['todoId']; ?>">
                    <?php echo $item['title']; ?>
                  </button>
                </h2>
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                  <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <p class="due_date"><span>Due on: </span><?php echo $item['due_date']; ?></p>
                  </div>
                  <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <?php $comp = $item['completed'];
                    if ($comp) : ?>
                      <a type="button" class="btn btn-outline-success disabled" aria-disabled="true">Done</a>
                    <?php else : ?>
                      <a class="btn btn-secondary" href="index.php?done=yes&id=<?php echo $item['todoId']; ?>" aria-disabled="true">Mark completed</a>
                    <?php endif ?>
                  </div>
                  <div class="btn-group" role="group" aria-label="Third group">
                    <a class="btn btn-danger" href="index.php?delete=<?php echo $item['todoId']; ?>">Delete</a>
                  </div>
                </div>
              </div>

              <div id="collapse<?php echo $item['todoId']; ?>" class="collapse show" aria-labelledby="heading<?php echo $item['todoId']; ?>" data-parent="#todoAccordion">
                <div class="card-body">
                  <?php echo $item['task']; ?>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      <?php endif ?>

      <?php if (count($todoList) < 1) : ?>
        <div class="card text-center mt-5 p-5">
          <div class="card-body p-5">
            <h3 class="card-title">You have no tasks here!</h3>
            
            <p class="card-text">Click below to add new task.</p>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addTask">Add task</a>
          </div>
        </div>
      <?php endif ?>
    </main>

  </div>
</div>

<div class="modal fade" id="addTask" tabindex="-1" role="dialog" aria-labelledby="addTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="taskForm" action="" method="post">
          <div class="form-group">
            <label for="inputTitle">Title</label>
            <input type="text" class="form-control" id="inputTitle" name="title" placeholder="New task">
          </div>
          <div class="form-group">
            <label for="inputTextarea">Task description</label>
            <textarea class="form-control" id="inputTextarea" name="task" placeholder="Enter task details here ..." required></textarea>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputCategory">Category</label>
              <input type="text" class="form-control" name="category" id="inputCategory">
            </div>
            <div class="form-group col-md-6">
              <label for="inputDate">Due date</label>
              <input type="date" class="form-control" id="inputDate" name="date" required>
            </div>
          </div>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="taskForm" name="addTask" class="btn btn-primary">Add task</button>
      </div>

    </div>

  </div>
</div>
<?php
require_once("footer.php")
?>