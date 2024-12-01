<?php
require_once 'misc/header.php';

$query = htmlspecialchars($_REQUEST["query"]);
$site = $_REQUEST["site"];
$category = isset($_REQUEST["category"]) ? $_REQUEST["category"] : null;
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$sort_by = isset($_REQUEST["sort_by"]) ? $_REQUEST["sort_by"] : null;

$request = empty($_COOKIE["request"]) ? "get" : $_COOKIE["request"];
?>


<head>
    <title> <?php echo "$query"; ?> - trntSearch</title>
</head>

<body>

    <form class="sub-search-container" action="search.php" method=<?php echo $request ?> autocomplete="off">
        <div class="input-wrap">
            <input class="code" type="text" name="query" <?php echo "value=\"$query\"" ?>>
            <button class="submit-button" type="submit">
                <img src="./assets/search.png" alt="search-icon">
            </button>
        </div>
        <div class="wrap-dropdown">
            <div class="inline-block margin-top-20">
                <label class="label block" for="provider">Provider</label>
                <select name="site">
                    <option value="yts" <?php if ($site == "yts")
                        echo "selected" ?>>yts</option>
                        <option value="academic_torrents" <?php if ($site == "academic_torrents")
                        echo "selected" ?>>
                            academic_torrents</option>
                        <option value="piratebay" <?php if ($site == "piratebay")
                        echo "selected" ?>> piratebay</option>
                        <option value="1337x" <?php if ($site == "1337x")
                        echo "selected" ?>> 1337x</option>
                        <option value="rarbg" <?php if ($site == "rarbg")
                        echo "selected" ?>> rarbg</option>
                        <option value="kiwi_torrent_research" <?php if ($site == "kiwi_torrent_research")
                        echo "selected" ?>>
                            kiwi_torrent_research</option>
                        <option value="eztvx" <?php if ($site == "eztvx")
                        echo "selected" ?>> eztvx</option>
                    </select>
                </div>

                <div class="inline-block margin-top-20">
                    <label class="label block" for="category">Category</label>
                    <select class="second-select" name="category">
                        <optgroup label="academic-torrents">
                            <option value="all" <?php if ($category == "all")
                        echo "selected" ?>>All</option>
                            <option value="dataset" <?php if ($category == "dataset")
                        echo "selected" ?>>Dataset</option>
                            <option value="course" <?php if ($category == "course")
                        echo "selected" ?>>Course</option>
                            <option value="paper" <?php if ($category == "paper")
                        echo "selected" ?>>Paper</option>
                        </optgroup>
                        <optgroup label="piratebay">
                            <option value="all" <?php if ($category == "all")
                        echo "selected" ?>>All</option>
                            <option value="music" <?php if ($category == "music")
                        echo "selected" ?>>Music</option>
                            <option value="video" <?php if ($category == "video")
                        echo "selected" ?>>Video</option>
                            <option value="application" <?php if ($category == "application")
                        echo "selected" ?>>Application
                            </option>
                            <option value="game" <?php if ($category == "game")
                        echo "selected" ?>>Game</option>
                            <option value="yyy" <?php if ($category == "yyy")
                        echo "selected" ?>>YYY</option>
                            <option value="other" <?php if ($category == "other")
                        echo "selected" ?>>Other</option>
                        </optgroup>
                    </select>
                </div>

                <?php
                    if ($site == "rarbg" || $site == "kiwi_torrent_research") {
                        echo "<div class=\"inline-block margin-top-20\">";
                        echo "<label class=\"label block\" for=\"sort_by\">Sort By</label>";
                        echo "<select name=\"sort_by\">";
                        echo "<option value=\"title\"" . ($sort_by == "title" ? "selected" : "") . ">title</option>";
                        echo "<option value=\"size\"" . ($sort_by == "size" ? "selected" : "") . "> size</option>";
                        echo "</select>";
                        echo "</div>";
                    }
                    ?>

        </div>
    </form>


</body>

</html>

<?php

switch ($site) {

    case "yts":
        include "provider/yts.php";
        $results = get_yts_results($query, $page);
        print_yts_torrent_results($results, $query, $page);
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
        for ($i = 1; $i <= 5; $i++) {
            echo "<a href=\"search.php?query=$query&site=1337x&page=$i\">$i</a>";
        }
        break;

    case "rarbg":
        include "provider/rarbg.php";
        $sort_by = isset($_REQUEST["sort_by"]) ? $_REQUEST["sort_by"] : "title";
        $results = get_rarbg_results($query, $sort_by, $page);
        print_rarbg_results($results, $query, $sort_by, $page);
        break;

    case "kiwi_torrent_research":
        include "provider/kiwi_torrent_research.php";
        // $results = get_kiwi_torrent_research_results($query, $sort_by);
        // print_kiwi_torrent_research_results($results, $query,$page);
        $results = get_kiwi_torrent_research_results($query, $sort_by, $page);
        print_kiwi_torrent_research_results($results, $sort_by, $query, $page);
        break;

    case "eztvx":
        include "provider/eztvx.php";
        include "omdbapi.php";
        $cookie_name = convert_whole_string($query);
        if (empty($_COOKIE[$cookie_name])) {
            $imdb_id = get_imdb_id($query);
            if (is_string($imdb_id)) { // Ensure get_imdb_id returns a valid value
                setcookie($cookie_name, $imdb_id, time() + 86400 * 1, "/", "", false, true);
                // Since cookies are not immediately available after setting them,
                // we can use the $imdb_id variable directly for the current request
                $_COOKIE[$cookie_name] = $imdb_id;
            } else {
                echo "Error: The show you entered may be misspelled or does not exist.";
                break;
            }
        }
        $results = get_eztvx_results($_COOKIE[$cookie_name]);
        print_eztvx_results($results, $query);
        break;
}

echo "<div class=\"margin-bottom-100\"></div>";

include_once "misc/footer.php";
?>