
<?php

    echo '<header> <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr;"> <h1>Movie Mash</h1> <a href = "./home.php">Home</a>';
    echo '<a href = "userpage.php?USER=' . $_SESSION["user_id"] . '"">Profile</a>';
    echo '<a href = "./search.php">Advanced Search</a> <a href = "./landing.php">Logout</a> </div> </header>';

?>