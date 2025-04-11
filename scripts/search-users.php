<?php

require("./../db-connect.php");

$sort = $_GET['sort'] ?? 'recent';

switch ($sort) {
    case 'recent':
        $order = "ORDER BY date_joined DESC";
        break;
    case 'oldest':
        $order = "ORDER BY date_joined ASC";
        break;

    case 'az':
        $order = "ORDER BY display_name ASC";
        break;

    case 'za':
        $order = "ORDER BY display_name DESC";
        break;
        
  default:
    $order = "ORDER BY display_name ASC";
}


$name = isset($_GET['user_name']) ? '%' .  $_GET['user_name'] . '%' : '%';


$sql = "SELECT * FROM user WHERE display_name LIKE ?  $order;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$results = $stmt->get_result();


if (!$stmt) {
    die("Query preparation failed: " . $conn->error); // Debugging step
}


if (!function_exists('create_user_badge')) {
    include("./../components/userBadge.php");
}


echo '<div style = "display:grid;grid-template-columns:repeat(6, 1fr);width:95vw;">';

if ($results->num_rows > 0){
    while ($result = $results->fetch_assoc()) {
        create_user_badge($result, $conn);
    } 
}

echo '</div>';




?>