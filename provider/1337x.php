<?php

include "misc/utils.php";
$config = require "config.php";
$_1337x_url = "https://1337x.to/search/$query/$page/";


function get_1337x_results($response)
{
    $xpath = get_xpath($response);
    $results = array();

    if (!$xpath)
        return $results;

    foreach ($xpath->query("//table/tbody/tr") as $result) {
        $name = $xpath->evaluate(".//td[@class='coll-1 name']/a", $result)[1]->textContent;
        $name = str_replace(".", " ", $name);
        $magnet = "/proxy/get_magnet_1337x.php?url=https://1337x.to" . $xpath->evaluate(".//td[@class='coll-1 name']/a/@href", $result)[1]->textContent;
        $size_unformatted = explode(" ", $xpath->evaluate(".//td[contains(@class, 'coll-4 size')]", $result)[0]->textContent);
        $size = $size_unformatted[0] . " " . preg_replace("/[0-9]+/", "", $size_unformatted[1]);
        $seeders = $xpath->evaluate(".//td[@class='coll-2 seeds']", $result)[0]->textContent;
        $leechers = $xpath->evaluate(".//td[@class='coll-3 leeches']", $result)[0]->textContent;

        if ($seeders > 0) {
            array_push(
                $results,
                array(
                    "name" => htmlspecialchars($name),
                    "seeders" => (int) $seeders,
                    "leechers" => (int) $leechers,
                    "magnet" => htmlspecialchars($magnet),
                    "size" => htmlspecialchars($size),
                    "source" => "1337x.to"
                )
            );
        }
    }

    return $results;
}

function print_1337x_results($results, $query)
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

            echo "<div class=\"margin-bottom-50\">";
            echo "<h2>$name</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th> Seeders </th>";
            echo "<th> Leechers </th>";
            echo "<th> Size </th>";
            echo "<th> Magnet </th>";
            echo "</tr>";

            echo "<tr>";
            echo "<td> $seeders </td>";
            echo "<td> $leechers </td>";
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
    } else {
        print_no_result_text($query);
    }
}
