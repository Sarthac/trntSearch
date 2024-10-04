<?php
$image_url = $_GET['url'];
$image_data = file_get_contents($image_url);
header('Content-Type: image/jpg');
echo $image_data;
