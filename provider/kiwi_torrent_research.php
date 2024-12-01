<?php
require 'misc/utils.php';
$config = require_once "config.php";

function get_kiwi_torrent_research_results($query, $sort_by, $page_number, $results_per_page = 20)
{
    $kiwi_torrent_research_sqlite = 'assets/dump_30_04_2023.sqlite';
    global $config;

    if (file_exists($kiwi_torrent_research_sqlite)) {
        $db = new SQLite3('assets/dump_30_04_2023.sqlite');
        $offset = ($page_number - 1) * $results_per_page;
        $db_query = "SELECT infohash,name,size,uploaded,num_files FROM torrents WHERE LOWER(name) LIKE LOWER('%$query%')";

        if ($sort_by == "title") {
            $db_query .= "";
        } elseif ($sort_by == "size") {
            $db_query .= " ORDER BY size DESC";
        }

        $db_query .= " LIMIT $results_per_page OFFSET $offset";

        $db_results = $db->query($db_query);
        $row = $db_results->fetchArray(SQLITE3_ASSOC);
        $results = array();

        while ($row) {
            $hash = bin2hex($row["infohash"]);
            $title = ($row["name"]);
            $size = $row["size"];
            $date = $row["uploaded"];
            $num_files = $row["num_files"];

            $magnet = "magnet:?xt=urn:btih:$hash&dn=$title" . $config->yts_trackers . $config->bittorrent_trackers;

            array_push(
                $results,
                array(
                    "hash" => $hash,
                    "title" => $title,
                    "size" => $size,
                    "date" => $date,
                    "num_files" => $num_files,
                    "magnet" => $magnet
                )
            );

            $row = $db_results->fetchArray(SQLITE3_ASSOC);
        }
    } else {
        $results = ["title" => "no sqlite file exist"];
    }

    return $results;
}



function print_kiwi_torrent_research_results($results, $sort_by, $query, $page_number)
{
    global $config;
    if (isset($results["title"]) && $results["title"] == "no sqlite file exist") {
        echo "<span style=\"color : red;\"> this server doesn't have 75gb of stoage to store kiwi-torrent-research sqlite file. visit other </span> <a href=\"instances.php\">instances</a>";
    } else if (count($results) > 0) {
        // print_total_results($total_results);

        foreach ($results as $result) {
            $hash = $result['hash'];
            $title = $result['title'];
            $size = human_filesize($result['size']);
            $date = date("Y-m-d H:i:s", $result['date']);
            $num_files = $result["num_files"];

            $magnet = $result['magnet'];

            echo "<div class=\"margin-bottom-50\">";
            echo "<h2>$title</h2>";
            echo "<table>";
            echo "<tr>";

            echo "<th> Date </th>";
            echo "<th> Size </th>";
            echo "<th> File Count </th>";

            echo "<th> Magnet </th>";
            echo "</tr>";

            echo "<tr>";
            echo "<td> $date </td>";

            echo "<td> $size </td>";
            echo "<td> $num_files </td>";
            echo "<td>";
            echo "<a href=\"$magnet\">";
            echo "magnet";
            echo "</a>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "</div>";
        }

        echo "<div class=\"lnline-block text-align-center\">";
        for ($i = 2; $i <= 10; $i++) {
            if ($i < 10) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            echo "<a  class=\"" . ($page_number == $i ? "active" : "") . "\"style=\"margin-right: 15px; display: inline-block;\" href=\"./search.php?query=" . urlencode($query) . "&site=kiwi_torrent_research&sort_by={$sort_by}&page=$i\">$i</a>";
        }
        echo "</div>";
    } else {
        echo "No results found.";
    }
}