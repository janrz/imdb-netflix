<?php
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

