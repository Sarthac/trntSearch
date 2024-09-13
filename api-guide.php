<?php

include "misc/header.php";

?>

<p>trntSearch API does not require an API key.</p>
<p>example: <a href="./api.php?query=spider-man&site=yts&category=All">./api.php?query=spider-man&site=yts&category=All</a></p>
<p>Parameters:</p>
<p>query=(keyword that you want to get results on)</p>
<p>site=(torrent site to get results i.e yts, academic-torrents, 1337x, piratebay, rarbg and eztvx)</p>
<p>category=(category only need for academic-torrent and can categorized results in All,Dataset,Course and Paper but required for all the torrent site. In future do not require for all the torrents )</p>



<?php
include_once "misc/footer.php";

?>