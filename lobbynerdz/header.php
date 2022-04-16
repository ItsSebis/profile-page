<?php
require_once "functions.inc.php";
session_start();
$name = "Nicht angemeldet!";
if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
    $name = accountData($_SESSION["id"])["username"];
}

/* Color Management
 *
 * Cyan: #40B6D6
 * Pink: #B61060
 * Background: #121212
 * Passive: #E081D5
 *
 */

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo("Lobbynerdz | ".$GLOBALS["site"]." | ".$name); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/hobbylos-title.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="nav active">
    <div class="logo_content">
        <div class="logo">
            <i class='bx bxs-microphone' ></i>
            <div class="logo_name">Lobbynerdz</div>
        </div>
        <i class='bx bx-menu' id="btn"></i>
    </div>

    <ul class="nav_list">
        <li>
            <i class='bx bx-search' ></i>
            <input type="text" placeholder="Suchen...">
        </li>

        <?php
        $links = array(
            array("name" => "Start", "link" => "./", "sym" => "bx bxs-home", "target" => "_self"),
            array("name" => "Hören", "link" => "https://open.spotify.com/show/6UUIXmp1V0fK4ZpK7vzAbQ?si=b0bc0fe64eaf4e9c", "sym" => "bx bxl-spotify", "target" => "_blank")
        );

        foreach ($links as $link) {
            echo '
<li>
    <a href="'.$link["link"].'" target="'.$link["target"].'">
        <i class="'.$link["sym"].'"></i>
        <span class="links_name">'.$link["name"].'</span>
    </a>
    <span class="tooltip">'.$link["name"].'</span>
</li>
            ';
        }
        ?>

    </ul>
    <?php
    if (isset($_SESSION["id"])) {
        $data = accountData($_SESSION["id"]);
        $role = roleData($data["role"]);
    ?>
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <img src="../img/person.png" alt="">
                    <div class="name_job">
                        <div class="name"><?php echo($data["username"]) ?></div>
                        <div class="job" style="color: <?php echo($role["color"]) ?>"><?php echo($role["name"]) ?></div>
                    </div>
                </div>
                <a href="../login.php?logout&path=../lobbynerdz"><i class="bx bx-log-out" id="log_out"></i></a>
            </div>
        </div>
    <?php
    } else {?>
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <img src="../img/person.png" alt="">
                    <div class="name_job">
                        <div class="name">Nicht angemeldet</div>
                        <div class="job" style="color: grey">Keine Rolle</div>
                    </div>
                </div>
                <a href="../login.php?logout&path=../lobbynerdz"><i class="bx bx-log-in" id="log_out"></i></a>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<script>
    let btn = document.querySelector("#btn");
    let sidebar = document.querySelector(".nav");
    let search = document.querySelector(".bx-search");

    btn.onclick = function () {
        sidebar.classList.toggle("active")
    }
    search.onclick = function () {
        sidebar.classList.toggle("active")
    }
</script>

<div class="content">
