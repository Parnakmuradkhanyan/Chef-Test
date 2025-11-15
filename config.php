<?php
$host = 'localhost'; 
$db = 'chef_assist';
$user = 'root'; 
$pass = 'root123';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection error" . $conn->connect_error);
}
?>