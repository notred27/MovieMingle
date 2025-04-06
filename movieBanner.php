<?php
// This file formats movie data from the database into HTML
    if (!function_exists('generate_stars')) {
        include("stars.php");
    }
 
    function create_movie_html($movie, $conn) {
        $sql = "SELECT AVG(rating) as rating FROM review WHERE imdb = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $movie["imdb"]);
        $stmt->execute();
        $avg_rating = $stmt->get_result()->fetch_assoc();


        // Generate HTML for each movie
        $movieHtml = '<div class="movieBanner" onclick="location.href=\'./movie.php?ID='. $movie["imdb"] .'\'">';
        $movieHtml .= '<img src="' . $movie["movie_poster"] . '" alt="Movie Poster">';
        $movieHtml .= '<div>';
        $movieHtml .= '<h4>' . $movie["title"] . '</h4>';
        
        // Get the year from release date
        $movieHtml .= '<h6>' . $movie["release_year"] . ', ';
        
        // Format the runtime
        $runtime = $movie["runtime"];
        $timeParts = explode(":", $runtime);
        if (count($timeParts) == 3) {
            $hours = (int)$timeParts[0];
            $minutes = (int)$timeParts[1];
            $movieHtml .= $hours . "h " . $minutes . "m";
        } else {
            $movieHtml .= $runtime;
        }
        $movieHtml .= '</h6>';
        
        
        $movieHtml .= generate_stars($avg_rating["rating"]);
        
        // Movie description
        $movieHtml .= '<p>' . $movie["movie_desc"] . '</p>';
        // $movieHtml .= 'See more >';
        $movieHtml .= '</div></div>';
        
        echo $movieHtml; // Output the HTML for the movie
    }

?>
