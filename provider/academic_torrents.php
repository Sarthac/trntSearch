<?php
require "misc/utils.php";
$config = require "config.php";
$xml = simplexml_load_file('https://academictorrents.com/database.xml');

$items = $xml->channel->item;

function search_by_name($query, $category)
{
    global $config, $xml, $items;

    // example "data structure"
    $query = trim($query);
    $results_title = array();
    $results_description = array();



    foreach ($items as $item) {
        $title = (string) $item->title;


        $file_category = (string) $item->category;
        $description = (string) $item->description;
        $infohash = (string) $item->infohash;
        $magnet = "magnet:?xt=urn:btih:$infohash&dn=$title" . $config->academic_torrent_trackers;
        $size = $item->size;
        $size = human_filesize($size);


        $pattern = "/" . $query . "/i";
        $match_in_title = preg_match($pattern, $title);
        $match_in_description = preg_match($pattern, $description);

        //creating two while
        // 1. retrive all results from query that found in title and show all
        // 2. retriving all results which the query found in description and show all after 1.

        // retrive results if the query present in title first 
        while ($match_in_title) {

            if ($category == "all") {
                array_push(
                    $results_title,
                    array(
                        "title" => htmlspecialchars($title),
                        "category" => $file_category,
                        "description" => htmlspecialchars($description),
                        "magnet" => $magnet,
                        "size" => $size,
                    )
                );
            } elseif ($category == strtolower($file_category)) {
                array_push(
                    $results_title,
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


        // retrive results if the query present in description
        while ($match_in_title == false && $match_in_description == true) {

            if ($category == "all") {
                array_push(
                    $results_description,
                    array(
                        "title" => htmlspecialchars($title),
                        "category" => $file_category,
                        "description" => htmlspecialchars($description),
                        "magnet" => $magnet,
                        "size" => $size,
                    )
                );
            } elseif ($category == strtolower($file_category)) {
                array_push(
                    $results_description,
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
    $results = array_merge($results_title, $results_description);
    return $results;
}



function print_academic_torrents_results($results, $query)
{
    $total = count($results);

    if ($total != 0) {
        print_total_results($total);

        foreach ($results as $result) {
            $title = htmlspecialchars($result["title"]);
            $category = $result["category"];
            $description = htmlspecialchars($result["description"]);
            $magnet = $result["magnet"];
            $size = $result["size"];


            $pattern = "/" . $query . "/i";

            if (strlen($query) > 1) {
                if (preg_match($pattern, $title, $matches, PREG_OFFSET_CAPTURE)) {
                    $start = $matches[0][1];
                    $end = $start + strlen($matches[0][0]);
                    $title = substr($title, 0, $start) . '<span class="match">' . $matches[0][0] . '</span>' . substr($title, $end);
                }

                if (preg_match($pattern, $description, $description_matches, PREG_OFFSET_CAPTURE)) {
                    $start = $description_matches[0][1];
                    $end = $start + strlen($description_matches[0][0]);
                    $description = substr($description, 0, $start) . '<span class="match">' . $description_matches[0][0] . '</span>' . substr($description, $end);
                }
            }

            echo "<div class = \"margin-bottom-50\">";
            echo "<h2>$title</h2>";
            if ($result["description"] != null) {
                echo "<p class=\"para\"> $description </p>";
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
        echo "<div class=\"margin-bottom-100\"></div>";
    } else {
        print_no_result_text($query);
    }
}
