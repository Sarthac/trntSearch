<?php
require("misc/utils.php");
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
function get_rarbg_results($query)
{
    $db = new SQLite3('assets/rarbg_db.sqlite');
    $db_query = "SELECT title,hash,size,dt,cat FROM items WHERE LOWER(title) LIKE LOWER('%$query%')";

    $db_results = $db->query($db_query);
    $row = $db_results->fetchArray(SQLITE3_ASSOC);
    $results = array();

    $counter = 0;
    while ($counter < 50 && $row = $db_results->fetchArray(SQLITE3_ASSOC)) {
        $hash = $row["hash"];
        $title = replace_dot($row["title"]);
        $date = $row["dt"];
        $category = $row["cat"];
        $size = $row["size"];
        $magnet = "magnet:?xt=urn:btih:$hash&dn=$title" . "&tr=udp://open.demonii.com:1337/announce&tr=udp://tracker.openbittorrent.com:80&tr=udp://tracker.coppersurfer.tk:6969&tr=udp://glotorrents.pw:6969/announce&tr=udp://torrent.gresille.org:80/announce&tr=udp://p4p.arenabg.com:1337&tr=udp://tracker.leechers-paradise.org:6969&tr=http://nyaa.tracker.wf:7777/announce&tr=udp://open.stealth.si:80/announce&tr=udp://tracker.opentrackr.org:1337/announce&tr=udp://exodus.desync.com:6969/announce&tr=udp://tracker.torrent.eu.org:451/announce";

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

        $counter += 1;
    }
    return $results;
}

function print_rarbg_results($results, $query)
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
        echo "<div class=\"margin-bottom-120\"></div>";
    } else {
        print_no_result_text($query);
    }
}





?>