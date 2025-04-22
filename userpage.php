<?php
    require("db-connect.php");

    $target_user_id = htmlspecialchars($_GET["USER"]);

    $sql = "SELECT * from user where user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_user_id);
    $stmt->execute();
    $target_user = $stmt->get_result()->fetch_assoc();


    if (!function_exists('create_user_badge')) {
        include("./components/userBadge.php");
    }

    // Get followers and following
    $sql = "SELECT user.display_name, user.user_id, user.user_img FROM user JOIN friends on user.user_id = friends.user_id where friend_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_user_id);
    $stmt->execute();
    $followers = $stmt->get_result();


    $sql = "SELECT user.display_name, user.user_id, user.user_img FROM user JOIN friends on user.user_id = friends.friend_id where friends.user_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_user_id);
    $stmt->execute();
    $following = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Mash</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/userpage.css">
    <link rel="stylesheet" href="./styles/movieReviewPreview.css">
    <link rel="stylesheet" href="./styles/club.css">

    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
</head>

<body>

<?php include('./components/web-header.php'); ?>
<main>

    <!-- Popup menus to display other users -->
    <div class = "adminPopup" id="followersDiv" style="display:none;">
        <div class = "adminPopupBody" style="display:flex;justify-content:center;flex-direction:column;">

            <h1>Users that follow <?php echo $target_user["display_name"]; ?>: </h1>
            <div style="width:200px;">
                <br/> 
                Search for users: 
                <input id="followerSearch"></input>
                <button onclick="toggleFollowersDiv()">Back</button>

            </div>
            <div style="overflow-y:scroll;height:60vh;">
            <?php
                if ($followers->num_rows > 0) {
                    while ($follower = $followers->fetch_assoc()) {
                        create_user_badge($follower, $conn);
                    }
                }
            ?>


            </div>
        </div>
    </div>


    <div class = "adminPopup" id="followingDiv" style="display:none;">
        <div class = "adminPopupBody" style="display:flex;justify-content:center;flex-direction:column;">
        <h1>Users that <?php echo $target_user["display_name"]; ?> follows: </h1>

            <div style="width:200px;">
                <br/> 
                Search for users: 
                <input id="followingSearch"></input>
                <button onclick="toggleFollowingDiv()">Back</button>

            </div>
            <div style="overflow-y:scroll;height:60vh;">
            <?php
                if ($following->num_rows > 0) {
                    while ($follow = $following->fetch_assoc()) {
                        create_user_badge($follow, $conn);
                    }
                }
            ?>


            </div>
        </div>
    </div>


<!-- Header that shows info about the user -->
    <div id="userHeader">
    <?php 

    echo '<img id="profileImg" src="' . $target_user["user_img"] . '" alt="User icon"> <h1 id="username">' . $target_user["display_name"] . '</h1>';

    if ($target_user["user_id"] != $_SESSION["user_id"]) {

        $sql = "SELECT COUNT(*) AS is_friend FROM friends WHERE user_id = ? and friend_id = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $_SESSION["user_id"], $target_user_id);
        $stmt->execute();
        $f = $stmt->get_result()->fetch_assoc();

        if ($f["is_friend"] == 0) {
            echo '<button id="toggleFollowBtn" class="userBtn follow"> Follow </button>';
        } else {
            echo '<button id="toggleFollowBtn" class="userBtn following"> Following </button>';
        }

    } 
    // else {
    //     echo '<button class="userBtn"> Rename </button>
    //         <div id="editIcon">
    //             <img src="./assets/pencil.svg" alt="User icon" style="width:20px;height:20px;">
    //         </div>';
    // }

    echo '<button id="followerStats" onclick="toggleFollowersDiv()">' . $followers->num_rows . ' followers</button> 
        <button id="followingStats" onclick="toggleFollowingDiv()">' . $following->num_rows . ' following</button>';

    ?>
    </div>

    <h2>Your Reviews <?php echo '(<span id="numReviews"> </span>)'; ?></h2>

    <label for="sort-option">Sort by:</label>
    <select name="sort-option" id="sort-option">
        <option value="recent">Recent</option>
        <option value="oldest">Oldest</option>
        <option value="highest">Highest Rating</option>
        <option value="lowest">Lowest Rating</option>
    </select>


    <!-- Contains reviews after query -->
    <div id="row-container" style="display:grid; grid-template-columns: repeat(auto-fill,540px);column-gap:10px; row-gap:10px;"></div>

</main>

<footer>
    <p>&copy; 2025 Movie Mash. All rights reserved.</p>
</footer>

<script src="./scripts/movie-dropdown.js" defer></script>

<script>
    function sortReviews() {
        const sortOption = this.value;

        fetch(`./scripts/load-rows.php?sort=${sortOption}&id=${<?php echo json_encode($target_user_id); ?>}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('row-container').innerHTML = data;
                document.getElementById('numReviews').innerHTML = document.getElementsByClassName('movieReview ').length;
            })
            .catch(error => console.error('Error fetching sorted data:', error));
    }

    // Sort reviews when dropdown option is changed
    document.getElementById('sort-option').addEventListener('change', sortReviews);

    // Load reviews initially on redirect
    window.addEventListener('DOMContentLoaded', sortReviews);

    function changeFriend() {
        fetch(`./scripts/toggle-friend.php?friend_id=${<?php echo json_encode($target_user_id); ?>}`)
            .then(response => response.text())
            .then(data => {
                const btn = document.getElementById('toggleFollowBtn');
                btn.textContent = data;

                if (data == "Following") {
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
    if (document.getElementById('toggleFollowBtn') != null) {
        document.getElementById('toggleFollowBtn').addEventListener('click', changeFriend);
    }

    function toggleFollowingDiv() {
        var div = document.getElementById("followingDiv");
        if (div.style.display === "none") {
            div.style.display = "block";
            document.body.classList.add("menu-open");
            
        } else {
            div.style.display = "none";
            document.body.classList.remove("menu-open");

        }

        document.getElementById("followingSearch").value = "";
        const followers = document.querySelectorAll('.userBadge');
        followers.forEach(follower => {
            follower.style.display = 'block';
        });
    }

    function toggleFollowersDiv() {
        var div = document.getElementById("followersDiv");
        if (div.style.display === "none") {
            div.style.display = "block";
            document.body.classList.add("menu-open");


        } else {
            div.style.display = "none";
            document.body.classList.remove("menu-open");

        }

        document.getElementById("followerSearch").value = "";
        const followers = document.querySelectorAll('.userBadge');
        followers.forEach(follower => {
            follower.style.display = 'block';
        });
    }

    function filterResults(event) {
        const search = event.target.value.toLowerCase();
        const followers = document.querySelectorAll('.userBadge');

        followers.forEach(follower => {
            const name = follower.dataset.id.toLowerCase();
            follower.style.display = name.includes(search) ? 'block' : 'none';
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        console.log("DOM fully loaded");

        document.getElementById("followerSearch").addEventListener("input", filterResults);
        document.getElementById("followingSearch").addEventListener("input", filterResults);
    });
</script>

</body>

</html>
