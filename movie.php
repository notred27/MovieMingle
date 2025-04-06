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

include('movieReview.php');


// Get url variable
$target_imdb = htmlspecialchars($_GET["ID"]);


// Get info about this movie
$sql = "SELECT * FROM movie WHERE imdb = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $target_imdb);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();


// Get reviews you've written for this movie
$sql = "SELECT * FROM review WHERE user_id = ? and imdb = ? ORDER BY watch_date DESC;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_SESSION["user_id"], $target_imdb);
$stmt->execute();
$user_reviews = $stmt->get_result();


// Get reviews from friends
// $sql = "SELECT * 
//         FROM review 
//         JOIN ((SELECT user_id as id 
//                FROM friends 
//                WHERE friend_id = ?) 
//                     UNION 
//               (SELECT friend_id as id 
//                FROM friends 
//                WHERE user_id = ?)) as F 
//             on F.id = review.user_id WHERE imdb = ? ORDER BY watch_date DESC;";

$sql = "SELECT * 
        FROM review 
        JOIN (SELECT friend_id as id 
               FROM friends 
               WHERE user_id = ?) as F 
            on F.id = review.user_id WHERE imdb = ? ORDER BY watch_date DESC;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_SESSION["user_id"], $target_imdb);
$stmt->execute();
$friend_reviews = $stmt->get_result();




// Get reviews that aren't from you or your friends
// $sql = "SELECT * FROM review WHERE imdb = ? and user_id != ? and user_id NOT IN (SELECT user_id as id FROM friends WHERE friend_id = ? UNION SELECT friend_id as id FROM friends WHERE user_id = ?) ORDER BY watch_date DESC;";

$sql = "SELECT * FROM review WHERE imdb = ? and user_id != ? and user_id NOT IN (SELECT friend_id FROM friends WHERE user_id = ?) ORDER BY watch_date DESC;";


$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $target_imdb, $_SESSION["user_id"], $_SESSION["user_id"]);
$stmt->execute();
$other_reviews = $stmt->get_result();

// Get the name of clubs you belong to
$sql = "SELECT club_name FROM member_of WHERE user_id = ?;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["user_id"]);
$stmt->execute();
$user_clubs = $stmt->get_result();

// Get the average rating for this movie
$sql = "SELECT AVG(rating) as rating FROM review WHERE imdb = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $target_imdb);
$stmt->execute();
$avg_rating = $stmt->get_result()->fetch_assoc();

$sql = "SELECT AVG(rating) as rating FROM review WHERE imdb = ? and user_id IN (SELECT friend_id FROM friends WHERE user_id = ?);";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $target_imdb, $_SESSION["user_id"]);
$stmt->execute();
$avg_friend_rating = $stmt->get_result()->fetch_assoc();


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Mingle</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="moviepage.css">
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <link rel="stylesheet" href="movieReviewPreview.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php include('web-header.php'); ?>

    <main>

<!-- Create a form to add new reviews -->
    <div id = "reviewMenu" style = "display:none; width:60vw; height:70vh; background-color:#282828; position:absolute; top:15vh; left:20vw; z-index:200;">
        <form  action="create-review.php" method="post" >
            <input type="number" min="0" max = "10" id="rating" name="rating" placeholder="Rating" required>
            <br/>
            <input type="text" id="reviewContent" name="reviewContent" placeholder="Review">
            <br/>

            <select name="club" id="club">
                <option value="">No Club</option>

                <?php
                    if ($user_clubs->num_rows > 0) { 
                        while ($club = $user_clubs->fetch_assoc()) {
                            echo '<option value="'. $club["club_name"] .'">' . $club["club_name"] . '</option>';

                        }   
                    }
                ?>

            </select>

            <br/>

            <input type="hidden"  id = "imdb" name="imdb" value="<?php echo $target_imdb; ?>"> </input>

            <button type="submit">Create Review</button>

            <button onclick = "toggleReviewMenu()">Cancel</button>
        </form>
    </div>




    <div class = "movieContainer" >
        <?php
            // Format the runtime
            $runtime = $movie["runtime"];
            $timeParts = explode(":", $runtime);
            if (count($timeParts) == 3) {
                $hours = (int)$timeParts[0];
                $minutes = (int)$timeParts[1];
                $r = $hours . "h " . $minutes . "m";
            } else {
                $r = $runtime;
            }

            // // Generate stars (example: assuming a rating integer value)
            // $rating = $avg_rating["rating"] / 2; 
            // $ratingHTML = '<span>';
            // for ($i = 1; $i <= 5; $i++) {
            //     if ($i <= $rating) {
            //         $ratingHTML .= '<span class="fa fa-star checked"></span>';
            //     } else {
            //         $ratingHTML .= '<span class="fa fa-star"></span>';
            //     }
            // }
            // $ratingHTML .= '(' . $avg_rating["rating"] . ')</span>';

            
            // $friend_rating = $avg_friend_rating["rating"] / 2; 
            // $ratingHTML2 = '<span>';
            // for ($i = 1; $i <= 5; $i++) {
            //     if ($i <= $rating) {
            //         $ratingHTML2 .= '<span class="fa fa-star checked"></span>';
            //     } else {
            //         $ratingHTML2 .= '<span class="fa fa-star"></span>';
            //     }
            // }
            // $ratingHTML2 .= '(' . $avg_friend_rating["rating"] . ')</span>';

            if (!function_exists('generate_stars')) {
                include("stars.php");
            }
             

            echo '<img id = "moviePoster" alt = "movieBanner" src="' . $movie["movie_poster"] . '" > 
            <h1 id = "movieTitle">' . $movie["title"] . '<span> (' . $movie["release_year"] . ')</span></h1> 
            <h4 id = "movieRuntime">' . $r . '</h4> 
            <p id = "movieDesc">' . $movie["movie_desc"] . '</p> 
            <h3 id = "overallRating">Overall Rating:</h3> <span id = "overallStars">' .generate_stars($avg_rating["rating"]) . '(' . $avg_rating["rating"] . ')</span> 
            <h3 id = "followingRating">Rating Among Friends:</h3> <span id = "followingStars">' .generate_stars($avg_friend_rating["rating"]) . '(' . $avg_friend_rating["rating"] . ')</span>';
        ?>

        <button id = "reviewBtn" onclick = "toggleReviewMenu()">Create A New Review!</button>
    </div>



    <h3>You may also like</h3>

    <h3> Your reviews </h3>

    <?php 
        if ($user_reviews->num_rows > 0) { 
            while ($review = $user_reviews->fetch_assoc()) {
                create_movie_review($review, $conn);
            }
        } else {
            echo "You haven''t reviewed this movie yet!";
        }
    ?>


    <h3> Reviews from people you follow </h3>

    <?php 

        if ($friend_reviews->num_rows > 0) { 
            while ($review = $friend_reviews->fetch_assoc()) {
                create_movie_review($review, $conn);
            }
        } else {
            echo "No reviews from Friends found :(";
        }

    ?>


    <h3> Other reviews</h3>

    <?php 
        if ($other_reviews->num_rows > 0) { 
            while ($review = $other_reviews->fetch_assoc()) {
                create_movie_review($review, $conn);
            }
        } else {
            echo "No other reviews found :(";
        }
    ?>
        

    </main>
    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>

    <script>
        function toggleReviewMenu() {
            var div = document.getElementById("reviewMenu");
            if (div.style.display === "none") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
    </script>
</body>

</html>