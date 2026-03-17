<?php

function get_kiwi_torrent_research_results($query, $sort_by, $page_number, $results_per_page = 20)
{
    // grabing database
    $json_data = file_get_contents("db.json");
    $json = json_decode($json_data, true);
    $db_file = $json["kiwi_db"];

    // importing require files
    $config = require "includes/config.php";

    if (extension_loaded('sqlite3') && file_exists($db_file)) {
        $db = new SQLite3($db_file);
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
            $title = $row["name"];
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
        $results = ["error" => "Something went wrong."];
    }

    return $results;
}



function print_kiwi_torrent_research_results($results, $sort_by, $query, $page_number)
{
    require_once "includes/utils.php";

    if (isset($results["error"])) {
        echo "<span style=\"color : red;\">" . $results["error"] . " </span>";
    } else if (count($results) > 0) {
        print_total_results(count($results));
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
        print_no_result_text($query);
    }
}