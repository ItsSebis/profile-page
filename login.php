<?php
if (isset($_GET["logout"])) {
    session_start();
    session_unset();
    session_destroy();
    header("location: ./");
    exit();
}
include_once "header.php";
?>

<style media='screen'>
    p {
        color: red;
    }
</style>
<script type="text/javascript">
    document.getElementById("login").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
</script>

<div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
    <h2>LOGIN</h2>
    <form action='posts/login.post.php' method='post'>
        <input type='text' name='usr' placeholder='User/Email...'><br>
        <input type='password' name='pw' placeholder='Passwort...'><br>
        <button type='submit' name='login'>Login</button><br>
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
            echo '<p style="margin-top: 10px;">Fill in all fields!</p>';
        }
        else if ($_GET['error'] == 'disabled') {
            echo '<p style="margin-top: 10px;">This account is disabled!</p>';
        }
    }
    ?>
</div>
