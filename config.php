<?php
return (object) array(

    "project_name" => "trntSearch",

    "bittorent_trackers" => "&tr=http://nyaa.tracker.wf:7777/announce&tr=udp://open.stealth.si:80/announce&tr=udp://tracker.opentrackr.org:1337/announce&tr=udp://exodus.desync.com:6969/announce&tr=udp://tracker.torrent.eu.org:451/announce",
    "academic_torrent_trackers" => "&tr=https%3A%2F%2Facademictorrents.com%2Fannounce.php&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce",

    // randomly select invidious instance so that one instance won't get flooded with user request
    "invidious_instances" => array(
        "https://invidious.jing.rocks",
        "https://inv.nadeko.net"
    ),

    // the following array is for tor-instance, uncommet to use it for tor-instance. don't forget to commet above array

    // "invidious_instance" => array(
    //     "http://inv.nadekonw7plitnjuawu6ytjsl7jlglk2t6pyq6eftptmiv3dvqndwvyd.onion",
    // ),


    // the following array is for i2p-instance, uncommet to use it for i2p-instance. don't forget to commet above array

    // "invidious_instance" => array(
    //     "http://inv.zzls.i2p",
    // ),



    // randomly select libremdb instance so that one instance won't get flooded with user request
    "libremdb_instances" => array(
        "https://ld.vern.cc",
        "https://d.opnxng.com",
        "https://libremdb.pussthecat.org",
        "https://libremdb.lunar.icu",
        "https://imdb.nerdvpn.de"


    ),

    // the following array is for tor-instance, uncommet to use it for tor-instance. don't forget to commet above array

    // "invidious_instance" => array(
    //     "http://ld.vernccvbvyi5qhfzyqengccj7lkove6bjot2xhh5kajhwvidqafczrad.onion",
    //     "http://libremdb.g4c3eya4clenolymqbpgwz3q3tawoxw56yhzk4vugqrl6dtu3ejvhjid.onion",
    //     "http://libremdb.r4focoma7gu2zdwwcjjad47ysxt634lg73sxmdbkdozanwqslho5ohyd.onion"
    // ),

    // the following array is for i2p-instance, uncommet to use it for i2p-instance. don't forget to commet above array

    // "invidious_instance" => array(
    //     "http://vernz3ubrntql4wrgyrssd6u3qzi36zrhz2agbo6vibzbs5olk2q.b32.i2p",
    //     "http://qjlgasoy3nxepgzntucmcnb6pryqxakwdu7sxvqzi7spdfootryq.b32.i2p",
    //     "http://5j37qusybvyhecljn4hr5i4chifdlfqfkfveythzpzyfxiibt7cq.b32.i2p"
    // ),



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
