<?php
require_once 'misc/header.php';
?>





<head>
    <title>trntSearch</title>
</head>

<body>

    <form class=" search-container" action="search.php" method="get" autocomplete="off">
        <!-- <h1 class="no-decoration">trntSearch</h1> -->
        <div class="input-wrap">
            <input class="code" type="text" name="query" placeholder="Search" autofocus>
            <button class="submit-button" type="submit">
                <img src="./assets/search.png" alt="search-icon">
            </button>

        </div>

        <div>
            <select name="site">
                <option value="yts">yts</option>
                <option value="academic_torrents">academic_torrents</option>
                <option value="piratebay">piratebay</option>
                <option value="1337x">1337x</option>
                <option value="rarbg">rarbg</option>
            </select>
            <select name="category">
                <option value="All">All</option>
                <option value="Dataset">Dataset</option>
                <option value="Course">Course</option>
                <option value="Paper">Paper</option>
            </select>
        </div>

    </form>
</body>

</html>


<?php
include_once "misc/footer.php";

?>