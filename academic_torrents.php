<?php
require_once "./misc/header.php";
require "misc/utils.php";
$config = require "config.php";
$xml = simplexml_load_file('https://academictorrents.com/database.xml');

$items = $xml->channel->item;

function search_by_name($query, $category)
{
    global $config, $xml, $items;

    // example "data structure"
    $query = ucwords(strtolower($query));
    $results = array();


    foreach ($items as $item) {
        $title = (string) $item->title;
        // converting for search because it case sensitive
        $title_convert = ucwords(strtolower($title));
        $file_category = (string) $item->category;
        $description = (string) $item->description;
        $infohash = (string) $item->infohash;
        $magnet = "magnet:?xt=urn:btih:$infohash&dn=$title" . $config->academic_torrent_trackers;
        $size = $item->size;
        $size = human_filesize($size);


        $array_title = explode(" ", $title_convert);

        foreach ($array_title as $word_compare) {
            if ($query == $word_compare) {
                if ($category == "All") {
                    array_push(
                        $results,
                        array(
                            "title" => htmlspecialchars($title),
                            "category" => $file_category,
                            "description" => htmlspecialchars($description),
                            "magnet" => $magnet,
                            "size" => $size,
                        )
                    );
                } elseif ($category == $file_category) {
                    array_push(
                        $results,
                        array(
                            "title" => htmlspecialchars($title),
                            "category" => $file_category,
                            "description" => htmlspecialchars($description),
                            "magnet" => $magnet,
                            "size" => $size,
                        )
                    );
                }
                break;
            }
        }
    }
    return $results;
}


function print_academic_torrents_results($results)
{
    echo "<div class=\"found-results\"> Found " . count($results) . " results </div>";

    foreach ($results as $result) {
        $title = htmlspecialchars($result["title"]);
        $category = $result["category"];
        $description = htmlspecialchars($result["description"]);
        $magnet = $result["magnet"];
        $size = $result["size"];

        echo "<div class = \"margin-bottom-50\">";
        echo "<h2>$title</h2>";
        if ($result["description"] != null) {
            echo "<p> $description </p>";
        }
        echo "<table>";
        echo "<tr>";
        echo "<th> Category </th>";
        echo "<th> Size </th>";
        echo "<th> Magnet </th>";
        echo "</tr>";

        echo "<tr>";
        echo "<td> $category </td>";
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
}
