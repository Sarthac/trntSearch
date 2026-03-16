<?php
$config = require_once "../includes/config.php";
require_once "../includes/utils.php";
$image_url = $_GET['url'];

$image_domain = parse_url($image_url, PHP_URL_HOST);
$allowed_domains = array("yts.bz", "img.yts.bz", "m.media-amazon.com", "ezimg.ch");


if (in_array($image_domain, $allowed_domains)) {
    $image_data = request($image_url);
    header('Content-Type: image/jpg');
    echo $image_data;
}
