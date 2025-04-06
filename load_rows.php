<?php
// load_rows.php

include('movieReviewPreview.php');


$sort = $_GET['sort'] ?? 'recent';
$target_id = $_GET['id'];

switch ($sort) {
  case 'recent':
    $order = "ORDER BY watch_date DESC";
    break;
  case 'oldest':
    $order = "ORDER BY watch_date ASC";
    break;
  case 'highest':
    $order = "ORDER BY rating DESC";
    break;
  case 'lowest':
    $order = "ORDER BY rating ASC";
    break;
  default:
    $order = "ORDER BY watch_date DESC";
}
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: landing.php');
    exit;
}


// Database connection details
$servername = "localhost";
$username = "mreidy3"; 
$password = "x-YeHnaY";   
$dbname = "mreidy3_1";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM review WHERE user_id = ? $order;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $target_id);
$stmt->execute();
$user_reviews = $stmt->get_result();



while ($review = $user_reviews->fetch_assoc()) {
//   echo "<div class='row'>{$row['imdb']} - {$row['watch_date']}</div>";
  create_movie_review_preview($review, $conn);
}
?>
