<?php
$GLOBALS["site"] = "Settings";
include_once "header.php";
if (!isset($_SESSION["id"])) {
    header("location: ./?error=notSignedIn");
    exit();
}
?>
<script type="text/javascript">
    document.getElementById("account").setAttribute("style", "border-bottom: 1px solid #999; color: #999")
</script>
<h1 style="margin-top: 60px; font-size: 3rem">Einstellungen</h1>
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