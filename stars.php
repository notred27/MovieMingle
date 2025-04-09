<?php 


function generate_stars($rating) {
    $html = '<span style="display:flex;flex-direction:row;">';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating / 2)) {
            $html .= '<img src = "./assets/l-star.png" style = "height:16px;"><img src = "./assets/r-star.png" style = "height:16px;">';
    
        } else if ($i == ceil($rating / 2) && $rating % 2 != 0) {
            $html .= '<img src = "./assets/l-star.png" style = "height:16px;"><img src = "./assets/r-hollow.png" style = "height:16px;">';
    
        }  else {
            $html .= '<img src = "./assets/l-hollow.png" style = "height:16px;"><img src = "./assets/r-hollow.png" style = "height:16px;">';

        }
    }
    return $html . '</span>';
}


?>