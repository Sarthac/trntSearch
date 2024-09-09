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
        <h1 class="no-decoration mobile-logo"><a href="./">trntSearch</a></h1>
        <div class="input-wrap">
            <input class="code" type="text" name="query" <?php echo "value=\"$query\""  ?>>
            <button class="submit-button" type="submit">
                <img src="./assets/search.png" alt="search-icon">
            </button>
        </div>
        <div>
            <select name="site">
                <option value="yts" <?php if ($site == "yts") echo "selected" ?>>yts</option>
                <option value="academic_torrents" <?php if ($site == "academic_torrents") echo "selected" ?>> academic_torrents</option>
                <option value="piratebay" <?php if ($site == "piratebay") echo "selected" ?>> piratebay</option>
                <option value="1337x" <?php if ($site == "1337x") echo "selected" ?>> 1337x</option>
                <option value="rarbg" <?php if ($site == "rarbg") echo "selected" ?>> rarbg</option>
            </select>


            <select class="second-select" name="category">
                <option value="All" <?php if ($category == "All") echo "selected" ?>>All</option>
                <option value="Dataset" <?php if ($category == "Dataset") echo "selected" ?>>Dataset</option>
                <option value="Course" <?php if ($category == "Course") echo "selected" ?>>Course</option>
                <option value="Paper" <?php if ($category == "Paper") echo "selected" ?>>Paper</option>
            </select>

        </div>
    </form>


</body>

</html>

<?php

switch ($site) {

    case "yts":
        include "yts.php";
        $url = "https://yts.mx/api/v2/list_movies.json?query_term=$query";
        $response = file_get_contents($url);
        $results = get_yts_results($response);
        print_yts_torrent_results($response);
        break;

    case "academic_torrents":
        include "academic_torrents.php";
        $results = search_by_name($query, $category);
        print_academic_torrents_results($results);
        // search_by_name($query, $category);

        break;

    case "piratebay":
        include "piratebay.php";
        $url = "https://apibay.org/q.php?q=$query";
        $response = file_get_contents($url);
        $results = get_thepiratebay_results($response);
        count_results($response);
        print_piratebay_results($results);
        break;

    case "1337x":
        include "1337x.php";
        $response = file_get_contents($_1337x_url);
        $results = get_1337x_results($response);
        count_results($response);
        print_1337x_results($results);
        break;

    case "rarbg":
        include "rarbg.php";
        $results = get_rarbg_results($query);
        print_rarbg_results($results);
        break;
}

?>