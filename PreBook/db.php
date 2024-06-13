<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'insight'; // Replace with your database name

$db_conn = new mysqli($host, $user, $password, $database);

if ($db_conn->connect_error) {
    die('Connection failed: ' . $db_conn->connect_error);
}
?>
