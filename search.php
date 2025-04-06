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


    <form id = "searchForm">
        <input type="text" id="movie_name" name="movie_name" placeholder="Movie Name" value="<?= htmlspecialchars($_GET['movie_name'] ?? '') ?>">
        <br />

        <input type="text" id="release_year" name="release_year" placeholder="Release Year" >
        <br />

        <input type="text" id="genres" name="genres" placeholder="Genres">
        <br />

        <input type="text" id="key_words" name="key_words" placeholder="Key words" value="<?= htmlspecialchars($_GET['key_words'] ?? '') ?>">
        <br />

        <button type="submit">Search</button>
    </form>


    <h2>Search Results </h2>
    <!-- (<?php echo $result->num_rows; ?>) -->
    <label for="sort-option">Sort by:</label>
    <select name = "sort-option" id = "sort-option">
        <option value="recent">Recent</option>
        <option value="oldest">Oldest</option>
        <option value="highest">Highest Rating</option>
        <option value="lowest">Lowest Rating</option>
    </select>


    <div id = "results" style = "display:flex; flex-direction:row; flex-wrap:wrap; gap:10px; justify-content:space-evenly;">


    <!-- <?php if ($result->num_rows > 0): ?>
            <?php while ($movie = $result->fetch_assoc()): ?>
                <?php  create_movie_html($movie, $conn);  ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results found for your search criteria.</p>
        <?php endif; ?> -->

    </div>
        

    </main>
    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>


    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            fetch('search-movies.php?' + params.toString())
                .then(response => response.text())
                .then(data => {
                    document.getElementById('results').innerHTML = "<div>Success</div>";
                })
                .catch(error => {
                    console.error('Search error:', error);
                    document.getElementById('results').innerHTML = "<p>Error loading search results.</p>";
                });
        });



        // function sortReviews() {
        //     const sortOption = this.value;

        //     fetch(`load_rows.php?sort=${sortOption}&id=${<?php echo json_encode($target_user_id); ?>}`)
        //         .then(response => response.text())
        //         .then(data => {
        //         document.getElementById('row-container').innerHTML = data;
        //         })
        //         .catch(error => console.error('Error fetching sorted data:', error));
        // }
    </script>
</body>

</html>