<?php
$GLOBALS["site"] = "Home";
include_once "header.php";
?>

<h1 style="margin-top: 60px; font-size: 3rem">Home</h1>
<div class="main" style="text-align: center">
    <p>Moin,<br>
        diese Sachen kannst du dir mal ansehen:<br><br>
        <a target="_self" href="./projects">-> Maine Projekte <-</a><br>
        <a target="_self" href="./users.php">-> Alle Benutzer <-</a><br>
        <a target="_blank" href="https://github.com/itssebis">-> Main GitHub <-</a>
        <a target="_blank" href="./nas">-> Main NAS <-</a><br>
    </p>
    <!--
    <br>
    <h2>Ich kann (vielleicht):</h2>
    <li>Websites erstellen (PHP, HTML, CSS)</li>
    <li>Discord Server verwalten (User/Role management)</li>
    <li>Discord Bots programmieren (JDA)</li>
    <li>Minecraft plugins programmieren (Spigot/Paper)</li>
    -->
    <p style="font-size: 1.3rem">;^)</p>
</div>
<?php

// Errors
if (isset($_GET["error"])) {
    if ($_GET["error"] == "0") {
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Erfolgreich angemeldet!";
    } elseif ($_GET["error"] == "notSignedIn") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Du musst dich anmelden, um diese Seite zu betreten!</p>";
    } elseif ($_GET["error"] == "1") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
MySQL Fehler! Versuche es später erneut!</p>";
    }
}