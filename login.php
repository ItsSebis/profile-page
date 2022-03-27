<?php
$GLOBALS["site"] = "Login";
include_once "header.php";
if (isset($_GET["logout"])) {
    setUserSeen($_SESSION["id"]);
    session_unset();
    session_destroy();
    header("location: ./");
    exit();
}
?>

<style media='screen'>
    p {
        color: red;
    }
</style>
<script type="text/javascript">
    document.getElementById("login").setAttribute("style", "border-bottom: 1px solid #00ff9d; color: #00ff9d")
</script>

<?php
if (!isset($_GET["register"])) {
?>

<div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
    <h2>LOGIN</h2>
    <form action='posts/login.post.php' method='post'>
        <input type='text' name='usr' placeholder='User/Email...'><br>
        <input type='password' name='pw' placeholder='Passwort...'><br>
        <button type='submit' name='login'>Login</button><br>
        <p style="font-size: medium; color: #fa3">Noch kein Benutzer? <br><a href="login.php?register">Hier registrieren!</a></p>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo "<br>";
        if ($_GET['error'] == 'wrongid') {
            echo '<p style="margin-top: 10px;">Dieser Account existiert nicht!</p>';
        }
        else if ($_GET['error'] == 'wrongpw') {
            echo '<p style="margin-top: 10px;">Falsches Passwort</p>';
        }
        else if ($_GET['error'] == 'emptyf') {
            echo '<p style="margin-top: 10px;">Fülle bitte alle Felder!</p>';
        }
        else if ($_GET['error'] == 'disabled') {
            echo '<p style="margin-top: 10px;">This account is disabled!</p>';
        }
    }
    ?>
</div>
<?php
} else {?>
    <div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
        <h2>Register</h2>
        <form action='posts/usermanager.post.php' method='post'>
            <input type='text' name='usr' placeholder='Username...'><br>
            <input type='text' name='email' placeholder='Email...'><br>
            <input type='password' name='pw' placeholder='Passwort...'><br>
            <input type='password' name='pwr' placeholder='Passwort wiederholen...'><br>
            <button type='submit' name='register'>Register</button><br>
        </form>
        <?php
        if (isset($_GET['error'])) {
            echo "<br>";
            if ($_GET['error'] == 'wrongid') {
                echo '<p style="margin-top: 10px;">Dieser Account existiert!</p>';
            } elseif ($_GET['error'] == 'wrongmail') {
                echo '<p style="margin-top: 10px;">Diese E-Mail ist bereits einem Account zugeordnet!</p>';
            }
            else if ($_GET['error'] == 'wrongpw') {
                echo '<p style="margin-top: 10px;">Diese Passwörter stimmen nicht überein!</p>';
            }
            else if ($_GET['error'] == 'invalidid') {
                echo '<p style="margin-top: 10px;">Accountnamen dürfen nur Buchstaben enthalten!</p>';
            }
            else if ($_GET['error'] == 'invalidmail') {
                echo '<p style="margin-top: 10px;">Diese E-Mail wurde vom System als ungültig markiert!</p>';
            }
            else if ($_GET['error'] == 'emptyf') {
                echo '<p style="margin-top: 10px;">Fülle bitte alle Felder!</p>';
            }
            else if ($_GET['error'] == 'nameTooShort') {
                echo '<p style="margin-top: 10px;">Accountnamen müssen mindestens 2 Buchstaben haben!</p>';
            }
            else if ($_GET['error'] == 'nameTooLong') {
                echo '<p style="margin-top: 10px;">Accountnamen dürfen maximal 64 Buchstaben haben!</p>';
            }
            else if ($_GET['error'] == 'pwTooShort') {
                echo '<p style="margin-top: 10px;">Passwörter müssen mindestens 8 Zeichen haben!</p>';
            }
            else if ($_GET['error'] == 'sebi') {
                echo '<p style="margin-top: 10px;">Accountnamen dürfen "sebi" nicht enthalten!</p>';
            }
        }
        ?>
    </div>
<?php
}
