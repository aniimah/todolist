<?php
session_start();
class Server
{
    public $db;
    public static $errors = array();

    function __construct()
    {
        $host = '127.0.0.1'; #change
        $user = 'root'; #change
        $pass = 'root'; #change
        $schema = 'Todolist';
        $this->db = new mysqli($host, $user, $pass, $schema);

        if ($this->db->connect_error) {
            Server::setError($this->errors, "An error occured, failed to connect to database");
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    static function setError($error)
    {
        array_push(Server::$errors, $error);
    }

    function getErrors()
    {
        return Server::$errors;
    }
}
