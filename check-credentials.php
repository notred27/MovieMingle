<?php
session_start(); // Start session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Database connection details
$servername = "localhost";
$username = "mreidy3"; 
$password = "x-YeHnaY";   
$dbname = "mreidy3_1";

// Collect user input

$user_name = $_POST['username']; 
$user_password = $_POST['password']; 

echo $user_name;
echo $user_password;



// Connect to the database
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username exists, and retrieve hash
    $sql = "SELECT * FROM user WHERE display_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    

    // Check if a row was returned
    if ($row = $result->fetch_assoc()) {
        // Access the password hash from the row
        $stored_hash = $row['password_hash'];

        // You can now compare the stored hash with the entered password
        echo "Stored password hash: " . $stored_hash;

        if (password_verify($user_password, $stored_hash)) {

            // Correct credentials, update session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['display_name'] = $user_name;
            $_SESSION['user_id'] = $row['user_id'];


            header("Location: home.php");

        } else {
            // Invalid login credentials
            header("Location: landing.php?error=invalid_pwd");
        }


    } else {
        // User does not exist
        header("Location: landing.php?error=account_not_found");
    }



    // // Check if the entered password matches the stored hash
    // if (password_verify($entered_password, $stored_hash)) {
    //     echo "Password is correct!";
    // } else {
    //     echo "Invalid password!";
    // }



    $conn->close();

} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
