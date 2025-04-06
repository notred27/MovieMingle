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

$movie_name = isset($_GET['movie_name']) ? $_GET['movie_name'] : '';
$release_year = isset($_GET['release_year']) ? $_GET['release_year'] : '';
$genres = isset($_GET['genres']) ? $_GET['genres'] : '';

// Construct query
$sql = "SELECT movie.* FROM movie";
$params = [];
$types = '';

if ($genres) {
    $genresArr = array_map('trim', explode(",", $genres)); // Split and trim genres

    // Add JOIN to ensure proper filtering
    $sql .= " JOIN movie_genres ON movie.imdb = movie_genres.imdb 
              WHERE movie_genres.genre RLIKE ?
              GROUP BY movie.imdb 
              HAVING COUNT(DISTINCT movie_genres.genre) = ?";

    $types .= 'si'; // 's' for each genre, 'i' for COUNT check
    $params = array_merge([implode("|", $genresArr)], [count($genresArr)]);
} else {
    $sql .= " WHERE 1=1"; // Ensures other conditions can be appended properly
}

// Add conditions for movie name
if ($movie_name) {
    $sql .= " AND title LIKE ?";
    $types .= 's';
    $params[] = "%$movie_name%";
}

// Add conditions for release year
if ($release_year) {
    $sql .= " AND CAST(release_year AS CHAR) LIKE ?";
    $types .= 's';
    $params[] = "%$release_year%";
}

// Prepare statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error); // Debugging step
}

// Bind parameters
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
return $stmt->get_result();




?>