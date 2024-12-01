<?php
include("misc/header.php");
$request = !empty($_REQUEST["request"]) ? $_REQUEST["request"] : "get";
$theme = !empty($_REQUEST["theme"]) ? $_REQUEST["theme"] : "dark";
?>

<head>
    <title> Settings</title>
</head>

<body>
    <h1 class="margin-bottom-20 margin-top-50">Settings</h1>
    <form action="" method=<?php echo $request ?>>
        <div class="flex-row-space-between setting-box">
            <div>
                <h3>request method</h3>
                <p>POST hides your query from the URL and browser tab. GET includes them.</p>
            </div>
            <div>
                <select name="request" id="">
                    <option value="get" <?php echo ($request == "get") ? "selected" : "" ?>>get</option>
                    <option value="post" <?php echo ($request == "post") ? "selected" : "" ?>>post</option>
                </select>
            </div>
        </div>
        <div class="flex-row-space-between setting-box">
            <div>
                <h3>theme</h3>
                <p>change the look and feel</p>
            </div>
            <select name="theme" id="">
                <option value="auto" <?php echo ($theme == "auto") ? "selected" : "" ?>>auto</option>
                <option value="light" <?php echo ($theme == "light") ? "selected" : "" ?>>light</option>
                <option value="dark" <?php echo ($theme == "dark") ? "selected" : "" ?>>dark</option>
            </select>
        </div>

        <div>
            <button class="setting-btn" type="submit" name="save" value="1">Save</button>
        </div>
    </form>

</body>
<?php

setcookie("request", $request, time() + 86400 * 30, "/");
setcookie("theme", $theme, time() + 86400 * 30, "/");

if (isset($_REQUEST["save"])) {
    header("Location: ./");
    die();
}

include("misc/footer.php");

?>