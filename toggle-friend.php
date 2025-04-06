<?php
session_start(); // Start session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "mreidy3"; 
$password = "x-YeHnaY";   
$dbname = "mreidy3_1";


$friend_id = $_GET['friend_id']; 

// Connect to the database
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    //Create the new review
    $stmt = $conn->prepare("SELECT COUNT(*) as c FROM friends WHERE user_id = ? and friend_id = ?");
    $stmt->bind_param("ss", $_SESSION["user_id"], $friend_id);


    if ($stmt->execute()) {


        if($stmt->get_result()->fetch_assoc()["c"] == 0) {
            // Currently not following, so add follow record

            date_default_timezone_set('America/New_York');
            $date = date('Y-m-d', time());

            $stmt = $conn->prepare("INSERT into friends values (?, ?, ?)");
            $stmt->bind_param("sss", $_SESSION["user_id"], $friend_id, $date);
            $stmt->execute();
            echo 'Following';

        } else {
            $stmt = $conn->prepare("DELETE FROM friends WHERE user_id = ? AND friend_id = ?");
            $stmt->bind_param("ss", $_SESSION["user_id"], $friend_id);
            $stmt->execute();
            echo 'Follow';

        }

        // header("Location: ./userpage.php?USER=" . $friend_id);
        exit();

    } else {
        echo "Error: " . $stmt->error;
        // header("Location: ./error.html");

    }


    $stmt->close();
    $conn->close();


} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
    header("Location: ./../error.html");

}
?>
