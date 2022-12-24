<?php
require_once "../../config.php";
require_once "./functions.php";
require_once "../publicFunc.php";
?>
<html lang="de">
<head>
    <title>Projekte | <?php echo(projectData(basename(__DIR__))["name"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<body style="overflow-y: hidden">
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<h1>Link shortener</h1>
<div class="main">
    <h2>Creator</h2>
    <form action="creator.php" method="post">
        <input style="width: 80%" name="target" type="url" placeholder="URL..."><br>
        <button type="submit" name="create">Short!</button>
    </form>
    <?php
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "invalid") {
            echo "<p style='color: red'>This is not a valid URI.<br>(".$_GET['uri'].")</p>";
        } elseif ($_GET["error"] == "0") {
            echo "<p style='color: lime'>Shorted URL.<br>(<a href='https://lnk.sebis.net?l=".$_GET['uri']."' target='_blank'>https://lnk.sebis.net?l=".$_GET['uri']."</a>)</p>";
        }
    }
    ?>
</div>
<div class="main"><hr></div>
<?php
if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
?>
<div class="main" style="overflow-y: initial">
    <h2>Your links</h2>
    <?php
    foreach (linksArray($_SESSION["id"]) as $link) {
        echo '
<div class="sub" style="display: flex; justify-content: stretch; width: 80%">
<p style="margin: auto auto; max-width: 50%; left: 10px; overflow-x: hidden; overflow-y: hidden; max-height: 28pt; 
flex-shrink: 100000"><a href="'.$link["target"].'" target="_blank">'.$link["target"].'</a></p>

<p style="margin: auto auto; max-width: 50%; left: 10px; overflow-x: hidden; overflow-y: hidden; max-height: 28pt; 
flex-shrink: 100000"><a href="https://sebis.net/lnk/?l='.$link["lid"].'" target="_blank">'.$link["lid"].'</a></p>

<p style="margin: auto auto; right: 10px; overflow-x: hidden; overflow-y: hidden; max-height: 28pt">Views: '.$link["views"].'</p>

<form action="delete.php" method="post"><button style="border: none; width: unset; float: right; cursor: pointer; 
right: 10px; align-self: flex-end; color: orangered" type="submit" name="delete" value="'.$link["lid"].'">delete</button></form>

</div>
';
    }
    ?>
</div>
<?php
}
?>
</body>