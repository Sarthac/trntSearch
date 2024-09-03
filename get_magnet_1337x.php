<?php
$config = require "./config.php";
require "misc/utils.php";

$url = $_REQUEST["url"];

$response = request($url, $config->curl_settings);
$xpath = get_xpath($response);

$magnet = $xpath->query("//main/div/div/div/div/div/ul/li/a/@href")[0]->textContent;
$magnet_without_tracker = explode("&tr=", $magnet)[0];
$magnet = $magnet_without_tracker . $config->bittorent_trackers;

header("Location: $magnet");
// header('Content-Type: text/plain');
