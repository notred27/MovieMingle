<?php

require("./../db-connect.php");

$poll_id = $_GET['poll_id'];
$opt_id = $_GET['opt_id'];

$sql = "UPDATE poll_vote SET option_id = ? WHERE user_id = ? AND poll_id = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss",   $opt_id, $_SESSION["user_id"], $poll_id);


if (!$stmt) {
    die("Query preparation failed: " . $conn->error); 
}

$stmt->execute();


?>

