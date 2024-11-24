<?php
require "misc/utils.php";
?>

<?php

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

function get_eztvx_results($query)
{
    global $page;
    $url = "https://eztvx.to/api/get-torrents?imdb_id=$query&page=$page";
    $json = file_get_contents($url);
    $json_results = json_decode($json, true);

    $store_results = array();

    if ($json_results["torrents_count"] !== 0) {
        $store_results = array("torrents_count" => $json_results["torrents_count"]);
        $results = $json_results["torrents"];
        foreach ($results as $result) {
            $title = $result["title"];
            $seeds = $result["seeds"];
            $magnet = $result["magnet_url"];
            $size = $result["size_bytes"];
            $date = $result["date_released_unix"];
            $img = $result["small_screenshot"];


            array_push(
                $store_results,
                array(
                    "title" => $title,
                    "seeds" => $seeds,
                    "magnet" => $magnet,
                    "size" => $size,
                    "date" => $date,
                    "img" => $img
                )
            );
        }
    }
    return array_reverse($store_results);
}

function print_eztvx_results($results, $query)
{
    global $page;
    $total = count($results);

    if ($total != 0) {
        print_total_results($total - 1); // -1 because it count "torrents_count" as a torrent which is not
        foreach ($results as $result) {
            if (isset($result['title'])) {
                $title = $result['title'];
                $seeds = $result['seeds'];
                $magnet = $result['magnet'];
                $date = $result['date'];
                $date = date("Y-m-d H:i:s", $date);
                $size = human_filesize($result['size']);
                $img = $result['img'];
                $img = "https:" . $img;

                echo "<div class=\"margin-bottom-50\">";
                echo "<img src=\"proxy/image_proxy.php?url=$img\" alt=\"$query image\">";
                echo "<h2>$title</h2>";
                echo "<table>";
                echo "<tr>";
                echo "<th> Seeds </th>";


                echo "<th> Date </th>";
                echo "<th> Size </th>";
                echo "<th> Magnet </th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td> $seeds </td>";
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
        }
        echo "<div class=\"margin-bottom-50\"></div>";

        // following code is running everytime, which is does not need to.
        $pages = ceil($results["torrents_count"] / 30);
        echo "<div class=\"lnline-block text-align-ceter\" style=\"line-height: 2;\">";
        for ($i = 2; $i <= $pages; $i++) {
            if ($i < 10) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            echo "<a style=\"margin-right: 15px; display: inline-block;\" href=\"./search.php?query=$query&site=eztvx&page=$i\">$i</a>";
        }
        echo "</div>";
    } else {
        print_no_result_text($query);
    }
}

?>