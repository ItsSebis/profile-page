<?php
$GLOBALS["site"] = "Settings";
include_once "header.php";
if (!isset($_SESSION["id"])) {
    header("location: ./?error=notSignedIn");
    exit();
}

if (isset($_GET["debug"])) {
    if (userHasPerm($_SESSION["id"], "debugs")) {
        $show = (int)accountData($_SESSION["id"])["sdebug"];
        if ($show == 1) {
            $show = 0;
        } else {
            $show = 1;
        }
        setUserStat($_SESSION["id"], "sdebug", $show);
    }
    header("location: settings.php");
    exit();
}

?>
<script type="text/javascript">
    document.getElementById("account").setAttribute("style", "border-bottom: 1px solid #999; color: #999")
</script>
<h1 style="margin-top: 60px; font-size: 3rem">Einstellungen</h1>
<p style="color: gray">Zuletzt abgemeldet: <?php echo(accountData($_SESSION["id"])["lastseen"]); ?></p>
<div class="main" style="text-align: center">
    <h2>Allgemeine Einstellungen</h2>
    <br>
    <table style="width: 75%; margin: 0 auto">
        <thead style="font-size: 1.2rem">
            <tr>
                <th style="width: 70%">Name</th>
                <th style="width: 30%">Einstellung</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="background-color: #3c3c3c">Debug anzeigen (Nur mit Berechtigung)</td>
                <td style="background-color: #3c3c3c"><a href="settings.php?debug">
                    <?php
                    if (accountData($_SESSION["id"])["sdebug"] && userHasPerm($_SESSION["id"], "debugs")) {
                        echo("<p style='color: lime'>Ja</p>");
                    } else {
                        echo("<p style='color: red'>Nein</p>");
                    }
                    ?>
                </td>
            </tr>
            <!--<tr>
                <td style="background-color: #4a4a4a">Test 2</td>
                <td style="background-color: #4a4a4a">Nein</td>
            </tr>-->
        </tbody>
    </table>
</div>
<div class="main" style="text-align: center">
    <h2>Passwort ändern</h2>
    <form action="posts/usermanager.post.php" method="post">
        <input type="password" minlength="8" name="oldPw" placeholder="Altes Passwort..."><br>
        <input type="password" minlength="8" name="pw" placeholder="Neues Passwort..."><br>
        <input type="password" minlength="8" name="pwr" placeholder="Passwort wiederholen..."><br>
        <button type="submit" name="chPw">Submit</button>
    </form>
    <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "invalidPassword") {
                echo "<p style='color: red'>Dies ist nicht dein altes Passwort!<br>Passwort wurde nicht geändert!</p>";
            } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red'>Bitte fülle alle Felder!<br>Passwort wurde nicht geändert!</p>";
            } elseif ($_GET["error"] == "pwr") {
                echo "<p style='color: red'>Diese Passwörter stimmen nicht überein!<br>Passwort wurde nicht geändert!</p>";
            } elseif ($_GET["error"] == "done") {
                echo "<p style='color: lime'>Passwort wurde erfolgreich geändert!</p>";
            }
        }
    ?>
</div>
<div class="main" style="text-align: center">
    <h2>Accountname ändern</h2>
    <form action="posts/usermanager.post.php" method="post">
        <input type="text" minlength="2" name="name" placeholder="Neuer Name..."><br>
        <button type="submit" name="chName">Submit</button>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo "<br>";
        if ($_GET['error'] == 'wrongid') {
            echo '<p style="margin-top: 10px; color: red">Dieser Account existiert!</p>';
        }
        else if ($_GET['error'] == 'invalidid') {
            echo '<p style="margin-top: 10px; color: red">Accountnamen dürfen nur Buchstaben enthalten!</p>';
        }
        else if ($_GET['error'] == 'nameTooShort') {
            echo '<p style="margin-top: 10px; color: red">Accountnamen müssen mindestens 2 Buchstaben haben!</p>';
        }
        else if ($_GET['error'] == 'nameTooLong') {
            echo '<p style="margin-top: 10px; color: red">Accountnamen dürfen maximal 64 Buchstaben haben!</p>';
        }
        else if ($_GET['error'] == 'sebi') {
            echo '<p style="margin-top: 10px; color: red">Accountnamen dürfen nicht "sebi" enthalten!</p>';
        }
        else if ($_GET['error'] == 'time') {
            echo '<p style="margin-top: 10px; color: red">Du hast in den letzten 30 Tagen deinen Accountnamen bereits geändert!</p>';
        }
        else if ($_GET['error'] == 'dona') {
            echo '<p style="margin-top: 10px; color: lime">Accountname erfolgreich geändert zu "'.accountData($_SESSION["id"])["username"].'"!</p>';
        }
    }
    ?>
</div>