<?php
$GLOBALS["site"] = "Management";
include_once "header.php";
if (!isset($_SESSION["id"]) || !userHasPerm($_SESSION["id"], "admin")) {
    header("location: ./?error=noperm");
    exit();
}
?>
<div class="main" style="margin-top: 60px;">
    <form action="admin.php">
        <button type='submit' name='page' value="home">Start</button>
        <button type='submit' name='page' value="users">Benutzer</button>
        <button type='submit' name='page' value="roles">Rollen</button>
        <?php /*<button type='submit' name='page' value="mail" <?php if (!userHasPerm($_SESSION["id"], "mail")) {echo("disabled");} ?>>Mail</button>*/?>
    </form>
</div>
<script type="text/javascript">
    document.getElementById("manage").setAttribute("style", "border-bottom: 1px solid #f44; color: #f44")
</script>
<?php

if (!isset($_GET["page"]) || $_GET["page"] == "home") {?>
    <h1 style="font-size: 3rem; margin-top: 60px">Management</h1>
    <div class="main">
        <h2>Statistiken</h2>
        <p>
            Benutzer: <span style="color: #00cccc"><?php echo(count(usersArray())); ?></span><br>
            Admins: <span style="color: #00cccc"><?php echo(countUsersByRole(1)); ?></span><br>
            Rollen: <span style="color: #00cccc"><?php echo(count(rolesArray())); ?></span><br>
        </p>
    </div>
<?php
} elseif ($_GET["page"] == "users") {
    if (!isset($_GET["usr"]) || accountData($_GET["usr"]) === false) {
    ?>
    <h1 style="font-size: 3rem; margin-top: 60px">Benutzer</h1>
    <div class="main">
        <?php userList(); ?>
    </div>
<?php
    } else {
        $data = accountData($_GET["usr"]);
        ?>
        <div class="main">
            <a href="admin.php?page=users" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem"><?php echo($data['username']); ?></h1>
            <form action="posts/usermanager.post.php" method="post">
                <input type="hidden" name="user" value="<?php echo($_GET["usr"]); ?>">
<!--                <input type="text" name="newacc" placeholder="Account..." style="width: 500px;"-->
<!--                       value="--><?php //echo($data['username']); ?><!--"><br>-->
                <?php /*<input type="text" name="nick" placeholder="Nickname..." value="<?php echo(userData(con(), $_GET["usr"])["nick"]); ?>"><br>*/ ?>
                <?php
                    roleSelector($_GET["usr"]);
                ?>
                <button type="submit" name="respw" style="margin-bottom: 7px;" <?php if (!userHasPerm($_SESSION["id"], "respw")) {echo("disabled");} ?>>Passwort zurücksetzten</button>
                <br>
                <button type="submit" name="edit" style="margin-bottom: 7px;" <?php if ($_GET["usr"] == $_SESSION["id"]) {echo("disabled");} ?>>Bearbeiten</button>
                <br>
                <button type="submit" name="del">Löschen</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "respw") {
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Passwort erfolgreich zurückgesetzt auf ".$_GET['pw']."!";
                } elseif ($_GET["error"] == "delself") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Lösch dich... nicht!</p>";
                } elseif ($_GET["error"] == "noPerm") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Dazu hast du nicht genug Rechte!</p>";
                } elseif ($_GET["error"] == "setRole") {
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Rolle erfolgreich bearbeitet!</p>";
                }
            } ?>
        </div>
<?php
    }
} elseif ($_GET["page"] == "roles") {
    if ((!isset($_GET["role"]) || roleData($_GET["role"]) === false) && !isset($_GET["create"])) {
    ?>
    <h1 style="font-size: 3rem; margin-top: 60px">Rollen</h1>
    <div class="main">
        <?php rolesList(); ?>
    </div>
    <?php
    } elseif (isset($_GET["role"])) {

    } else {

    }
}

/* elseif ($_GET["page"] == "mail") {
    if (!userHasPerm($_SESSION["id"], "mail")) {
        header("location: admin.php?error=noPerm");
        exit();
    }
    */?><!--
    <h1 style="font-size: 3rem; margin-top: 60px">Mail</h1>
    <div class="main">
        <form action="posts/mail.php" method="post">
            Via: <?php /*mailSelector(); */?><br>
            <input name="to" placeholder="An..."><br>
            <input name="subject" placeholder="Betreff..."><br>
            <textarea name="text" style="text-align: center"></textarea><br>
            <button type="submit" name="send">Senden</button>
        </form>
        <?php
/*        if (isset($_GET["error"])) {
            if ($_GET["error"] == "invalidTo") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Die Empfänger E-Mail ist nicht gültig!</p>";
            } elseif ($_GET["error"] == "sent") {
                echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Mail erfolgreich gesendet!</p>";
            } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Fülle bitte alle Felder!</p>";
            }
        }
        */?>
    </div>
    --><?php
/*}*/