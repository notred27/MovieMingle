<?php
// This file fetches reviews from the database and generates HTML for each review.

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

// Write the SQL query to fetch all review rows
$sql = "SELECT * FROM review ORDER BY watch_date DESC";

// Execute the query and get the result
$reviews = $conn->query($sql);


// <div class="movieReview">
// <img src="rosso.jpg" alt="Movie Poster">
// <div>
//     <h4>Porco Rosso</h4>
//     <div>
//         <img src="me.png" alt="User icon">
//         <h4><a href = "">Notred27</a></h4>
//     </div>

//     <span>
//         <span class="fa fa-star checked"></span>
//         <span class="fa fa-star checked"></span>
//         <span class="fa fa-star checked"></span>
//         <span class="fa fa-star checked"></span>
//         <span class="fa fa-star fa-star-half-alt checked"></span>

//         (Dec 12, 2024)
//     </span>

//     <p>
//         I really liked this movie that blah blah lorem ipsum I really liked this movie that blah blah lorem ipsum I really liked this movie that blah blah lorem ipsum 
//     </p>

//     <a href = "">Continue reading ></a>
// </div>
// </div>

if ($reviews->num_rows > 0) {
    // Loop through each movie and generate the HTML
    while ($review = $reviews->fetch_assoc()) {
        // Generate HTML for each movie
        


        $sql = "SELECT title, movie_poster FROM movie WHERE imdb = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $review["imdb"]);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $html = '<div class="movieReview">';
      
        $html .= '<a href = "movie.php?ID='. $review["imdb"] .'"><img src="' . $result["movie_poster"] . '" alt="Movie Poster"></a>';

        $html .= '<div class="reviewGrid">';

        // // Check if we got a result
        // if ($result->num_rows > 0) {
        //     // Fetch the row and get the user's name
        //     $row = $result->fetch_assoc();
            $html .= '<h4>' . $result['title'] . '</h4>';

        // } else {
        //     // Movie title could not be found...
        //     $html .= '<h4>' . $review["imdb"] . '</h4>';
        // }
        $html .= '<div class="profileRow" ><img class = "profileImg" src="me.png" alt="User icon">';


        
        $sql = "SELECT display_name FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $review["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();

    
        $row = $result->fetch_assoc();
        $html .= '<a href ="userpage.php?USER=' . $review["user_id"] . '">' . $row['display_name'] . '</a>';

        


        // Generate stars for review
        $rating = (int)$review["rating"]; //$movie["rating"]; // Assume rating is an integer from 1 to 5

        $html .= '<span style="justify-self:end;">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating / 2)) {
                $html .= '<span class="fa fa-star checked" ></span>';

            } else if ($i == ceil($rating / 2) && $rating % 2 != 0) {
                $html .= '<span class="fa fa-star fa-star-half-alt checked"></span>';

            } else {
                $html .= '<span class="fa fa-star"></span>';
            }
        }

        $html .= '</span></div>';


        $html .= '<p>' . $review["content"] .'</p>';

        $ds = new DateTime($review["watch_date"]);

        $html .= '<h6>Watched ' . $ds->format('M d, Y') . '</h6></div></div>';

        echo $html; // Output HTML
    }
} else {
    echo '<p>No movies found in the database.</p>';
}

?>