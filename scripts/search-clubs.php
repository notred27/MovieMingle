<?php

require("./../db-connect.php");

$sort = $_GET['sort'] ?? 'recent';

switch ($sort) {

    case 'az':
        $order = "ORDER BY club_name ASC";
        break;

    case 'za':
        $order = "ORDER BY club_name DESC";
        break;
        
  default:
    $order = "ORDER BY club_name ASC";
}


$club_name = isset($_GET['club_name']) ? '%' .  $_GET['club_name'] . '%' : '%';


$sql = "SELECT * FROM club WHERE club_name LIKE ?  $order;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $club_name);
$stmt->execute();
$results = $stmt->get_result();

if (!$stmt) {
    die("Query preparation failed: " . $conn->error); 
}


if (!function_exists('create_club_preview')) {
    include("./../components/clubPreview.php");
}


echo '<div style = "display:grid;grid-template-columns:repeat(4, 1fr);width:95vw;column-gap:5px;">';

if ($results->num_rows > 0){
    while ($result = $results->fetch_assoc()) {
        create_club_preview($result, $conn);
    } 
}

echo '</div>';




?>