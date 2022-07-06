<?php

require_once "../../../config.php";
require_once "../../publicFunc.php";
require_once "../functions.php";

if (!isset($_GET["l"]) || getLink($_GET["l"]) == false) {
    echo '
<html lang="en">
<head>
<title>404 Not Found</title>
<h1>Not Found</h1>
<p>The requested URL was not found on this server.</p>
<hr>
<address>Apache/2.4.38 (Debian) Server at '.$_SERVER['HTTP_HOST'].' Port '.$_SERVER["SERVER_PORT"].'</address>

<iframe src="about:blank" style="display: none;"></iframe></body></html>';
    header("HTTP/1.1 404 Not Found");
    exit();
} else {
    $target = getLink($_GET["l"])["target"];
    header("location: ".$target);
}