<?php

require("./../db-connect.php");

$sort = $_GET['sort'] ?? 'recent';

switch ($sort) {
    case 'recent':
        $order = "ORDER BY release_year DESC";
        break;
    case 'oldest':
        $order = "ORDER BY release_year ASC";
        break;

    case 'az':
        $order = "ORDER BY title ASC";
        break;

    case 'za':
        $order = "ORDER BY title DESC";
        break;
        
    case 'highest':
        $order = "ORDER BY avg_rating DESC";
        break;
    case 'lowest':
        $order = "ORDER BY avg_rating ASC";
        break;
  default:
    $order = "ORDER BY watch_date DESC";
}




$movie_name = isset($_GET['movie_name']) ? $_GET['movie_name'] : '';
$release_year = isset($_GET['release_year']) ? $_GET['release_year'] : '';
$genres = isset($_GET['genres']) ? $_GET['genres'] : '';

// Construct query
$sql = "SELECT movie.*, AVG(review.rating) AS avg_rating 
        FROM movie
        LEFT JOIN review ON movie.imdb = review.imdb";
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

$sql .= " GROUP BY movie.imdb $order;";

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
$result =  $stmt->get_result();

include('./../components/movieBanner.php');


if ($result->num_rows > 0){
    while ($movie = $result->fetch_assoc()) {
        create_movie_html($movie, $conn);
    }
}




?>