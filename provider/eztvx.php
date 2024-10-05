
<?php
require "misc/utils.php";
?>

<?php


function get_eztvx_results($query)
{
    $url = "https://eztvx.to/api/get-torrents?imdb_id=$query";
    $json = file_get_contents($url);
    $json_results =  json_decode($json, true);

    $store_results = array();

    if ($json_results["torrents_count"] !== 0) {
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

    return $store_results;
}


function print_eztvx_results($results, $query)
{
    $total = count($results);

    if ($total != 0) {
        print_total_results($total);
        foreach ($results as $result) {
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
        echo "<div class=\"margin-bottom-100\"></div>";
    } else {
        print_no_result_text($query);
    }
}




?>