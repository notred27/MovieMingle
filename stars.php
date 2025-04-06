<?php 


function generate_stars($rating) {
    $html = '<span style="display:flex;flex-direction:row;">';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating / 2)) {
            $html .= '<img src = "l-star.png" style = "height:16px;"><img src = "r-star.png" style = "height:16px;">';
    
        } else if ($i == ceil($rating / 2) && $rating % 2 != 0) {
            $html .= '<img src = "l-star.png" style = "height:16px;"><img src = "r-hollow.png" style = "height:16px;">';
    
        }  else {
            $html .= '<img src = "l-hollow.png" style = "height:16px;"><img src = "r-hollow.png" style = "height:16px;">';

        }
    }
    return $html . '</span>';
}


?>