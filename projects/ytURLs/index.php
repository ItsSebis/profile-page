<?php

require_once "../../config.php";
require_once "../publicFunc.php";

if (isset($_POST["vidid"]) && !empty($_POST["vidid"])) {
    if (yt_exists($_POST["vidid"])) {
        $data = yt_info($_POST["vidid"]);
        $result = "<span style='color: #3f3'>Video gefunden!</span><br><br><a href='https://youtu.be/".$_POST["vidid"]."' target='_blank'>-> Zum Video <-</a><br><br>
Titel <br><span style='color: #00cccc'>".$data['title']."</span><br><br>
Creator <br><span style='color: #00cccc'>".$data['author_name']."</span>";
    } else {
        if (strlen($_POST["vidid"]) == 11) {
            $result = "<span style='color: #f33'>Kein Video gefunden!</span>";
        } else {
            $result = "<span style='color: #f33'>Video IDs haben genau 11 Zeichen!</span>";
        }

        /*if (strlen($_POST["vidid"]) < 11) {
            $try = $_POST["vidid"];
            for ($i=strlen($_POST["vidid"]);$i<11;$i++) {
                $try.=randomLetter();
            }
            while (!yt_exists($try)) {
                $try = $_POST["vidid"];
                for ($i=strlen($_POST["vidid"]);$i<11;$i++) {
                    $try.=randomLetter();
                }
            }
        }*/
    }
}

?>

<html lang="de">
<head>
    <title>Projekte | <?php echo(projectData(basename(__DIR__))["name"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-height: 75%; max-width: 75%;
overflow: hidden; overflow-y: initial; width: 60%; background-color: #333333; border: 9px solid #333333; border-radius: 20px">
    <form action="./" method="post">
        <input maxlength="11" name="vidid" placeholder="Video ID..." <?php if (isset($_POST["vidid"])){echo("value=\"".$_POST['vidid']."\"");} ?>><br>
        <button type="submit" name="yt">Suchen</button>
    </form>
    <p><?php if (isset($result)){echo("<br>".$result);} ?></p>
</div>
</body>
</html>
