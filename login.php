<?php
$GLOBALS["site"] = "Login";
include_once "header.php";
if (isset($_GET["logout"]) && isset($_SESSION["id"])) {
    setUserSeen($_SESSION["id"]);
    session_unset();
    session_destroy();
    $path = "./";
    if (isset($_GET["path"])) {
        $path = $_GET["path"];
    }
    header("location: ".$path);
    exit();
} elseif (isset($_SESSION["id"])) {
    $path = "./";
    if (isset($_GET["path"])) {
        $path = $_GET["path"];
    }
    header("location: ".$path);
    exit();
}
?>

<style media='screen'>
    p {
        color: red;
    }
    .scan {
        position: absolute;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        top: 50%;
        left: 50%;
        height: 65%;
        width: 45%;
        background-color: #111;
        transform: translate(-50%, -50%);
        border: solid #111;
        border-radius: 30px;
        visibility: hidden;
        transition: 125ms;
    }

    .scan .fingerprint {
        position: relative;
        width: 300px;
        height: 380px;
        background: url("img/fingerPrint_01.png");
        background-size: 300px;
    }

    .scan .fingerprint::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("img/fingerPrint_02.png");
        background-size: 300px;
        animation: animate 2s ease-in-out infinite;
    }

    @keyframes animate {
        0%,100% {
            height: 0;
        }
        50% {
            height: 100%;
        }
    }

    .scan .fingerprint::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        border-radius: 8px;
        filter: drop-shadow(0 0 20px #3fefef) drop-shadow(0 0 60px #3fefef);
        background: #3fefef;
        background-size: 300px;
        animation: animate_line 2s ease-in-out infinite;
    }

    @keyframes animate_line {
        0%,100% {
            top: 0;
        }
        50% {
            top: 100%;
        }
    }
</style>
<script type="text/javascript">
    document.getElementById("login").setAttribute("style", "border-bottom: 1px solid #00ff9d; color: #00ff9d");

    function scan() {
        document.getElementById("finger-ov").style.visibility = "visible";
    }
</script>

<?php
$urlExtensions = "";
if (count($_GET) > 0) {
    foreach ($_GET as $key => $value) {
        $urlExtensions.=$key."=".$value."&";
    }
}

if (!isset($_GET["register"])) {
?>

<div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
    <h2>LOGIN</h2>
    <form action='<?php echo("posts/login.post.php?".$urlExtensions); ?>' method='post'>
        <input type='text' name='usr' placeholder='User/Email...'><br>
        <input type='password' name='pw' placeholder='Passwort...'><br>
        <button type='submit' name='login' onclick="scan()">Login</button><br>
        <p style="font-size: medium; color: #fa3">Noch kein Benutzer? <br><a href="login.php?register&<?php //echo($urlExtensions); ?>">Hier registrieren!</a></p>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo "<br>";
        if ($_GET['error'] == 'wrongid') {
            echo '<p style="margin-top: 10px;">Falsche Anmeldedaten!</p>';
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
} else {
    ?>
    <div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
        <h2>Register</h2>
        <form action='posts/usermanager.post.php?<?php echo($urlExtensions); ?>' method='post'>
            <input type='text' name='usr' placeholder='Username...'><br>
            <input type='text' name='email' placeholder='Email...'><br>
            <input type='password' name='pw' placeholder='Passwort...'><br>
            <input type='password' name='pwr' placeholder='Passwort wiederholen...'><br>
            <button type='submit' name='register' onclick="scan()">Register</button><br>
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
?>

<div id="finger-ov" class="scan">
    <div class="fingerprint"></div>
    <h3>Authentifizierung...</h3>
</div>
