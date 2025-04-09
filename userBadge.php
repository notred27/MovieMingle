
<?php 

    function create_user_badge($user, $conn) {

        $sql = "SELECT COUNT(*) as c FROM friends WHERE user_id = ? AND friend_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_SESSION["user_id"], $user["user_id"]);
        $stmt->execute();
        $status = $stmt->get_result()->fetch_assoc();

        $status["color"] =  $status["c"] == 1 ? "#D5FC51" : "#FFFFFF";


        echo '<div>
                <img alt = "profileImg" src = "'. $user["user_img"] .'" style="width:40px; height:40px; border-radius:20px;">
                <a href = "userpage.php?USER=' . $user["user_id"] . '" style="font-size: larger;text-decoration: none;font-weight:bold;color:' .  $status["color"] . ';">' .
                    $user["display_name"] . 
                '</a>
            </div>';

    }

?>