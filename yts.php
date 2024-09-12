<?php
include "misc/utils.php";
include "misc/header.php";
$config = include "config.php";

function get_yts_results($query)

{
    $url = "https://yts.mx/api/v2/list_movies.json?query_term=$query";
    $results3 = array();
    try {
        $response = file_get_contents($url);
        if ($response === false) {
            throw new Exception("yts.mx is down");
        }
        $json_results =  json_decode($response, true);
        if ($json_results["status"] == "ok" && $json_results["data"]["movie_count"] != 0) {
            $items = $json_results["data"]["movies"];
            foreach ($items as $item) {
                $results = array();
                $results2 = array();


                $title = $item["title"];
                $year = $item["year"];
                $rating = $item["rating"];
                $runtime = $item["runtime"];
                $genres = $item["genres"];
                $summary = $item["summary"];
                $img = $item["medium_cover_image"];

                array_push(
                    $results,
                    array(
                        "title" => $title,
                        "year" => $year,
                        "rating" => $rating,
                        "runtime" => $runtime,
                        "genres" => $genres,
                        "summary" => $summary,
                        "img" => $img
                    )
                );

                foreach ($item["torrents"] as $torrent) {
                    $quality = $torrent["quality"];
                    $type = $torrent["type"];
                    $size = $torrent["size"];
                    $seeders = $torrent["seeds"];
                    $hash = $torrent["hash"];
                    $magnet = "magnet:?xt=urn:btih:$hash&dn=$title" . "&tr=udp://open.demonii.com:1337/announce&tr=udp://tracker.openbittorrent.com:80&tr=udp://tracker.coppersurfer.tk:6969&tr=udp://glotorrents.pw:6969/announce&tr=udp://torrent.gresille.org:80/announce&tr=udp://p4p.arenabg.com:1337&tr=udp://tracker.leechers-paradise.org:6969&tr=http://nyaa.tracker.wf:7777/announce&tr=udp://open.stealth.si:80/announce&tr=udp://tracker.opentrackr.org:1337/announce&tr=udp://exodus.desync.com:6969/announce&tr=udp://tracker.torrent.eu.org:451/announce";

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
                        "basic" => $results,
                        "more" => $results2
                    )
                );
            }
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
    return $results3;
}



function print_yts_torrent_results($results, $query)
{
    $total = count($results);
    if ($total != 0) {

        print_total_results($total);
        foreach ($results as $result) {

            foreach ($result["basic"] as $value) {
                $title = $value["title"];
                $year = $value["year"];
                $rating = $value["rating"];
                $runtime = $value["runtime"];
                $runtime = minutesToTime($runtime);
                $summary = $value["summary"];
                $img = $value["img"];

                echo "<div class=\"flex-row margin-bottom-100\">";
                echo "<img src=\"image_proxy.php?url=$img\">";
                echo "<div class=\"t-width\">";
                echo "<div class=\"title\">";
                echo "<h2 class=\"yts-h2\"";
                if ($summary != null) {
                    echo " class=\"yts-movie-name\"";
                }
                echo ">{$title}</h2>";
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
                echo "</div>";
                echo "</div>";

                if ($summary != null) {
                    echo "<p> $summary </p>";
                }
            }
            echo "<div class=\"flex-column-space-between\">";
            echo "<table>";
            echo "<th> Type </th>";
            echo "<th> Seeders</th>";
            echo "<th> Magnet</th>";
            foreach ($result["more"] as $key) {

                $quality = $key["quality"];
                $type = $key["type"];
                $size = $key["size"];
                $seeders = $key["seeders"];
                $magnet = $key["magnet"];

                echo "<tr>";
                echo "<td>$type - $quality - $size</td>";

                echo "<td>$seeders</td>";
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
    } else {
        print_no_result_text($query);
    }
}
