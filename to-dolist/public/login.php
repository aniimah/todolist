<?php
include_once('./server/Auth.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $auth = new Auth();

  $auth->login();
}
require_once("header.php");
?>
<?php include_once("errors.php"); ?>
<style>
  body {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
  }
</style>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 mx-auto px-md-4">
  <form class="form-signin  bg-c-white" action="" method="post">

    <div class="text-center mb-4">
      <h1 class="h3 mb-3 font-weight-normal">To-do App</h1>
      <p>Login in to enjoy! </p>
    </div>

    <div class="form-label-group">
      <input type="text" id="inputUsername" class="form-control" name="username" placeholder="Username" required autofocus>
      <label for="inputUsername">Username</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
      <label for="inputPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <label>

      </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Sign in</button>
    <p class="mt-5 mb-3 text-muted text-center">Are You New Here? <a href="register.php">Sign up</a></p>
  </form>
</main>
<?php
require_once("footer.php")
?>