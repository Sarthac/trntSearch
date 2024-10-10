<?php
require_once 'misc/header.php';
?>

<?php
$query = htmlspecialchars($_REQUEST["query"]);
$site = $_REQUEST["site"];
$category = $_REQUEST["category"];
?>


<head>
    <title> <?php echo "$query"; ?> - trntSearch</title>
</head>

<body>

    <form class="sub-search-container" action="search.php" method="get" autocomplete="off">
        <!-- <h1 class="no-decoration mobile-logo"><a href="./">trntSearch</a></h1> -->
        <div class="input-wrap">
            <input class="code" type="text" name="query" <?php echo "value=\"$query\""  ?>>
            <button class="submit-button" type="submit">
                <img src="./assets/search.png" alt="search-icon">
            </button>
        </div>
        <div>
            <div class="inline-block margin-top-20">
                <label class="label block" for="provider">Provider</label>
                <select name="site">
                    <option value="yts" <?php if ($site == "yts") echo "selected" ?>>yts</option>
                    <option value="academic_torrents" <?php if ($site == "academic_torrents") echo "selected" ?>> academic_torrents</option>
                    <option value="piratebay" <?php if ($site == "piratebay") echo "selected" ?>> piratebay</option>
                    <option value="1337x" <?php if ($site == "1337x") echo "selected" ?>> 1337x</option>
                    <option value="rarbg" <?php if ($site == "rarbg") echo "selected" ?>> rarbg</option>
                    <option value="eztvx" <?php if ($site == "eztvx") echo "selected" ?>> eztvx</option>
                </select>
            </div>

            <div class="inline-block margin-top-20">
                <label class="label block" for="category">Category</label>
                <select class="second-select" name="category">
                    <optgroup label="academic-torrents">
                        <option value="all" <?php if ($category == "all") echo "selected" ?>>All</option>
                        <option value="dataset" <?php if ($category == "dataset") echo "selected" ?>>Dataset</option>
                        <option value="course" <?php if ($category == "course") echo "selected" ?>>Course</option>
                        <option value="paper" <?php if ($category == "paper") echo "selected" ?>>Paper</option>
                    </optgroup>
                    <optgroup label="piratebay">
                        <option value="all" <?php if ($category == "all") echo "selected" ?>>All</option>
                        <option value="music" <?php if ($category == "music") echo "selected" ?>>Music</option>
                        <option value="video" <?php if ($category == "video") echo "selected" ?>>Video</option>
                        <option value="application" <?php if ($category == "application") echo "selected" ?>>Application</option>
                        <option value="game" <?php if ($category == "game") echo "selected" ?>>Game</option>
                        <option value="yyy" <?php if ($category == "yyy") echo "selected" ?>>YYY</option>
                        <option value="other" <?php if ($category == "other") echo "selected" ?>>Other</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </form>


</body>

</html>

<?php

switch ($site) {

    case "yts":
        include "provider/yts.php";
        $results = get_yts_results($query);
        print_yts_torrent_results($results, $query);
        break;

    case "academic_torrents":
        include "provider/academic_torrents.php";
        $results = search_by_name($query, $category);
        print_academic_torrents_results($results, $query);
        break;

    case "piratebay":
        include "provider/piratebay.php";
        $results = get_thepiratebay_results($query, $category);
        print_piratebay_results($results, $query);
        break;

    case "1337x":
        include "provider/1337x.php";
        $response = file_get_contents($_1337x_url);
        $results = get_1337x_results($response);
        print_1337x_results($results, $query);
        break;

    case "rarbg":
        include "provider/rarbg.php";
        $results = get_rarbg_results($query);
        print_rarbg_results($results, $query);
        break;

    case "eztvx":
        include "provider/eztvx.php";
        include "omdbapi.php";
        $omdb_results = get_omdbapi_details($query);
        $imdb_id = get_imdb_id($omdb_results);
        if ($imdb_id != "no tv series exist") {
            $results = get_eztvx_results($imdb_id);
            print_omdbapi_details($omdb_results);
            print_eztvx_results($results, $query);
        } else {
            echo " <span style=\"color: red;\"> $imdb_id on '$query', try different keyword </span> ";
        }

        break;
}

?>

<?php
include_once "misc/footer.php";

?>