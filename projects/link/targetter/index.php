<html lang="en">
<head>
    <title>Sebis.net</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>

<?php
require_once "../../../config.php";
require_once "../../publicFunc.php";
require_once "../functions.php";

if (!isset($_GET["l"]) || getLinkByLid($_GET["l"]) == false) {
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
} else {
    $target = getLinkByLid($_GET["l"])["target"];
    addView($_GET["l"]);
    //print_r(getLinkByLid($_GET["l"]));
    echo "
    Redirecting...
<script>
function sleep(milliseconds) {
  let start = new Date().getTime();
  for (let i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
sleep(2000)
window.location.replace('" .$target."');
setTimeout(function() {
}, 2000);
</script>
";
    header("location: ".$target);
}
exit();