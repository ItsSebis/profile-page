<?php
session_start();
require_once "../../config.php";
require_once "../publicFunc.php";

if (isset($_POST["valve"]) && $_POST["valve"] === "correct✅" && isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
    unset($_SESSION["waldo"]);
    setUserStat($_SESSION["id"], "foundit", accountData($_SESSION["id"])["foundit"]+1);
    header("location: ./");
    exit();
} elseif (isset($_POST["valve"]) && isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
    setUserStat($_SESSION["id"], "foundit", 0);
    header("location: ./");
    exit();
}



if (!isset($_SESSION["waldo"]) || !is_array($_SESSION["waldo"])) {
    $imgs = array();
    foreach (scandir("imgs") as $img) {
        if ($img != "." && $img != "..") {
            $imgs[] = $img;
        }
    }
    $img = rngArray($imgs);
    try {
        $top = random_int(1, 100000000) / 1000000;
        $left = random_int(1, 100000000) / 1000000;
    } catch (Exception $e) {
        $top = 50;
        $left = 50;
    }

    $findit = array("top" => $top, "left" => $left, "img" => $img);
    $_SESSION["waldo"] = $findit;
} else {
    $waldo = $_SESSION["waldo"];
    $top = $waldo["top"];
    $left = $waldo["left"];
    $img = $waldo["img"];
}
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
<style>
    * {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;

        /*
          Introduced in IE 10.
          See http://ie.microsoft.com/testdrive/HTML5/msUserSelect/
        */
        -ms-user-select: none;
        user-select: none;
    }
    body {
        width: max-content;
        height: max-content;
        /*background-image: url("imgs/waldoes.png");*/
        background-image: url(<?php echo("imgs/".$img); ?>);
        background-repeat: no-repeat;
        background-size: 1920px 1080px;
    }
    button {
        border: none;
        padding: 0;
        margin: 0;
        width: min-content;
        height: min-content;

        position: absolute;
        top: <?php echo($top); ?>%;
        left: <?php echo($left); ?>%;
    }
    button:hover {
        cursor: default;
        border: none;
        padding: 0;
        margin: 0;
        width: min-content;
        height: min-content;
    }
</style>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<?php
if (!isset($_SESSION["id"]) || accountData($_SESSION["id"]) === false) {
    echo '<div class="stats">
    <p>Melde dich an um später deine Statistiken zu sehen.</p><br>
    <a href="../../login.php?path=../projects/finder">-> Login <-</a>
</div>';
} else {
    echo '<div class="stats">
    <h2>Statistiken</h2><br>
    <p>
        Gefunden: <span class="value">'.reformatBIgInts(accountData($_SESSION["id"])["foundit"]).'</span>
    </p>
</div>';
}
?>
<form action="./" method="post">
    <?php
    for ($i=0;$i<60;$i++) {
        echo '<button type="submit" style="visibility: hidden" name="valve" value="correct"><></button>';
    }
    #<img src="imgs/wenda.png" alt="wenda" style="height: 32px; width: auto">
    ?>
    <button type="submit" name="valve" value="correct✅"><p style="opacity: 1;"><img src="V.png" alt="x" style="width: 16px; height: auto;"></p></button>
</form>
</body>
</html>