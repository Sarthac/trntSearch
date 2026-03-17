<?php


function get_rarbg_results($query, $sort_by, $page_number, $results_per_page = 20)
{
    // grabing db
    $json_data = file_get_contents("secrete.json");
    $json = json_decode($json_data, true);
    $rarbg_sqlite = $json["rarbg_db"];

    // importing require files
    $config = require "includes/config.php";
    require "includes/utils.php";

    if (extension_loaded('sqlite3') && file_exists($rarbg_sqlite)) {
        $db = new SQLite3($rarbg_sqlite);
        $offset = ($page_number - 1) * $results_per_page;
        $db_query = "SELECT title,hash,size,dt,cat FROM items WHERE LOWER(title) LIKE LOWER('%$query%')";

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
            $hash = $row["hash"];
            $title = $row["title"];
            $date = $row["dt"];
            $category = $row["cat"];
            $size = $row["size"];
            $magnet = "magnet:?xt=urn:btih:$hash&dn=$title" . $config->yts_trackers . $config->bittorrent_trackers;

            array_push(
                $results,
                array(
                    "hash" => $hash,
                    "title" => $title,
                    "date" => $date,
                    "category" => $category,
                    "size" => $size,
                    "magnet" => $magnet
                )
            );
            $row = $db_results->fetchArray(SQLITE3_ASSOC);
        }
        return $results;
    } else {
        return ["error" => "Something went wrong."];
    }
}

function print_rarbg_results($results, $query, $sort_by, $page_number)
{
    if (isset($results["error"])) {
        echo "<span style=\"color : red;\">" . $results["error"] . " </span>";
        return;
    } 
    $total = count($results);
    if ($total != 0) {
        print_total_results($total);

        foreach ($results as $result) {
            $hash = $result['hash'];
            $title = $result['title'];
            $date = $result['date'];
            $category = $result['category'];
            $size = human_filesize($result['size']);
            $magnet = $result['magnet'];


            echo "<div class=\"margin-bottom-50\">";
            echo "<h2>$title</h2>";


            echo "<table>";
            echo "<tr>";
            echo "<th> Category </th>";


            echo "<th> Date </th>";
            echo "<th> Size </th>";
            echo "<th> Magnet </th>";
            echo "</tr>";

            echo "<tr>";
            echo "<td> $category </td>";
            echo "<td> $date </td>";

            echo "<td> $size </td>";
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
        for ($i = 2; $i <= 5; $i++) {
            echo "<a  class=\"" . ($page_number == $i ? "active" : "") . "\"style=\"margin-right: 15px; display: inline-block;\" href=\"./search.php?query=" . urlencode($query) . "&site=rarbg&sort_by={$sort_by}&page=$i\">$i</a>";
        }
        echo "</div>";

    } else {
        print_no_result_text($query);
    }
}

?>