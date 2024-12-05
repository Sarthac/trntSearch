<?php
include("misc/header.php");
include("misc/utils.php");
$config = require_once("config.php");

if (isset($_REQUEST["request"])) {
    $request = $_REQUEST["request"];
    setcookie("request", $request, time() + 86400 * 30, "/");

} else {
    $request = "post";
}

if (isset($_REQUEST["theme"])) {
    $theme = $_REQUEST["theme"];
    setcookie("theme", $theme, time() + 86400 * 30, "/");

}

if (!empty($_COOKIE["request"])) {
    $request_cookie = $_COOKIE["request"];
}
if (!empty($_COOKIE["theme"])) {
    $theme_cookie = $_COOKIE["theme"];
}


if (isset($_REQUEST["project_name"])) {
    $project_name = $_REQUEST["project_name"];

    //validating name and length
    if (strlen($project_name) > 0 && strlen($project_name) <= 25) {
        $project_name = valid_name($project_name);
        setcookie("project_name", $project_name, time() + 86400 * 30, "/");
    } else {
        $project_name = $config->$project_name;
    }
}


?>


<title> Settings</title>


<body>
    <h1 class="margin-bottom-20 margin-top-50">Settings</h1>
    <form autocomplete="off" method=<?php echo $request ?>>
        <div class="flex-row-space-between setting-box gap-10">
            <div>
                <h3>Change name</h3>
                <p>Name should be no longer than 25 chars.Include alphabets and numbers</p>
            </div>
            <input class="input-name" name="project_name" type="text" value=<?php echo (!empty($_COOKIE["project_name"])) ? $_COOKIE["project_name"] : $config->project_name ?>>
        </div>

        <div class="flex-row-space-between setting-box  gap-10">
            <div>
                <h3>request method</h3>
                <p>POST hides your query from the URL and browser tab. GET includes them.(set to 'get' if you intend to
                    bookmark or share url)</p>
            </div>
            <div>
                <select class="margin-0" name="request" id="">
                    <option value="post" <?php echo ($request_cookie == "post") ? "selected" : "" ?>>post</option>
                    <option value="get" <?php echo ($request_cookie == "get") ? "selected" : "" ?>>get
                    </option>
                </select>
            </div>
        </div>
        <div class="flex-row-space-between setting-box  gap-10">
            <div>
                <h3>theme</h3>
                <p class="margin-top-10">change the look and feel</p>
            </div>
            <select class="margin-0" name="theme" id="">
                <option value="dark" <?php echo ($theme_cookie == "dark") ? "selected" : "" ?>>dark </option>
                <option value="light" <?php echo ($theme_cookie == "light") ? "selected" : "" ?>>light</option>
                <option value="auto" <?php echo ($theme_cookie == "auto") ? "selected" : "" ?>>auto</option>
            </select>
        </div>

        <div>
            <button class="setting-btn" type="submit" name="save" value="1">Save</button>
        </div>
    </form>

</body>
<?php


if (isset($_REQUEST["save"])) {
    header("Location: ./");
    die();
}

include("misc/footer.php");

?>