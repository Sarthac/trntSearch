<?php
require "misc/utils.php";
$config = require_once "config.php"
    ?>

<?php
// Connect to the database
$db = new SQLite3('assets/rarbg_db.sqlite');


// Prepare the query
// $query = "SELECT * FROM your_table";
// $query = "iron man";
$db_query = "SELECT title,hash,size FROM items WHERE title LIKE '%$query%'";

// Execute the query
$results = $db->query($db_query);
$row = $results->fetchArray();

// Fetch the data
$row = null;

// while ($row = $results->fetchArray()) {
//     print_r($row);
//     echo "<hr>";
// }


// while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
//     print_r($row);
//     echo "<hr>";
// }



// $values = array();
// while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
//     print_r($row);
//     array_push($values,
//     $row);
// }
// print_r($values);

// print_r($results->fetchArray(SQLITE3_ASSOC));
// $query = $_REQUEST["query"];
function get_rarbg_results($query, $sort_by, $page_number, $results_per_page = 20)
{
    global $config;
    $db = new SQLite3('assets/rarbg_db.sqlite');
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
        $title = replace_dot($row["title"]);
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
}

function print_rarbg_results($results, $query, $page_number)
{

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