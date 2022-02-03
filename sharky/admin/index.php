<?php

require_once "../config/dbh.php";
require_once "../extensions/functions.php";
session_start();

if (isset($_POST["login"])) {
    if (empty($_POST["username"]) || empty($_POST["pw"])) {
        header("location: ./?error=emptyf");
        exit();
    }
    $login = $_POST["username"];
    $pw = $_POST["pw"];
    if (opData($login) === false || empty(opData($login)["pw"])) {
        header("location: ./?error=wrongLogin");
        exit();
    }
    if ($pw !== opData($login)["pw"]) {
        header("location: ./?error=wrongPw");
        exit();
    }
    $_SESSION["username"] = $login;
}

if (isset($_GET["logout"]) && isset($_SESSION["username"])) {
    session_unset();
    session_destroy();
    session_start();
}

if (isset($_SESSION["username"]) && isset($_GET["premium"]) && isset($_GET["guild"])) {
    if (guildData($_GET["guild"]) !== false) {
        toggleGuildPremium($_GET["guild"]);
    }
    header("location: ./");
    exit();
}

?>

<html lang="en">
<head>
    <title>Sharky | Admin</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/sharky.png">
</head>
<style>
    * {
        color: white;
        text-align: center;
    }
    body {
        background-color: #363636;
        font-family: "Roboto Mono", monospace;
        font-size: 11pt;
    }
    h1 {
        margin: 35px auto;
        font-size: 3rem;
    }
    table {
        align-items: center;
        float: none;
        margin: 30px auto;
        font-size: larger;
    }
    th {
        padding: 5px 5px 10px;
        background-color: #424242;
        border: 2px solid #424242;
        border-radius: 10px;
        margin: 20px 0;
    }
    td {
        padding: 3px 20px;
        background-color: #424242;
        border: 2px solid #424242;
        border-radius: 10px;
        font-size: 0.95rem;
    }
    a {
        text-decoration: none;
        color: #00cccc;
    }
    a:hover {
        color: #007777;
    }
    a:active {
        color: #00ff9d;
    }
    button {
        background: none;
        width: 200px;
        border-radius: 24px;
        height: 40px;
        color: rgb(199, 199, 199);
        border: solid #333333;
        transition: 0.25s;
    }
    button:hover {
        width: 280px;
        border: solid rgb(0, 255, 221);
        cursor: crosshair;
    }
    .main {
        width: 60%;
        background-color: #262626;
        float: none;
        margin: 10px auto;
        border: 10px solid #262626;
        border-radius: 20px;
    }
    .sub {
        width: 45%;
        background-color: #424242;
        float: none;
        margin: 30px auto;
        border: 10px solid #424242;
        border-radius: 20px;
        padding: 20px;
        text-align: left;
        box-sizing: content-box;
    }
    .login {
        align-items: center;
        text-transform: uppercase;
        width: 360px;
        padding: 40px;
        position: absolute;
        font-weight: 500;
        font-size: larger;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        /*background: url(../img/back.jpg)
        no-repeat;
        background-size: cover;*/
        background-color: #485a52;
        border: 20px solid transparent;
        border-radius: 24px;
    }
    .stats {
        width: 300px;
        background-color: #262626;
        float: left;
        position: absolute;
        top: 20px;
        left: 20px;
        height: fit-content;
        padding: 5px;
        border: 10px solid #262626;
        border-radius: 20px;
    }
    input, select {
        background: none;
        border: solid #333333;
        border-radius: 24px;
        width: 250px;
        color: white;
        margin: 20px auto;
        height: 70px;
        padding: 14px 10px;
        outline: none;
        transition: 0.2s;
        font-size: larger;
    }
    select:hover {
        background-color: #363636;
    }
    option {
        color: #000000;
    }
    input:hover {
        width: 320px;
        background-color: #FFFFF0;
        color: #000000;
        border: solid royalblue;
    }
    input:focus {
        width: 320px;
        border: solid aqua;
    }
</style>
<body>

<?php

if (isset($_SESSION["username"])) {?>

    <a href="./?logout" style="position: absolute; top 20px; right: 20px; float: right;">Logout</a>
    <div class="stats">
        <h2>Statistics</h2>
        <p>Servers: <?php echo(getGuildCount()); ?></p>
        <p>Saved Members: <?php echo(getMemberCount()); ?></p>
        <p>Operators: <?php echo(getOperatorCount()); ?></p>
    </div>
    <div class="main">
        <h1>Dashboard</h1>
        <h3>Welcome, <?php echo($_SESSION["username"]); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>Premium</th>
                </tr>
            </thead>
            <tbody>
                <?php guilds(); ?>
            </tbody>
        </table>
    </div>

<?php
} else {
?>

    <div class="login">
        <h1>Log In</h1>
        <form action="./" method="post">
            <input type="text" name="username" placeholder="Username...">
            <input type="password" name="pw" placeholder="Password...">
            <button type="submit" name="login">Login</button>
        </form>
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'wrongLogin') {
                echo '<p style="margin-top: 10px; color: red">Invalid Username</p>';
            }
            else if ($_GET['error'] == 'wrongPw') {
                echo '<p style="margin-top: 10px; color: red">Invalid Password</p>';
            }
            else if ($_GET['error'] == 'emptyf') {
                echo '<p style="margin-top: 10px; color: red">Fill in all fields!</p>';
            }
        }
        ?>
    </div>

<?php
}
?>
</body>
</html>
