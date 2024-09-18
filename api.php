<?php
$query = htmlspecialchars($_REQUEST["query"]);
$site = $_REQUEST["site"];
$category = $_REQUEST["category"];

$results = array();

switch ($site) {

    case "yts":
        include "yts.php";
        $results = get_yts_results($query);
        break;

    case "academic_torrents":
        include "academic_torrents.php";
        $results = search_by_name($query, $category);
        break;

    case "piratebay":
        include "piratebay.php";
        $results = get_thepiratebay_results($query);
        break;

    case "1337x":
        include "1337x.php";
        $response = file_get_contents($_1337x_url);
        $results = get_1337x_results($response);
        break;

    case "rarbg":
        include "rarbg.php";
        $results = get_rarbg_results($query);
        break;

    case "eztvx":
        include "eztvx.php";
        include "omdbapi.php";
        $omdb_id = get_imdb_id($query);
        $results = get_eztvx_results($omdb_id);
        break;

    default:
        include "yts.php";
        $results = get_yts_results($query);
        break;
}

header("Content-Type: application/json");
echo json_encode($results);
