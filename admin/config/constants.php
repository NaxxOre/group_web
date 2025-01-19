<?php
session_start();
define("ROOT_URL", "http://localhost/PHP-MySQL-Blog-Website-with-Admin-Panel/");
define('DB_HOST', 'localhost');
define('DB_USER', 'ato');
define('DB_PASS', 'ato');
define('DB_NAME', 'blog');
if (!isset($_SESSION['user-id'])) {
    header("location: " . ROOT_URL . "logout.php");
    //destroy all sessions and redirect user to login page
    session_destroy();
    die();
    header("location: " . ROOT_URL . "signin.php");
}