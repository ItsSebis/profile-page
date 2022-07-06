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
<body>
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
<div class="main">
    <h2>Your links</h2>

</div>
</body>