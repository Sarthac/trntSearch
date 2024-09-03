
<?php
function replace_chars($str)
{
    return str_replace(array('-', '_'), ' ', $str);
}

function replace_dot($str)
{
    return str_replace('.', ' ', $str);
}
function get_base_url($url)
{
    $split_url = explode("/", $url);
    $base_url = $split_url[0] . "//" . $split_url[2] . "/";
    return $base_url;
}

function get_root_domain($url)
{
    $split_url = explode("/", $url);
    $base_url = $split_url[2];

    $base_url_main_split = explode(".", strrev($base_url));
    $root_domain = strrev($base_url_main_split[1]) . "." . strrev($base_url_main_split[0]);

    return $root_domain;
}


function get_xpath($response)
{
    $htmlDom = new DOMDocument;
    @$htmlDom->loadHTML($response);
    $xpath = new DOMXPath($htmlDom);

    return $xpath;
}

function request($url)
{
    global $config;

    $ch = curl_init($url);
    curl_setopt_array($ch, $config->curl_settings);
    $response = curl_exec($ch);

    return $response;
}

function human_filesize($bytes, $dec = 2)
{
    $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$dec}f ", $bytes / pow(1024, $factor)) . @$size[$factor];
}


?>