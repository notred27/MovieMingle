<?php

session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: landing.php');
    exit;
}

// Database connection 
$servername = "localhost";
$username = "mreidy3"; 
$password = "x-YeHnaY";   
$dbname = "mreidy3_1";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>