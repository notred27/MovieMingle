<?php

require("./../db-connect.php");

$poll_id = $_GET['poll_id'];
$opt_id = $_GET['opt_id'];



$sql = "INSERT INTO poll_vote VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss",  $_SESSION["user_id"], $poll_id, $opt_id);


if (!$stmt) {
    die("Query preparation failed: " . $conn->error); 
}

$stmt->execute();


?>