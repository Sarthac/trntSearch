<?php
$config = require "config.php";

?>


<div class="footer">
    <div>
        <h3><a href="./"><?php echo (!empty($_COOKIE["project_name"])) ? $_COOKIE["project_name"] : $config->project_name ?>
            </a>
        </h3>
    </div>

    <div class="scrollable">
        <a href="api.php">API</a>
        <a href="instances.php">Instances</a>
        <a target="_blank" href="https://github.com/sarthac/trntSearch">Source</a>
        <a href="settings.php">Settings</a>
        <a href="guide.php">Guide</a>
    </div>

</div>