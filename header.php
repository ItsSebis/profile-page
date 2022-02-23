<?php
    session_start();
    require_once "config.php";
    require_once "projects/publicFunc.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>ItsSebis | <?php echo($GLOBALS["site"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="img/title-bar.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="nav">
    <a href="./" style="background: none; border: none; float: none;"><img src="img/title-bar.png" alt="pp" style="width: 16px; height: auto;"></a>
    <?php
        if (!isset($_SESSION["id"])) {
            echo '<a target="_self" href="login.php" class="navlinks" style="color: #00ff9d" id="login">Login</a>';
        } else {
            echo '<a target="_self" href="settings.php" class="navlinks" style="color: #999" id="account">Account</a>';
            if (roleData(accountData($_SESSION["id"])["role"])["admin"]) {
                echo '<a target="_self" href="admin.php" class="navlinks" style="color: #f44" id="manage">Management</a>';
            }
            echo '<a target="_self" href="login.php?logout" class="navlinks" style="color: #e75">Logout</a>';
        }
    ?>

    <!--    <a target="_blank" href="https://www.sebis.net/downloads/" class="navlinks" style="color: #e74">Downloads</a>-->
</div>