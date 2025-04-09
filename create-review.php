<?php
require("db-connect.php");

// Collect user input
$rating = $_POST['rating']; 
$review_text = $_POST['reviewContent']; 


if ($_POST['club'] === '') {
    $_POST['club'] = NULL; 
}

$club = $_POST['club']; 

$imdb = $_POST['imdb']; 

date_default_timezone_set('America/New_York');
$date = date('Y-m-d h:i:s', time());



// Connect to the database
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    //Create the new review
    $stmt = $conn->prepare("INSERT INTO review (user_id, imdb, watch_date, rating, content, in_club) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_SESSION["user_id"], $imdb, $date, $rating, $review_text, $club);


    if ($stmt->execute()) {
        echo "Success";
        header("Location: ./movie.php?ID=" . $imdb);
        exit();

    } else {
        echo "Error: " . $stmt->error;
        header("Location: ./error.html");

    }


    $stmt->close();
    $conn->close();


} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
    header("Location: ./../error.html");

}
?>
