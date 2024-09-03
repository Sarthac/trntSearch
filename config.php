<?php
return (object) array(

    "bittorent_trackers" => "&tr=http://nyaa.tracker.wf:7777/announce&tr=udp://open.stealth.si:80/announce&tr=udp://tracker.opentrackr.org:1337/announce&tr=udp://exodus.desync.com:6969/announce&tr=udp://tracker.torrent.eu.org:451/announce",
    "academic_torrent_trackers" => "&tr=https%3A%2F%2Facademictorrents.com%2Fannounce.php&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce",

    "curl_settings" => array(
        // CURLOPT_PROXY => "ip:port",
        // CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:116.0) Gecko/20100101 Firefox/116.0", // For a normal Windows 10 PC running Firefox x64
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_WHATEVER,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
        CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_VERBOSE => false,
        CURLOPT_FOLLOWLOCATION => true
    )

);
