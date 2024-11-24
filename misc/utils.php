
<?php

function print_total_results($total)
{
    echo ($total > 1) ? "<span class=\"found-results\"> Found " . $total . " results </span>" :  "<span class=\"found-results\"> Found " . $total . " result </span>";
}

function get_instance($array)
{
    $result = random_int(0, count($array) - 1);
    return $array[$result];
}

function print_no_result_text($query)
{
    echo " <span style=\"color: red;\"> results not found on '$query', try different keyword </span> ";
}
function minutesToTime($minutes)
{
    $hours = floor($minutes / 60);
    $minutes = $minutes % 60;
    return sprintf($hours . "h" . " " . $minutes . "m");
}

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
    return parse_url($url, PHP_URL_HOST);
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
