<?php
include_once('Server.php');

class Auth
{
    public $name;
    public $username;
    public $email;
    private $password;
    private $confirm_password;
    private $db;
    private $server;

    function __construct()
    {
        $this->server = new Server();
        $this->db = $this->server->db;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->name = mysqli_real_escape_string($this->db, $_POST['name']);
            $this->username = mysqli_real_escape_string($this->db, $_POST['username']);
            $this->email = mysqli_real_escape_string($this->db, $_POST['email']);
            $this->password = mysqli_real_escape_string($this->db, $_POST['password']);
            $this->confirm_password = mysqli_real_escape_string($this->db, $_POST['password_1']);

            if (empty($this->username)) {
                Server::setError("Username is required");
            }
            if (empty($this->password)) {
                Server::setError("Password is required");
            }
        }
    }

    function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['register'])) {

                if (empty($this->email)) {
                    Server::setError("Email is required");
                }
                if ($this->password != $this->confirm_password) {
                    Server::setError("The two passwords do not match");
                }

                $check_user = "SELECT * FROM User WHERE Username='$this->username' OR email='$this->email' LIMIT 1";
                $result = $this->db->query($check_user);
                $user = $result->fetch_assoc();

                if ($user) {
                    if ($user['Username'] ===  $this->username) {
                        Server::setError("Username already exists");
                    }

                    if ($user['email'] === $this->email) {
                        Server::setError("email already exists");
                    }
                }

                $password = md5($this->password);
                $query = "INSERT INTO User (Username, Fullname, email, date_commenced, password) 
                      VALUES('$this->username', '$this->name', '$this->email', now(), '$password')";
                $write_result = $this->db->query($query);

                if ($write_result !== TRUE) {
                    Server::setError("Failed to register");
                    Server::setError($this->db->error);
                }

                if (count($this->server->getErrors()) == 0) {
                    $_SESSION['username'] =  $this->username;
                    $_SESSION['name'] =  $this->name;
                    $_SESSION['status'] = "You are now logged in";
                    header('location: index.php');
                }
            }
        } else {
            Server::setError("Not allowed");
        }
    }

    function login()
    {
            if (isset($_POST['login'])) {

                if (count($this->server->getErrors()) == 0) {
                    $password = md5($this->password);
                    $query = "SELECT * FROM User WHERE Username='$this->username' AND password='$password'";
                    $results = $this->db->query($query);
                    if ($results->num_rows == 1) {
                        $_SESSION['username'] = $this->username;
                        $_SESSION['name'] =  $this->name;
                        $_SESSION['status'] = "You are now logged in";
                        header('location: index.php');
                    } else {
                        Server::setError("Wrong username/password");
                    }
                }
            }
        
    }
}
