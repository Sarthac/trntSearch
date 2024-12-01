<?php

$site = isset($_REQUEST["site"]) ? $_REQUEST["site"] : null;
$suggestions = isset($_REQUEST["suggestions"]) ? $_REQUEST["suggestions"] : null;
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$sort_by = isset($_REQUEST["sort_by"]) ? $_REQUEST["sort_by"] : null;

$results = array();

if (isset($suggestions)) {
    include "provider/yts.php";
    $id = get_id_by_name($suggestions);
    $results = get_suggestions($id);
} else if (isset($site)) {
    $query = htmlspecialchars($_REQUEST["query"]);
    switch ($site) {
        case "yts":
            include "provider/yts.php";
            $results = get_yts_results($query, $page);
            break;

        case "academic_torrents":
            $category = strtolower($_REQUEST["category"]);
            include "provider/academic_torrents.php";
            $results = search_by_name($query, $category);
            break;

        case "piratebay":
            $category = strtolower($_REQUEST["category"]);
            include "provider/piratebay.php";
            $results = get_thepiratebay_results($query, $category);
            break;

        case "1337x":
            include "provider/1337x.php";
            $response = file_get_contents($_1337x_url);
            $results = get_1337x_results($response);
            break;

        case "rarbg":
            include "provider/rarbg.php";
            $results = get_rarbg_results($query, $sort_by, $page);
            break;

        case "kiwi_torrent_research":
            include "provider/kiwi_torrent_research.php";
            $results = get_kiwi_torrent_research_results($query, $sort_by, $page);
            break;

        case "eztvx":
            include "provider/eztvx.php";
            include "omdbapi.php";
            $omdb_results = get_omdbapi_details($query);
            $omdb_id = get_imdb_id($omdb_results);
            $results = get_eztvx_results($omdb_id);
            break;

        default:
            include "provider/yts.php";
            $results = get_yts_results($query, $page);
            break;
    }
} else {
    include "misc/header.php";

    echo "<p>trntSearch API does not require an API key.</p>";
    echo "<p>example: <a href=\"./api.php?query=spider-man&site=yts\">./api.php?query=spider-man&site=yts</a></p>";
    echo "<p>Parameters:</p>";
    echo "<p>query=(keyword that you want to get results on. Provide with site parameter)</p>";
    echo "<p>site=(torrent site to get results i.e yts, academic-torrents, 1337x, piratebay, rarbg and eztvx. Provide with query parameter)</p>";
    echo "<p>category=(category only need for academic_torrents and piratebay, for academic_torrents i.e all, dataset, course and paper. For piratebay i.e all, music, video, application, game, yyy and other)</p>";
    echo "<p>suggestions=(type a keyword that you want to get movie suggestions based on keyword. it doesn't need site, query or category as extra parameters)</p>";
    echo "<p>page=(only for eztvx to get result from eztvx pages, defualt 30 results per page)";

    include_once "misc/footer.php";

    die();
}

header("Content-Type: application/json");
echo json_encode($results);
