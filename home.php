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



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>My First Realistic Web Page</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




</head>

<body>
    <header>
        <h1>Movie Mingle</h1>

        <a href = "landing.php">Logout</a>
    </header>
    <main>


    <h3>Movies in the database:</h3>

        <div class="horizontalContainer"> 
           

        <?php if ($movies->num_rows > 0): ?>
            <?php while ($movie = $movies->fetch_assoc()): ?>

                <div class="movieBanner">
                    <?php
                        echo '<img src="posters/' . $movie["imdb"] . '.jpg" alt="Movie Poster">';
                    ?>
                    <div>
                        <h4><?php echo $movie["title"]; ?></h4>
                        <h6>
                            <?php $date = $movie["release_date"]; 
                                $year = substr($date, 0, 4);
                                echo $year;
                            ?>, 
                        
                        <?php
                            // change format of runtime
                            $runtime = $movie["runtime"];
                            $timeParts = explode(":", $runtime);

                            if (count($timeParts) == 3) {
                                $hours = (int)$timeParts[0];
                                $minutes = (int)$timeParts[1];
                                
                                // Format and display runtime as xh xm
                                echo $hours . "h " . $minutes ."m";
                            } else {

                                echo $runtime;
                            }
                            ?>
                        </h6>

                        <span>
                            <?php

                            // Make this correspond to the avg. rating in the database

                                $review_score = 3.5; // The review score can be a float, e.g., 3.5 stars
                                $full_stars = floor($review_score); // Number of full stars (integer part)
                                $half_star = ($review_score - $full_stars) >= 0.5 ? true : false; // Check if there's a half star
                                $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0); // The remaining empty star

                                // Display full stars
                                for ($i = 0; $i < $full_stars; $i++) {
                                    echo '<span class="fa fa-star checked"></span>';
                                }

                                // Display half star
                                if ($half_star) {
                                    echo '<span class="fa fa-star-half-alt checked"></span>';
                                }

                                // Display empty stars
                                for ($i = 0; $i < $empty_stars; $i++) {
                                    echo '<span class="fa fa-star"></span>';
                                }
                            ?>


                        </span>
                        <p class = "movieBannerText" style="margin: 0px; font-size: small; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-width: 190px;">
                            <?php echo $movie["movie_desc"]; ?>
                        </p>
                        <a href="#">See more ></a>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>No movies found in the database.</p>
        <?php endif; ?>

        </div>



        <h3>Recent reviews from friends:</h3>

        <div class="horizontalContainer"> 
            <div class="movieReview">
                <img src="rosso.jpg" alt="Movie Poster">

                <div>
                    <div>
                        <img src="me.png" alt="User icon">
                        <h4><a href = "">Notred27</a></h4>
                    </div>

                    <h4>Porco Rosso</h4>
                    <span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star fa-star-half-alt checked"></span>

                        (Dec 12, 2024)
                    </span>

                    <p>
                        I really liked this movie that blah blah lorem ipsum I really liked this movie that blah blah lorem ipsum I really liked this movie that blah blah lorem ipsum 
                    </p>

                    <a href = "">Continue reading ></a>
                </div>
            </div>

        </div>


        <h3>New posts in groups you follow:</h3>
        <div class="horizontalContainer">


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



        <h3>Trending Movies:</h3>

        <div class="horizontalContainer">

            <div class="movieBanner">
                <img src="rosso.jpg" alt="Movie Poster">

                <div>
                    <h4>Porco Rosso</h4>
                    <h6>1992, 1h 34m</h6>

                    <span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star fa-star-half-alt checked"></span>
                        <span class="fa fa-star"></span>
                    </span>

                    <p>
                        In 1930s Italy, a veteran World War I pilot is cursed to look like an anthropomorphic pig.
                    </p>

                    <a href="">See more ></a>
                </div>
            </div>

            <div class="movieBanner">
                <img src="pm.jpg" alt="Movie Poster">

                <div>
                    <h4>Princess Mononoke</h4>
                    <h6>1997, 2h 13m</h6>

                    <span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                    </span>

                    <p>
                        While seeking to cure himself of a curse, young warrior Ashitaka stumbles into a conflict...
                    </p>

                    <a href="">See more ></a>
                </div>
            </div>

            <div class="movieBanner">
                <img src="wr.jpg" alt="Movie Poster">

                <div>
                    <h4>The Wind Rises</h4>
                    <h6>2013, 2h 6m</h6>

                    <span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star fa-star-half-alt checked"></span>
                        <span class="fa fa-star"></span>
                    </span>

                    <p>
                        Jiro Horikoshi studies assiduously to fulfill his aim of becoming an aeronautical ...
                    </p>

                    <a href="">See more ></a>
                </div>
            </div>
        </div>


        

    </main>
    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>
</body>

</html>