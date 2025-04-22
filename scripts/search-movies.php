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


// Construct query
$sql = "SELECT movie.*, AVG(review.rating) AS avg_rating 
        FROM movie
        LEFT JOIN review ON movie.imdb = review.imdb WHERE 1=1";
$params = [];
$types = '';


//  movie name
if ($movie_name) {
    $sql .= " AND title LIKE ?";
    $types .= 's';
    $params[] = "%$movie_name%";
}

// release year
if ($release_year) {
    $sql .= " AND CAST(release_year AS CHAR) LIKE ?";
    $types .= 's';
    $params[] = "%$release_year%";
}

$sql .= " GROUP BY movie.imdb $order;";

$stmt = $conn->prepare($sql);


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