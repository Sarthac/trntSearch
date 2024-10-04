<?php
$query = htmlspecialchars($_REQUEST["query"]);
$site = $_REQUEST["site"];
// $category = $_REQUEST["category"];

$results = array();

switch ($site) {

    case "yts":
        include "provider/yts.php";
        $results = get_yts_results($query);
        break;

    case "academic_torrents":
        include "provider/academic_torrents.php";
        $results = search_by_name($query, $category);
        break;

    case "piratebay":
        include "provider/piratebay.php";
        $results = get_thepiratebay_results($query);
        break;

    case "1337x":
        include "provider/1337x.php";
        $response = file_get_contents($_1337x_url);
        $results = get_1337x_results($response);
        break;

    case "rarbg":
        include "provider/rarbg.php";
        $results = get_rarbg_results($query);
        break;

    case "eztvx":
        include "provider/eztvx.php";
        include "omdbapi.php";
        $omdb_id = get_imdb_id($query);
        $results = get_eztvx_results($omdb_id);
        break;

    default:
        include "provider/yts.php";
        $results = get_yts_results($query);
        break;
}

header("Content-Type: application/json");
echo json_encode($results);
