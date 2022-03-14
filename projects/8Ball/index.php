<?php
require_once "../../config.php";
require_once "../publicFunc.php";
?>
<html lang="de">
<head>
    <title>Projects | <?php echo(projectData(basename(__DIR__))["name"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<style>
    span {
        font-style: italic;
    }
    button {
        width: 320px;
    }
    button:hover {
        width: 320px;
    }
</style>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>

<?php

function rngArray($array) {
    try {
        return $array[random_int(0, count($array)-1)];
    } catch (Exception $e) {
        return false;
    }
}

function rngTime() {
    try {
        $hour = random_int(0, 23);
        $min = random_int(0, 59);
        if ($hour < 10) {
            $hour = "0" . $hour;
        }
        if ($min < 10) {
            $min = "0" . $min;
        }
        return $hour . ":" . $min;
    } catch (Exception $e) {
        return "25:61";
    }
}

$yeaReplies = array("YES!", "Yea", "yep", "YEP", "Absolut!", "Meine Quellen stimmen zu.");
$nopeReplies = array("Ne", "nope", "Auf keinen Fall!", "Niemals!", "Meine Quellen stimmen nicht zu.", "NOPERS");
$maybeReplies = array("Maybe", "Da bin ich mir nicht sicher...", "Darüber sind sich meine Quellen nicht einig.");

$weekdays = array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag");
try {
    $times = array("Morgen", "Übermorgen", "Nächsten ".rngArray($weekdays), "Jeden Tag");
} catch (Exception $e) {
    $times = array("Morgen", "Übermorgen", "Jeden Tag", "Am 13.3.2020");
}

$answer = "<span style='color: #4cf;'>Ask the 8Ball!</span>";
if (isset($_POST["yeno"])) {
    try {
        $rng = random_int(1, 1000);
    } catch (Exception $e) {
        header("location: ./");
        exit();
    }

    if ($rng == 1) {
        $answer = "<span style='color: #FFD633FF;'>\"".rngArray($maybeReplies)."\"</span>";
    } elseif ($rng > 500) {
        $answer = "<span style='color: #f04;'>\"".rngArray($nopeReplies)."\"</span>";
    } else {
        $answer = "<span style='color: #3f3;'>\"".rngArray($yeaReplies)."\"</span>";
    }
} elseif (isset($_POST["time"])) {
    $answer = "<span style='color: #AF44FFFF;'>\"".rngArray($times)." um ".rngTime()."\"</span>";
    if (rngByPerCent(7.5)) {
        $answer = "<span style='color: #AF44FFFF;'>\"Nie\"</span>";
    }
}
?>

<body>
<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <p><?php if (isset($_POST["quest"]) && !empty($_POST["quest"])) {echo("Frage: \"".$_POST["quest"]."\"");} ?></p><br>
    <img src="img.png" alt="8Ball" style="height: 256px; width: 256px">
    <p style="margin-top: 30px; margin-bottom: 20px;"><?php echo($answer); ?></p>
    <form action="./" method="post">
        <textarea name="quest" minlength="10" placeholder="Deine Frage bitte..." rows="1" cols="50" style="height: 45px; min-height: 45px; max-height: 45px;"></textarea><br><br>
        <p style="color: grey;">Antworttypen:</p><br>
        <button type="submit" name="yeno">Ja/Nein</button>
        <button type="submit" name="time">Wann?</button>
    </form>
</div>
</body>
</html>