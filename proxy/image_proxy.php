<?php
$config = require_once "../config.php";
function request($url)
{
    global $config;

    $ch = curl_init($url);
    curl_setopt_array($ch, $config->curl_settings);
    $response = curl_exec($ch);

    return $response;
}

$image_url = $_GET['url'];

$image_domain = parse_url($image_url, PHP_URL_HOST);
$allowed_domains = array("yts.mx", "m.media-amazon.com", "ezimg.ch");


if (in_array($image_domain, $allowed_domains)) {
    $image_data = request($image_url);
    header('Content-Type: image/jpg');
    echo $image_data;
}
