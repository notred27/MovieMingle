<?php
// This file generates HTML for a preview of a review (which contains a movie poster)

function create_movie_review_preview($review, $conn) {
    // Get information about the movie
    $sql = "SELECT title, movie_poster FROM movie WHERE imdb = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $review["imdb"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Get the display name of the user that posted the review
    $sql = "SELECT display_name, user_img FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $review["user_id"]);
    $stmt->execute();
    $name = $stmt->get_result()->fetch_assoc();

    // Watch date
    $ds = new DateTime($review["watch_date"]);

    // Movie rating
    $rating = (int)$review["rating"];

    if (!function_exists('generate_stars')) {
        include("stars.php");
    }


    $html = '<div class="movieReview PreviewContainer"> 
                <a href = "movie.php?ID='. $review["imdb"] .'">
                    <img src="' . $result["movie_poster"] . '" alt="Movie Poster">
                </a> 
                <div class="reviewGrid"> 
                    <h4>' . $result['title'] . '</h4> 
                    <div class="profileRow">
                        <img class = "profileImg" src="' . $name['user_img'] . '" alt="User icon"> 
                        <a href ="userpage.php?USER=' . $review["user_id"] . '">' . $name['display_name'] . '</a> 
                        
                        <span style="justify-self:end;">' 
                            .generate_stars($rating) . 
                        '</span>
                    </div>
                    <p class = "previewReviewText">' . $review["content"] .'</p>
                    <h6>Watched ' . $ds->format('M d, Y') . '</h6>
                </div>
            </div>';

    echo $html; 
}

?>