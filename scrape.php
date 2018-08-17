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

