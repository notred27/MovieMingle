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


include('isFriend.php');


$target_user_id = htmlspecialchars($_GET["USER"]);

$sql = "SELECT * from user where user_id = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $target_user_id);
$stmt->execute();
$target_user = $stmt->get_result()->fetch_assoc();


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

    <?php 
        echo $target_user["display_name"];

        if($target_user["user_id"] != $_SESSION["user_id"]) {

            $sql = "SELECT COUNT(*) AS is_friend FROM friends WHERE user_id = ? and friend_id = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $_SESSION["user_id"], $target_user_id);
            $stmt->execute();
            $f = $stmt->get_result()->fetch_assoc();
           

            if ($f["is_friend"] == 0) {
                echo '<form id="toggle_friend" action="toggle-friend.php" method="post"><input type = "hidden" value = "'. $target_user_id .'" id = "friend_id" name = "friend_id"><button> Follow </button></form>';
                
                
            } else {
                echo '<form id="toggle_friend" action="toggle-friend.php" method="post"><input type = "hidden" value = "'. $target_user_id .'" id = "friend_id" name = "friend_id"><button> Unfollow </button></form>';

            }
        } else {
            echo "<button> Change Name </button>";

        }

    ?>
        

    </main>
    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>
</body>

</html>