<?php

$json_data = file_get_contents("assets/secrete.json");
$json = json_decode($json_data, true);
$api_key = $json["api_key"];

function get_omdbapi_id($query)
{
    global $api_key, $query;
    $url = "http://www.omdbapi.com/?t={$query}&apikey={$api_key}";
    $omdbapi_response = file_get_contents($url);
    $omdbapi_json_data = json_decode($omdbapi_response, true);
    $id = $omdbapi_json_data["imdbID"];
    $id = str_replace("tt", "", $id);
    return $id;
}
