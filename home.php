<?php
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

// Write the SQL query to fetch all rows from the "movies" table
$sql = "SELECT * FROM movie";

// Execute the query and get the result
$movies = $conn->query($sql);



$sql = "SELECT * FROM review ORDER BY watch_date DESC";

// Execute the query and get the result
$reviews = $conn->query($sql);


include('movieBanner.php');
include('movieReviewPreview.php');

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Mingle</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="movieReviewPreview.css">

    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php include('web-header.php'); ?>
    <main>


        <h2>Movies in the database:</h2>

        <div class="horizontalContainer container"> 

            <?php 
                if ($movies->num_rows > 0) { 
                    while ($movie = $movies->fetch_assoc()) {
                        create_movie_html($movie, $conn); 
                    }
                } else {
                    echo "No movies found :(";
                }
            ?>

        </div>



        <h2>Recent reviews from friends:</h2>
       
        <div class="horizontalContainer container"> 
            

            <?php 
                if ($reviews->num_rows > 0) { 
                    while ($review = $reviews->fetch_assoc()) {
                        create_movie_review_preview($review, $conn); 
                    }
                } else {
                    echo "No movies found :(";
                }
            ?>

        </div>


        <h2>New posts in groups you follow:</h2>
        <div class="horizontalContainer container">


            <div class="movieComment">
                <div>
                    <img src="me.png" alt="User icon">
                    <h4>Username</h4>
                </div>

                <p>
                    blah blah blah blah this is a movie review
                </p>

                <h6>Dec 12, 2024</h6>
            </div>

        </div>



   


        

    </main>
    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>
</body>

</html>