<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>IMDb Watchlist Netflix checker</title>
<style>
div#container {
    max-width: 1200px;
    margin: auto;
}

div#container div#titleContainer {
    text-align: center;
}

div.movie {
    max-width: 300px;
    display: inline-block;
}

@media screen and (max-width: 600px) {
    div.movie {
        width: 100%;
    }
}

div.movie div.movieImage {
    width: 20%;
    max-width: 200px;
    float: left;
}
div.movie div.movieTitle {
    width: 80%;
    float: left;
}

div.movie div.movieImage img {
    max-width: 100%;
    max-height: 100%;
}
</style>
</head>
<body>

<?php
$user = $_GET['user'];

$contents = file_get_contents("https://www.imdb.com/user/" . $user . "/watchlist");
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($contents);
libxml_clear_errors();

$xpath = new DOMXpath($dom);
$element = $xpath->query('//span[@class="ab_widget"]/script')->item(0);
if ($element == null) {
    die("Error: watchlist not public");
}
$data = $element->nodeValue;

preg_match('/IMDbReactInitialState.push\((.*$)/m', $data, $matches);
$data = $matches[1];

preg_match('/"author":"(.+?)(?=\")/', $data, $author);
$watchlistOwner = $author[1];

preg_match('/\"titles\":.+?(?=}\);)/', $data, $titles);

$titles = $titles[0];
$titlesWithoutPlot = preg_replace('/\"plot\":\".+?(?=\"poster)/', '', $titles);

$data = json_decode("{" . $titlesWithoutPlot . "}", true);

$movieArray = $data['titles'];

$movies = [];

foreach ($movieArray as $movie) {
    $movieObject = new Movie();
    $movieObject->setTitle($movie['primary']['title']);
    $movieObject->setImageURL($movie['poster']['url']);
    $movieObject->setIMDbURL($movie['primary']['href']);
    $movies[] = $movieObject;
}

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

class Movie {
    private $title;
    private $imageURL;
    private $IMDbURL;

    function setTitle($title) {
        $this->title = $title;
    }

    function getTitle() {
        return $this->title;
    }

    function setImageURL($URL) {
        $this->imageURL = $URL;
    }

    function getImageURL() {
        return $this->imageURL;
    }

    function setIMDbURL($URL) {
        $this->IMDbURL = $URL;
    }

    function getIMDbURL() {
        return $this->IMDbURL;
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
