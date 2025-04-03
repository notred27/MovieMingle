<?php
// This file formats movie data from the database into HTML

    function create_movie_html($movie) {
        // Generate HTML for each movie
        $movieHtml = '<div class="movieBanner">';
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
        
        // Generate stars (example: assuming a rating integer value)
        $rating = 3.5; //$movie["rating"]; // Assume rating is an integer from 1 to 5
        $movieHtml .= '<span>';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $movieHtml .= '<span class="fa fa-star checked"></span>';
            } else {
                $movieHtml .= '<span class="fa fa-star"></span>';
            }
        }
        $movieHtml .= '</span>';
        
        // Movie description
        $movieHtml .= '<p style="margin: 0px; font-size: small; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-width: 190px;">' . $movie["movie_desc"] . '</p>';
        $movieHtml .= '<a href="./movie.php?ID='. $movie["imdb"] .'">See more ></a>';
        $movieHtml .= '</div></div>';
        
        echo $movieHtml; // Output the HTML for the movie
    }

?>
