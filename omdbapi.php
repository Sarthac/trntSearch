<?php

$json_data = file_get_contents("assets/secrete.json");
$json = json_decode($json_data, true);
$api_key = $json["api_key"];

$url = "https://www.omdbapi.com/?t={$query}&apikey={$api_key}";




function get_omdbapi_details($query)
{
    global $query, $url;
    $omdbapi_response = file_get_contents($url);
    $omdbapi_json_data = json_decode($omdbapi_response, true);
    $results = array();
    if ($omdbapi_json_data["Response"] != "False") {
        $results = [
            "Response" => $omdbapi_json_data["Response"],
            "imdbID" => $omdbapi_json_data["imdbID"],
            "Title" => $omdbapi_json_data["Title"],
            "Year" => $omdbapi_json_data["Year"],
            "Rated" => $omdbapi_json_data["Rated"],
            "Released" => $omdbapi_json_data["Released"],
            "Runtime" => $omdbapi_json_data["Runtime"],
            "Genre" => $omdbapi_json_data["Genre"],
            "Director" => $omdbapi_json_data["Director"],
            "Writer" => $omdbapi_json_data["Writer"],
            "Actors" => $omdbapi_json_data["Actors"],
            "Plot" => $omdbapi_json_data["Plot"],
            "Country" => $omdbapi_json_data["Country"],
            "Awards" => $omdbapi_json_data["Awards"],
            "Poster" => $omdbapi_json_data["Poster"],
            "Metascore" => $omdbapi_json_data["Metascore"],
            "imdbRating" => $omdbapi_json_data["imdbRating"],
            "Type" => $omdbapi_json_data["Type"]

        ];
    } else {
        $results = [
            "Response" => $omdbapi_json_data["Response"]

        ];
    }
    return $results;
}

function print_omdbapi_details($results)
{
    $config = require_once "config.php";
    $libremdb_instance = get_instance($config->libremdb_instances);
    if ($results["Response"] != "False") {

        $title = $results["Title"];
        $imdbID = $results["imdbID"];
        $year = $results["Year"];
        $imdbRating = $results["imdbRating"];
        $rated = $results["Rated"];
        $released = $results["Released"];
        $runtime = $results["Runtime"];
        $genre = $results["Genre"];
        $poster = $results["Poster"];
        $country = $results["Country"];
        $director = $results["Director"];
        $actor = $results["Actors"];
        echo "<div class=\"flex-column omdb-results\">";
        echo "<div>";
        echo "<img src=\"proxy/image_proxy.php?url=$poster\">";
        echo "</div>";
        echo "<div>";
        echo "<h3>$title  </h3>";
        echo "<div class=\"movie-info\">";
        echo "<img src=\"assets/star.png\" alt=\"rating \">";
        echo "<span>$imdbRating </span>";
        echo "<span>$runtime </span>";
        echo "<span>$genre </span>";
        echo "<span>$released</span>";
        echo "</div>";
        echo "<a style=\"font-size: smaller;\" href=\"$libremdb_instance/title/$imdbID\" target=\"_blank\">...More about series</a>";
        echo "</div>";
        echo "</div>";
    }
}



function get_imdb_id($results)
{
    if ($results["Response"] != "False") {

        $id = $results["imdbID"];
        $id = str_replace("tt", "", $id);
        return $id;
    } else {
        return "no tv series exist";
    }
}
