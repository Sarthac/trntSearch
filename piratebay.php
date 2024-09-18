<?php
$config = require "config.php";
require "misc/utils.php";

function get_thepiratebay_results($query)
{
    global $config;
    $url = "https://apibay.org/q.php?q=$query";
    $response = file_get_contents($url);
    $json_response = json_decode($response, true);

    $results = array();

    if (empty($json_response)) {
        return $results;
    }

    foreach ($json_response as $response) {
        $size = human_filesize($response["size"]);
        $hash = $response["info_hash"];
        $name = $response["name"];
        $name = str_replace(".", " ", $name);
        $seeders = (int) $response["seeders"];
        $leechers = (int) $response["leechers"];
        $added = $response["added"];

        $magnet = "magnet:?xt=urn:btih:$hash&dn=$name" . $config->bittorent_trackers;

        if ($name == "No results returned")
            break;
        if ($seeders > 0) {
            array_push(
                $results,
                array(
                    "size" => htmlspecialchars($size),
                    "name" => htmlspecialchars($name),
                    "seeders" => (int) htmlspecialchars($seeders),
                    "leechers" => (int) htmlspecialchars($leechers),
                    "magnet" => htmlspecialchars($magnet),
                    "source" => "thepiratebay.org",
                    "added" => $added
                )
            );
        }
    }

    return $results;
}

function print_piratebay_results($results, $query)
{
    $total = count($results);
    if ($total != 0) {
        print_total_results($total);

        foreach ($results as $result) {
            $name = $result["name"];
            $magnet = $result["magnet"];
            $size = $result["size"];
            $seeders = $result["seeders"];
            $leechers = $result["leechers"];
            $source = $result["source"];
            $added = $result["added"];
            $added = date("Y-m-d H:i:s", $added);

            echo "<div class=\"margin-bottom-50\">";
            echo "<h2>$name</h2>";

            echo "<table>";
            echo "<tr>";
            echo "<th> Seeders </th>";
            echo "<th> Leechers </th>";
            echo "<th> Size </th>";
            echo "<th> Added </th>";
            echo "<th> magnet </th>";
            echo "</tr>";

            echo "<tr>";
            echo "<td> $seeders </td>";
            echo "<td> $leechers </td>";
            echo "<td> $size </td>";
            echo "<td> $added </td>";
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
