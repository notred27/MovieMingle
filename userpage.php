<?php
    session_start();

    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: landing.php');
        exit;
    }

    include('isFriend.php');


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

    $target_user_id = htmlspecialchars($_GET["USER"]);

    $sql = "SELECT * from user where user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_user_id);
    $stmt->execute();
    $target_user = $stmt->get_result()->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Mingle</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="userpage.css">
    <link rel="stylesheet" href="movieReviewPreview.css">

    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php include('web-header.php'); ?>

    <main>

    <div id = "userHeader">
    <?php 

        $sql = "SELECT COUNT(*) AS followers FROM friends WHERE friend_id = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $target_user_id);
        $stmt->execute();
        $f1 = $stmt->get_result()->fetch_assoc();

        $sql = "SELECT COUNT(*) AS f FROM friends WHERE user_id = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $target_user_id);
        $stmt->execute();
        $f2 = $stmt->get_result()->fetch_assoc();

        echo '<img id="profileImg" src="me.png" alt="User icon"> <h1 id = "username" >' . $target_user["display_name"] .'</h1>';

        if($target_user["user_id"] != $_SESSION["user_id"]) {

            $sql = "SELECT COUNT(*) AS is_friend FROM friends WHERE user_id = ? and friend_id = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $_SESSION["user_id"], $target_user_id);
            $stmt->execute();
            $f = $stmt->get_result()->fetch_assoc();
           

            if ($f["is_friend"] == 0) {
                echo '<button id = "toggleFollowBtn" class = "userBtn follow" > Follow </button>';
                
            } else {
                echo '<button id = "toggleFollowBtn" class = "userBtn following"> Following </button>';
            }

            
        } else {
            echo   '<button class = "userBtn"> Rename </button>
                    <div id = "editIcon">
                        <img src="pencil.svg" alt="User icon" style="width:20px;height:20px;">
                    </div>';

        }

        echo '<span id = "followerStats"> '. $f1["followers"] .' followers</span> 
              <span id = "followingStats"> '. $f2["f"] .' following</span>';
    ?>
    </div>


    <?php include("stars.php");
    
    generate_stars(3);

    generate_stars(10);

    ?>

    <h2>Your Reviews</h2>

    <label for="sort-option">Sort by:</label>
    <select name = "sort-option" id = "sort-option">
        <option value="recent">Recent</option>
        <option value="oldest">Oldest</option>
        <option value="highest">Highest Rating</option>
        <option value="lowest">Lowest Rating</option>
    </select>

    <div id="row-container" style="display:grid; grid-template-columns: repeat(auto-fill,540px);column-gap:10px; row-gap:10px;">



    </div>
        

    </main>



    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>

    <script>
        function sortReviews() {
            const sortOption = this.value;

            fetch(`load_rows.php?sort=${sortOption}&id=${<?php echo json_encode($target_user_id); ?>}`)
                .then(response => response.text())
                .then(data => {
                document.getElementById('row-container').innerHTML = data;
                })
                .catch(error => console.error('Error fetching sorted data:', error));
        }

        // Sort reviews when dropdown option is changed
        document.getElementById('sort-option').addEventListener('change', sortReviews);


        // Load reviews initially on redirect
        window.addEventListener('DOMContentLoaded', sortReviews);


        function changeFriend() {


        fetch(`toggle-friend.php?friend_id=${<?php echo json_encode($target_user_id); ?>}`)
            .then(response => response.text())
            .then(data => {
                const btn =  document.getElementById('toggleFollowBtn');

                btn.textContent = data;

                if(data == "Following") {
                    btn.classList.remove('follow');
                    btn.classList.add('following');
                } else {
                    btn.classList.remove('following');
                    btn.classList.add('follow');
                }
            })
            .catch(error => console.error('Error fetching sorted data:', error));
        }

        // Add an event listener to the button to trigger the function on click
        document.getElementById('toggleFollowBtn').addEventListener('click', changeFriend);
    </script>
</body>

</html>