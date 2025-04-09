<?php
    require("db-connect.php");

    $target_club = htmlspecialchars($_GET["CLUB"]);

    $sql = "SELECT * from club where club_name = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_club);
    $stmt->execute();
    $club = $stmt->get_result()->fetch_assoc();


    $sql = "SELECT * from member_of where club_name = ? AND user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $target_club, $_SESSION["user_id"]);
    $stmt->execute();
    $mem_status = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Movie Mingle</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="userpage.css">
        <link rel="stylesheet" href="movieReviewPreview.css">
        <link rel="stylesheet" href="poll.css">


        <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>

   

    </head>

    <body>


    <?php include('web-header.php'); ?>
    <main>

    <?php 
        
        if (!function_exists('create_user_badge')) {
            include("userBadge.php");
        }

        $sql = "SELECT user.display_name, user.user_id, user.user_img, member_of.admin_level FROM member_of JOIN user on user.user_id = member_of.user_id where member_of.club_name = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $target_club);
        $stmt->execute();
        $members = $stmt->get_result();



        echo '<div id = "memberDiv" style="display:none;background-color:#282828; position:absolute; top:10vh; left:20vw; z-index:200; width:60vw; height:70vh;"><button onclick="toggleMemberDiv()">Cancel</button> <br/> SEARCH <input></input><br/> <div style="overflow-y:scroll;height:60vh;">';
        if ($members->num_rows > 0) { 
            while ($member = $members->fetch_assoc()) {
                create_user_badge($member, $conn);
            }   
        }
        echo '</div></div>';
    ?>


        <h1><?php echo $club["club_name"]?></h1>
        <p><?php echo $club["club_description"]?></p>


        <button id = "toggleMemberBtn">
            <?php
                if($mem_status->num_rows == 0) {
                    echo "Join";
                } else {
                    echo "Leave";
                }

            ?> 
        </button>


        <?php
            echo '<button onclick="toggleMemberDiv()" > '. $members->num_rows.' members</button>'
        ?>

        <div>
            <?php 
                $sql = "SELECT * from club_topics where club_name = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $target_club);
                $stmt->execute();
                $topics = $stmt->get_result();

                if ($topics->num_rows > 0) { 
                    while ($topic = $topics->fetch_assoc()) {
                        echo $topic["topic"] . ', ';
                    }
                } else {
                    echo "No topics.";
                }

            ?>
        </div>

        
        <div>
            <?php 
                include('poll.php');


                $sql = "SELECT * from poll where in_club = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $target_club);
                $stmt->execute();
                $reviews = $stmt->get_result();

                if ($reviews->num_rows > 0) { 
                    while ($review = $reviews->fetch_assoc()) {
                        create_poll_html($review, $conn);
                    }
                } else {
                    echo "You haven''t reviewed this movie yet!";
                }

            ?>
        </div>

        <div>
            <?php 
                include('movieReviewPreview.php');


                $sql = "SELECT * from review where in_club = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $target_club);
                $stmt->execute();
                $reviews = $stmt->get_result();

                if ($reviews->num_rows > 0) { 
                    while ($review = $reviews->fetch_assoc()) {
                        create_movie_review_preview($review, $conn);
                    }
                } else {
                    echo "You haven''t reviewed this movie yet!";
                }

            ?>
        </div>
        

    </main>



<script>

    
function selectOption(poll, option) {
    fetch(`select-poll-option.php?poll_id=${poll}&opt_id=${option}`)
    .catch(error => console.error("Error fetching sorted data:", error));

}

function changeOption(poll, option) {
    fetch(`change-poll-option.php?poll_id=${poll}&opt_id=${option}`)
    .catch(error => console.error("Error fetching sorted data:", error));

}



function toggleMember() {


    fetch(`toggle-club-member.php?CLUB=${<?php echo json_encode($club["club_name"]); ?>}`)
        .then(response => response.text())
        .then(data => {

            const btn =  document.getElementById('toggleMemberBtn');

            btn.textContent = data;

            // if(data == "Following") {
            //     btn.classList.remove('follow');
            //     btn.classList.add('following');
            // } else {
            //     btn.classList.remove('following');
            //     btn.classList.add('follow');
            // }
        })
        .catch(error => console.error('Error fetching sorted data:', error));
}

// Add an event listener to the button to trigger the function on click
document.getElementById('toggleMemberBtn').addEventListener('click', toggleMember);


function toggleMemberDiv() {
        var div = document.getElementById("memberDiv");
        if (div.style.display === "none") {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    }


</script>

    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>

   
</body>

</html>