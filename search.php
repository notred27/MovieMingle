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

// Get parameters
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
$result = $stmt->get_result();



include('movieBanner.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Mingle</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php include('web-header.php'); ?>

    <main>


    <form action="" method="">

        <input type="text" id="movie_name" name="movie_name" placeholder="Movie Name">

        <br />

        <input type="text" id="release_year" name="release_year" placeholder="Release Year" >

        <br />

        <input type="text" id="genres" name="genres" placeholder="Genres">
        <br />

        <input type="text" id="key_words" name="key_words" placeholder="Key words">
        <br />


        <button type="submit">Search</button>

    </form>
    <h2>Search Results (<?php echo $result->num_rows; ?>)</h2>


    <div style = "display:flex; flex-direction:row; flex-wrap:wrap; gap:10px; justify-content:space-evenly;">


    <?php if ($result->num_rows > 0): ?>
            <?php while ($movie = $result->fetch_assoc()): ?>
                <?php  create_movie_html($movie);  ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results found for your search criteria.</p>
        <?php endif; ?>

        </div>
        

    </main>
    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>
</body>

</html>