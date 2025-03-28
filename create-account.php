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
$user_email = $_POST['email']; 
$user_name = $_POST['username']; 
$user_password = $_POST['password']; 

// Connect to the database
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $sql = "SELECT COUNT(*) as email_count FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['email_count'] > 0) {
        // If email already exists, show an error message
        echo "This email is already associated with an account. Please use a different email.";
        header("Location: create-account.html?email=in_use");
    } else {


        // Get the total number of users to generate a unique ID
        $sql = "SELECT COUNT(*) AS total_users FROM user";
        $res = $conn->query($sql);

        if ($res) {
            // Fetch result
            $row = $res->fetch_assoc();
            $num_users = $row['total_users'];

            // Generate a new user_id (Example: U001, U002, etc.)
            $user_id = "U" . str_pad($num_users + 1, 5, '0', STR_PAD_LEFT);

            // Hash the password for security
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

            // Create the current date the account was created
            $join_date = date("Y-m-d");

            // Insert the new user
            $stmt = $conn->prepare("INSERT INTO user (user_id, display_name, password_hash, email, date_joined) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $user_id, $user_name, $hashed_password, $user_email, $join_date);


            if ($stmt->execute()) {
                echo "New user created with ID: $user_id";

                header("Location: landing.php?success=account_created");
        
                exit();
            } else {
                echo "Error: " . $stmt->error;
                header("Location: error.html");

            }


            $stmt->close();
        } else {
            echo "Error executing query: " . $conn->error;
            header("Location: error.html");

        }

        $conn->close();

    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
