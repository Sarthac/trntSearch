<?php
require_once 'misc/header.php';

$request = empty($_COOKIE["request"]) ? "get" : $_COOKIE["request"];
?>

<head>
    <title>trntSearch</title>
</head>

<body>

    <form class="search-container" action="search.php" method=<?php echo $request ?> autocomplete="off">
        <div class="input-wrap">
            <input class="code" type="text" name="query" placeholder="Search" autofocus>
            <button class="submit-button" type="submit">
                <img src="./assets/search.png" alt="search-icon">
            </button>

        </div>

        <div>
            <div class="inline-block margin-top-20">
                <label class="label block " for="provider">Provider</label>
                <select name="site">
                    <option value="yts">yts</option>
                    <option value="academic_torrents">academic_torrents</option>
                    <option value="piratebay">piratebay</option>
                    <option value="1337x">1337x</option>
                    <option value="rarbg">rarbg</option>
                    <option value="kiwi_torrent_research">kiwi_torrent_research</option>
                    <option value="eztvx">eztvx</option>
                </select>
            </div>
            <div style="font-size: small;" class="inline-block margin-top-20">
                <label class="label block" for="category">Category</label>
                <select name="category">
                    <optgroup label="academic-torrents">
                        <option value="all">All</option>
                        <option value="dataset">Dataset</option>
                        <option value="course">Course</option>
                        <option value="paper">Paper</option>
                    </optgroup>
                    <optgroup label="piratebay">
                        <option value="all">All</option>
                        <option value="music">Music</option>
                        <option value="video">Video</option>
                        <option value="application">Application</option>
                        <option value="game">Game</option>
                        <option value="yyy">YYY</option>
                        <option value="other">Other</option>
                    </optgroup>
                </select>
            </div>
        </div>

    </form>
</body>

</html>

<?php
include_once "misc/footer.php";

?>