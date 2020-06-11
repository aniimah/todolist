<?php
include_once('./server/Auth.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $auth = new Auth();

  $auth->register();
}

require_once("header.php");
?>
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
<?php include_once("errors.php"); ?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 mx-auto px-md-4">
  <form class="form-signin  bg-c-white" action="" method="post">
    <div class="text-center mb-4">
      <h1 class="h3 mb-3 font-weight-normal">To-do App</h1>
      <p>Fill the form below to sign-up! </p>
    </div>

    <div class="form-label-group">
      <input type="text" id="inputUsername" class="form-control" name="username" placeholder="Username" required autofocus>
      <label for="inputUsername">Username</label>
    </div>

    <div class="form-label-group">
      <input type="text" id="inputFullname" class="form-control" name="name" placeholder="Full name" required>
      <label for="inputFullname">Full name</label>
    </div>

    <div class="form-label-group">
      <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email address" required>
      <label for="inputEmail">Email address</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
      <label for="inputPassword">Password</label>
    </div>

    <div class="form-label-group">
      <input type="password" id="inputConfirm" class="form-control" name="password_1" placeholder="Confirm password" required>
      <label for="inputConfirm">Confirm password</label>
    </div>

    <div class="checkbox mb-3">
      <label>

      </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Register</button>
    <p class="mt-2 mb-1 text-muted text-center">Creating the account you agree to our <a href="#">Terms and Conditions</a>.</p>

    <p class="mt-3 mb-3 text-muted text-center">Not a new user? <a href="login.php">Log in</a></p>
  </form>
</main>
<?php
require_once("footer.php")
?>