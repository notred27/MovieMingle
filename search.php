<?php
    require("db-connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Mash</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./styles/styles.css">
</head>

<body>
    <?php include('./components/web-header.php'); ?>

    <main>

    <div style="display:grid;grid-template-columns: 1fr 1fr 1fr;">
        <form id = "searchForm">
            <h3 style="margin:2px;">Movies</h3>
            <input type="text" id="movie_name" name="movie_name" placeholder="Movie Name" value="<?= htmlspecialchars($_GET['movie_name'] ?? '') ?>">
            <br />

            <input type="text" id="release_year" name="release_year" placeholder="Release Year" >
            <br />

            <input type="text" id="genres" name="genres" placeholder="Genres">
            <br />

            <input type="text" id="key_words" name="key_words" placeholder="Key words" value="<?= htmlspecialchars($_GET['key_words'] ?? '') ?>">
            <br />

            <button type="submit">Search</button>
        </form>


        <form id = "clubForm">
            <h3 style="margin:2px;">Users</h3>
            <input type="text" id="club_name" name="club_name" placeholder="Club Name">
            <br />

            <input type="text" id="club_topics" name="club_topics" placeholder="Club Topics">
            <br />

            <button type="submit">Search</button>
        </form>

        <form id = "userForm">
            <h3 style="margin:2px;">Users</h3>
            <input type="text" id="user_name" name="user_name" placeholder="Username"  value="<?= htmlspecialchars($_GET['user_name'] ?? '') ?>">
            <br />

            <button type="submit">Search</button>
        </form>
    </div>


    <h2>Search Results </h2>
    
    <label for="sort-option">Sort by:</label>
    <select name = "sort-option" id = "sort-option">
        <option value="recent">Recent</option>
        <option value="oldest">Oldest</option>
        <option value="az">Alphabetical (A-Z)</option>
        <option value="za">Alphabetical (Z-A)</option>
        <option value="highest">Rating (Highest)</option>
        <option value="lowest">Rating (Lowest)</option>
    </select>

    <div id = "results" style = "display:flex; flex-direction:row; flex-wrap:wrap; gap:10px; justify-content:space-evenly;">


    </div>
        

    </main>

    <footer>
        <p>&copy; 2023 Michael Reidy. All rights reserved.</p>
    </footer>

    <script>
        let lastSearchType = 'movies';

        function get_movies() {
            // e.preventDefault();

            const formData = new FormData(document.getElementById('searchForm'));
            const params = new URLSearchParams(formData);

            const sortOption = document.getElementById('sort-option').value;


            fetch(`./scripts/search-movies.php?` + params.toString() + `&sort=${sortOption}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('results').innerHTML = data;
                })
                .catch(error => {
                    console.error('Search error:', error);
                    document.getElementById('results').innerHTML = "<p>Error loading search results.</p>";
                });
        }



        function get_users() {
            // e.preventDefault();

            const formData = new FormData(document.getElementById('userForm'));
            const params = new URLSearchParams(formData);

            const sortOption = document.getElementById('sort-option').value;


            fetch(`./scripts/search-users.php?` + params.toString() + `&sort=${sortOption}`)
                .then(response => response.text())
                .then(data => {
                    console.log("Hellow");
                    document.getElementById('results').innerHTML = data;
                })
                .catch(error => {
                    console.error('Search error:', error);
                    document.getElementById('results').innerHTML = "<p>Error loading search results.</p>";
                });
        }
        
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            get_movies();
            lastSearchType = 'movies';

        });


        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            get_users();
            lastSearchType = 'users';

        });

        // document.getElementById('sort-option').addEventListener('change', get_movies);

        document.getElementById('sort-option').addEventListener('change', function() {

        if(lastSearchType == "movies") {
            get_movies();
        } else if (lastSearchType == "users") {
            get_users();
        }
        });

        
        // window.addEventListener('DOMContentLoaded', get_movies);

    </script>
</body>

</html>