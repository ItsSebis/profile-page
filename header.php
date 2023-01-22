<?php
    require_once "config.php";
    require_once "projects/publicFunc.php";
    session_start();
    if (!isset($_SESSION["id"]) && isset($_GET["token"]) && accountDataByToken($_GET["token"]) !== false) {
        $account = accountDataByToken($_GET["token"]);
        login($account["id"]);
    }
    $name = "Nicht angemeldet!";
    if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
        $name = accountData($_SESSION["id"])["username"];
    }
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo($GLOBALS["site"]." | ".$name); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="img/title-bar.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="nav">
    <ul>
<!--    <img src="img/title-bar.png" alt="pp" style="width: 16px; height: auto;">-->
        <li><a href="./" style="background: none; border: none; float: none;"><i style="color: #00cccc" class='bx bxs-home'></i></a></li>
        <?php
            if (!isset($_SESSION["id"])) {
                echo '<li><a target="_self" href="login.php" class="navlinks" style="color: #00ff9d" id="login">Login</a></li>';
            } else {
                echo '<li><a target="_self" href="settings.php" class="navlinks" style="color: #d5d5d5" id="account">Account</a>';
                echo '<ul>';
                if (userHasPerm($_SESSION["id"], "admin")) {
                    echo '<li><a target="_self" href="admin.php" class="navlinks" style="color: #f44" id="manage">Management</a></li>';
                }
                echo '<li><a target="_self" href="login.php?logout" class="navlinks" style="color: #e75">Logout</a></li></ul></li>';
            }
        ?>

        <li>
            <a target="_self" href="#" class="navlinks other" id="login"><i style="color: #199061" class='bx bx-chevrons-right bx-chevrons-down'></i></a>
            <ul class="navi-hidden">
                <li><a target="_self" href="projects" class="navlinks" style="color: #00a7ff">Projects</a></li>
<!--                <li><a target="_blank" href="https://sebis.net/nas" class="navlinks" style="color: #41d0ff">NAS</a></li>-->
                <li><a target="_blank" href="https://github.com/ItsSebis" class="navlinks" style="color: #4f64b1">GitHub</a></li>
                <li><a target="_blank" href="smp.php" class="navlinks" style="color: #444ad3">Minecraft SMP</a></li>
            </ul>
        </li>
    </ul>

    <!--    <a target="_blank" href="https://www.sebis.net/downloads/" class="navlinks" style="color: #e74">Downloads</a>-->
</div>