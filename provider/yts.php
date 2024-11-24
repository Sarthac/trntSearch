<?php
include "misc/utils.php";
$config = require_once "config.php";


function get_yts_results($query, $page)
{
    $url = "https://yts.mx/api/v2/list_movies.json?query_term=$query&sort_by=like_count&page=$page";
    global $config;

    $results3 = array();
    try {
        $response = file_get_contents($url);
        if ($response === false) {
            throw new Exception("yts.mx is down");
        }
        $json_results = json_decode($response, true);
        if ($json_results["status"] == "ok" && $json_results["data"]["movie_count"] != 0) {
            $items = $json_results["data"]["movies"];
            $movie_count = ["total" => $json_results["data"]["movie_count"]];
            foreach ($items as $item) {
                $results = array();
                $results2 = array();

                $id = $item["id"];
                $imdb_code = $item["imdb_code"];
                $title = $item["title"];
                $year = $item["year"];
                $rating = $item["rating"];
                $runtime = $item["runtime"];
                $genres = $item["genres"];
                $summary = $item["summary"];
                $yt_trailer_code = $item["yt_trailer_code"];
                $img = $item["medium_cover_image"];
                $lang = $item["language"];

                array_push(
                    $results,
                    array(
                        "id" => $id,
                        "title" => $title,
                        "year" => $year,
                        "imdb_code" => $imdb_code,
                        "rating" => $rating,
                        "runtime" => $runtime,
                        "genres" => $genres,
                        "summary" => $summary,
                        "yt_trailer_code" => $yt_trailer_code,
                        "img" => $img,
                        "lang" => $lang
                    )
                );

                foreach ($item["torrents"] as $torrent) {
                    $quality = $torrent["quality"];
                    $type = $torrent["type"];
                    $size = $torrent["size"];
                    $seeders = $torrent["seeds"];
                    $hash = $torrent["hash"];
                    $magnet = "magnet:?xt=urn:btih:$hash&dn=$title" . $config->yts_trackers;

                    array_push(
                        $results2,
                        array(
                            "quality" => $quality,
                            "type" => $type,
                            "size" => $size,
                            "seeders" => $seeders,
                            "magnet" => "$magnet"
                        )
                    );
                }

                array_push(
                    $results3,
                    array(
                        "data" => $results,
                        "torrents" => $results2
                    )
                );
            }
            $results3 = array_merge($results3, $movie_count);
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
    return $results3;
}

function get_id_by_name($query)
{
    $url = "https://yts.mx/api/v2/list_movies.json?query_term=$query&sort_by=like_count";
    $response = file_get_contents($url);
    $json_results = json_decode($response, true);
    if ($json_results["status"] == "ok" && $json_results["data"]["movie_count"] != 0) {
        return $json_results["data"]["movies"][0]["id"];
    }
}

function get_suggestions($id)
{
    global $config;

    $url = "https://yts.mx/api/v2/movie_suggestions.json?movie_id=$id";

    $results3 = array();

    $response = file_get_contents($url);
    $json_results = json_decode($response, true);

    if ($json_results["status"] == "ok") {
        foreach ($json_results["data"]["movies"] as $item) {
            $results = array();
            $results2 = array();

            $id = $item["id"];
            $imdb_code = $item["imdb_code"];
            $title = $item["title"];
            $year = $item["year"];
            $rating = $item["rating"];
            $runtime = $item["runtime"];
            $genres = $item["genres"];
            $summary = $item["summary"];
            $yt_trailer_code = $item["yt_trailer_code"];
            $img = $item["medium_cover_image"];
            $lang = $item["language"];

            array_push(
                $results,
                array(
                    "id" => $id,
                    "title" => $title,
                    "year" => $year,
                    "imdb_code" => $imdb_code,
                    "rating" => $rating,
                    "runtime" => $runtime,
                    "genres" => $genres,
                    "summary" => $summary,
                    "yt_trailer_code" => $yt_trailer_code,
                    "img" => $img,
                    "lang" => $lang
                )
            );

            foreach ($item["torrents"] as $torrent) {
                $quality = $torrent["quality"];
                // $type = $torrent["type"];
                $size = $torrent["size"];
                $seeders = $torrent["seeds"];
                $hash = $torrent["hash"];
                $magnet = "magnet:?xt=urn:btih:$hash&dn=$title" . $config->yts_trackers;

                array_push(
                    $results2,
                    array(
                        "quality" => $quality,
                        // "type" => $type,
                        "size" => $size,
                        "seeders" => $seeders,
                        "magnet" => "$magnet"
                    )
                );
            }

            array_push(
                $results3,
                array(
                    "data" => $results,
                    "torrents" => $results2
                )
            );
        }
        return $results3;
    }
}

function print_yts_torrent_results($results, $query)
{
    global $config;
    $total = count($results);
    $invidious_instance = get_instance($config->invidious_instances);
    $libremdb_instance = get_instance($config->libremdb_instances);
    if ($total != 0) {

        print_total_results($total - 1);
        foreach ($results as $result) {

            foreach ($result["data"] as $value) {
                $id = $value["id"];
                $title = $value["title"];
                $year = $value["year"];
                $imdb_code = $value["imdb_code"];
                $rating = $value["rating"];
                $runtime = $value["runtime"];
                $runtime = minutesToTime($runtime);
                $summary = $value["summary"];
                $yt_trailer_code = $value["yt_trailer_code"];
                $img = $value["img"];
                $lang = $value["lang"];

                echo "<div class=\"flex-row margin-bottom-100\">";
                echo "<div class=\"flex-column-center\">";
                echo "<img src=\"proxy/image_proxy.php?url=$img\">";
                echo "<div class=\"yts-link\">";
                echo (!empty($yt_trailer_code)) ? "<a style=\"margin-right : 10px;\" class=\"btn \" href=\"$invidious_instance/watch?v=$yt_trailer_code\" target=\"_blank\">YT Trailer</a>" : "No Trailer";
                echo "<a class=\"btn \" href=\"suggestions.php?id=$id\" >Similar Movies</a>";
                echo "</div>";
                echo "</div>";
                echo "<div class=\"t-width\">";
                echo "<div class=\"title\">";
                echo "<h2 class=\"yts-h2\"";
                if ($summary != null) {
                    echo " class=\"yts-movie-name\"";
                }
                echo ">{$title}</h2>";
                echo "<span class=\"yts-lang\">$lang</span>";
                echo "<div class=\"movie-info\">";
                echo "<img src=\"assets/star.png\" alt=\"rating \">";
                echo "<span>$rating </span>";
                echo "<span>$runtime </span>";
                echo " <span>";
                foreach ($value["genres"] as $genre) {
                    echo $genre . " ";
                }
                echo " </span>";
                echo "<span>$year</span>";
                echo "<a href=\"$libremdb_instance/title/$imdb_code\" target=\"_blank\">...More</a>";
                echo "</div>";
                echo "</div>";

                if ($summary != null) {
                    echo "<p class=\"para\"> $summary </p>";
                }
            }
            echo "<div class=\"flex-column-space-between\">";
            echo "<table>";
            echo "<th> Type </th>";
            echo "<th> Seeders</th>";
            echo "<th> Magnet</th>";
            foreach ($result["torrents"] as $key) {

                $quality = $key["quality"];
                $type = $key["type"];
                $size = $key["size"];
                $seeders = $key["seeders"];
                $magnet = $key["magnet"];

                echo "<tr>";
                echo "<td>$type - $quality - $size</td>";

                echo ($seeders == 100) ? "<td>$seeders+</td>" : "<td>$seeders</td>";
                echo "<td>";
                echo "<a href=\"$magnet\">";
                echo "magnet";
                echo "</a>";
                echo "</td>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        $pages = ceil($results["total"] / 20);
        echo "<div class=\"flex-row-center\">";
        for ($i = 2; $i <= $pages; $i++) {
            echo "<a style=\"margin-left: 25px;\" href=\"./search.php?query=$query&site=yts&page=$i\">$i</a>";
        }
        echo "</div>";
    } else {
        print_no_result_text($query);
    }
}
