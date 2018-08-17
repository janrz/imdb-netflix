<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>IMDb Watchlist Netflix checker</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

<?php
require 'Movie.php';
include 'scrape.php';

function printIntro($owner) {
    echo "<h1>" . $owner . "'s IMDb Watchlist</h1>";
}

function printMovies($movies) {
    foreach ($movies as $movie) {
        $netflixSearchURL = "https://www.netflix.com/search?q=" . str_replace(" ", "%20", $movie->getTitle());
        echo '<div class="movie">
                <div class="movieImage">
                    <a href="https://www.imdb.com' . $movie->getIMDbURL() . '" target="_blank">
                        <img src="' . $movie->getImageURL() . '">
                    </a>
                </div>
                <div class="movieTitle">
                    <h2>' . $movie->getTitle() . '</h2>
                    <a href="' . $netflixSearchURL . '" target="_blank">
                        Search on Netflix
                    </a>
                </div>
            </div>';
    }
}

?>
        <div id="container">
            <div id="titleContainer">
                <?php printIntro($watchlistOwner); ?>
            </div>
            <?php printMovies($movies); ?>
        </div>
    </body>
</html>
