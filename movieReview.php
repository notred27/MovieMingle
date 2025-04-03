<?php
// This file fetches reviews from the database and generates HTML for each review.


// <div class="movieReview">
// <img src="rosso.jpg" alt="Movie Poster">
// <div>
//     <div>
//         <img src="me.png" alt="User icon">
//         <h4><a href = "">Notred27</a></h4>
//     </div>

//     <h4>Porco Rosso</h4>
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



    function create_movie_review($review, $conn) {    
        $sql = "SELECT title, movie_poster FROM movie WHERE imdb = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $review["imdb"]);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $html = '<div class="movieReview">';
      
        // $html .= '<a href = "movie.php?ID='. $review["imdb"] .'"><img src="' . $result["movie_poster"] . '" alt="Movie Poster"></a>';

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


    

?>