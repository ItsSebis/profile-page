<?php
require_once "../../config.php";
require_once "../publicFunc.php";
session_start();

if (isset($_SESSION["authrate"]) && is_array($_SESSION["authrate"])) {
    $data = $_SESSION["authrate"];
} else {
    $data = array("stage" => 0, "score" => 0);
}

if (isset($_POST["next"])) {
    $data["stage"] = $data["stage"]+1;
    $data["score"] = $data["score"]+$_POST["next"];
    $_SESSION["authrate"] = $data;
    header("location: ./");
    exit();
}

if (isset($_GET["reset"]) || isset($_POST["reset"])) {
    unset($_SESSION["authrate"]);
    header("location: ./");
    exit();
}

$content = "";

switch ($data["stage"]) {
    case 0: {
        $content .= "<h3>Start</h3><p>Bist du bereit für deine Onlinesicherheitszusammenfassung in einem Wert?<br>Dann beginne hier!</p><br>
<button type='submit' name='next' value='0'>Start</button><br>
";
        break;
    }
    case 1: {
        $content .= "<h3>Passwörter 1</h3><p>Wie sind deine Passwörter aufgebaut?</p><br>
<button type='submit' name='next' value='1'>Ich verwende ein Passwort, das ich mir gut merken kann.</button><br>
<button type='submit' name='next' value='2'>Ich verwende verschiedene Passwörter, die aber nicht sehr kompliziert zu merken sind.</button><br>
<button type='submit' name='next' value='3'>Ich verwende ein Passwort, das man sich wirklich nicht merken kann und ich mir deshalb irgendwo aufgeschrieben habe.</button><br>
<button type='submit' name='next' value='4'>Ich verwende verschiedene Passwörter, die man sich nicht merken kann und ich mir deshalb irgendwo aufgeschrieben habe.</button><br>
";
        break;
    }
    case 2: {
        $content .= "<h3>Passwörter 2</h3><p>Wie sind deine Passwörter gespeichert?</p><br>
<button type='submit' name='next' value='1'>Ich habe mir meine Passwörter auf einem Zettel aufgeschrieben.</button><br>
<button type='submit' name='next' value='2'>Ich merke mir alle meine Passwörter.</button><br>
<button type='submit' name='next' value='3'>Ich habe mir meine Passwörter in einer Notiz o.ä. auf meinem Handy oder PC gesichert.</button><br>
<button type='submit' name='next' value='4'>Ich verwende einen Passwortmanager, der auch gut gesichert ist.</button><br>
";
        break;
    }
    case 3: {
        $content .= "<h3>Passwörter 3</h3><p>Nutzt du MFA?</p><br>
<button type='submit' name='next' value='1'>Was ist das?</button><br>
<button type='submit' name='next' value='2'>Ich nutze für die wichtigsten Sachen 2FA, aber auch, nur wenn ich dran denke.</button><br>
<button type='submit' name='next' value='3'>Ich nutze für so viel wie möglich 2FA.</button><br>
<button type='submit' name='next' value='4'>Ich nutze für alles 2FA wo es geht, und so viel wie möglich 3FA oder Security Keys.</button><br>
";
        break;
    }
    case 4: {
        $content .= "<h3>Cookies</h3><p>Wie ist dein Umgang mit den Keksen aus dem Internet?</p><br>
<button type='submit' name='next' value='1'>Mir sind Cookies komplett egal, ich akzeptiere sie immer.</button><br>
<button type='submit' name='next' value='2'>Wenn es nicht zu umständlich ist versuche ich diese nervigen Dinger abzuwehren.</button><br>
<button type='submit' name='next' value='3'>Ich versuche schon viel, um Cookies zu blockieren, scheitere aber manchmal und versuche es dann auch nicht immer mehr.</button><br>
<button type='submit' name='next' value='4'>Bei mir kommen keine Cookies in die Tüte! Alle werden stetig abgeblockt! Und wenn die möglichkeit nicht besteht, wird die Website verlassen.</button><br>
";
        break;
    }
    case 5: {
        $content .= "<h3>Ortungsdienste</h3><p>Wie sind die Ortungsdienste auf deinen Geräten eingerichtet?</p><br>
<button type='submit' name='next' value='1'>Mir sind die Ortungsdienste sehr egal, ich erlaube sie meistens.</button><br>
<button type='submit' name='next' value='2'>Es kommt schon häufiger vor, dass ich auch mal diese Sachen deaktiviere.</button><br>
<button type='submit' name='next' value='3'>Sie werden nur zum Informieren meiner Familie/Freunden genutzt (Bsp. \"Wo ist?\").</button><br>
<button type='submit' name='next' value='4'>Nix Ortungsdienste.</button><br>
";
        break;
    }
    case 6: {
        $content .= "<h3>Codes 1</h3><p>Wie wählst du deine Codes?</p><br>
<button type='submit' name='next' value='1'>Meine Codes sind eigentlich immer Geburtsdaten naher Bekannter oder von mir.</button><br>
<button type='submit' name='next' value='2'>Jegliche andere besondere Daten für mich im Jahr außer Geburtstage.</button><br>
<button type='submit' name='next' value='3'>Jegliche andere besondere Zahlenkombinationen für mich außer Daten.</button><br>
<button type='submit' name='next' value='4'>Zufällige Zahlen, die mit nichts im Zusammenhang stehen.</button><br>
";
        break;
    }
    case 7: {
        $content .= "<h3>Codes 2</h3><p>Wer kennt deine Codes/Passwörter?</p><br>
<button type='submit' name='next' value='1'>Jeder meiner Freunde kennt sie besser als ich.</button><br>
<button type='submit' name='next' value='2'>Mehrere meiner Freunde.</button><br>
<button type='submit' name='next' value='3'>Nur meine besten Freunde.</button><br>
<button type='submit' name='next' value='4'>Nur die, denen ich auch mein Leben anvertrauen würde.</button><br>
";
        break;
    }
    case 8: {
        $content .= "<h3>E-Mails</h3><p>Nutzt du mehrere E-Maildienste für verschiedene Anwendungszwecke?</p><br>
<button type='submit' name='next' value='1'>Ich habe eine E-Mail-Adresse für alles.</button><br>
<button type='submit' name='next' value='2'>Ich habe vielleicht so 2-3 Mailadressen die ich aber nicht speziell zugeteilt habe.</button><br>
<button type='submit' name='next' value='3'>Ich nutze so 3-5 Mailadressen für die größeren Bereiche.</button><br>
<button type='submit' name='next' value='4'>Ich nutze fast für jeden neuen Dienst eine andere E-Mail-Adresse.</button><br>
";
        break;
    }
    case 9: {
        $content .= "<h3>Glauben</h3><p>Wem glaubst du in sozialen Medien?</p><br>
<button type='submit' name='next' value='1'>Wenn jemand mir direkt Tatsachen schildert, die stimmen könnten glaube ich ihm das auch.</button><br>
<button type='submit' name='next' value='2'>Wenn irgendwer mir etwas schreibt glaube ich das nicht direkt, aber meinen Freunden glaube ich jedes Wort.</button><br>
<button type='submit' name='next' value='3'>Auch bei meinen Freunden bin ich manchmal kritisch.</button><br>
<button type='submit' name='next' value='4'>Ich glaube niemandem, erstrecht nicht, wenn etwas sehr random kommt.</button><br>
";
        break;
    }
    case 10: {
        $content .= "<h3>VPN</h3><p>Wie sieht es bei dir mit der Nutzung von VPN aus?</p><br>
<button type='submit' name='next' value='1'>Noch nie genutzt.</button><br>
<button type='submit' name='next' value='2'>Vielleicht mal genutzt für Netflix.</button><br>
<button type='submit' name='next' value='3'>Ich nutze VPN manchmal, um mich ein bisschen Anonymer zu machen.</button><br>
<button type='submit' name='next' value='4'>Ich nutze VPN so viel wie möglich, auch vor dem Tor-Netzwerk zögere ich nicht.</button><br>
";
        break;
    }
    case 11: {
        $content .= "<h3>Dateisystem</h3><p>Wie speicherst du deine Dateien?</p><br>
<button type='submit' name='next' value='1'>Lokal oder auf einem USB-Stick.</button><br>
<button type='submit' name='next' value='2'>Meistens auf der internen Festplatte meines PCs.</button><br>
<button type='submit' name='next' value='3'>Meistens auf einer größeren Festplatte oder auf öffentlichen Cloud Diensten.</button><br>
<button type='submit' name='next' value='4'>Die meisten Dateien auf einem eigenen NAS.</button><br>
";
        break;
    }

    case 12: {
        $content .= "<h3>Eigeneinschätzung</h3><p>Wie schätzt du dich selber ein bist du online sicher?</p><br>
<button type='submit' name='next' value='3'>25%</button><br>
<button type='submit' name='next' value='3'>50%</button><br>
<button type='submit' name='next' value='3'>75%</button><br>
<button type='submit' name='next' value='3'>100%</button><br>
";
        break;
    }
    default: {
        $score = $data["score"];
        $percent = round(100 / (($data['stage'] - 1) * 4) * $score, 2);
        $content .= "<h3>Ergebnis</h3><p>Du bist nach meinen Berechnungen zu ".$percent."% Online geschützt! Aber du wirst niemals zu 100 % online sicher sein, das ist unmöglich.</p><br><br>
        <button type='submit' name='reset'>Reset</button><br>
        ";
    }
}

$_SESSION["authrate"] = $data;
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
    h3 {
        font-size: 1.4rem;
    }
    p {
        font-size: 1.3rem;
    }
    button {
        padding: 10px;
        height: fit-content;
        width: 350px;
        min-width: 350px;
        margin-bottom: 12px;
        font-size: 1.05rem;
        line-height: 1.25;
    }
</style>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
    <h1>Deine Onlinesicherheitsrate</h1>
<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-height: 75%; max-width: 75%;
overflow: hidden; overflow-y: initial; width: 60%; background-color: #262626; border: 9px solid #262626; border-radius: 20px">
    <form action="." method="post">
        <?php
        if ($data["stage"] > 1 && isset($_SESSION["id"]) && userHasPerm($_SESSION["id"], "debugs") && accountData($_SESSION["id"])["sdebug"]) {
            echo "Score: " . $data["score"] . " | " . (100 / (($data['stage'] - 1) * 4) * $data['score']) . "%<br><br>";
        }
        echo $content;
        ?>
    </form>
</div>
</body>
</html>