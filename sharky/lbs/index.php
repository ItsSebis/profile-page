<?php

require_once "../config/dbh.php";
require_once "../extensions/functions.php";
?>

<html lang="en">
    <head>
        <title>Sharky | Leaderboards</title>
        <meta charset="utf-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
        <link rel="icon" href="../img/sharky.png">
    </head>
    <style>
        * {
            color: white;
        }
        body {
            background-color: #363636;
            font-family: "Roboto Mono", monospace;
            font-size: 11pt;
        }
        h1 {
            margin: 35px auto;
            text-align: center;
            font-size: 3rem;
        }
        h3 {
            text-align: center;
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
        .all-guilds {
            float: top;
            margin: 0 auto;
            max-width: 60%;
        }
        .guild {
            box-sizing: content-box;
            display: inline-block;
            width: 290px;
            float: none;
            height: fit-content;
            padding: 15px;
            background-color: #424242;
            margin: 20px;
            border: solid #424242;
            border-radius: 24px;
        }
        .user {
            text-align: center;
            width: 75%;
            float: none;
            height: fit-content;
            padding: 15px;
            background-color: #424242;
            margin: 20px auto;
            border: solid #424242;
            border-radius: 24px;
        }
        .invite {
            position: fixed;
            top: 10px;
            right: 20px;
            float: right;
        }
        .value {
            color: #00cccc;
        }
    </style>
    <body>
        <a class="invite" href="https://www.sebis.net/bot">Add to Discord</a>
        <?php
            if (!isset($_GET["guild"]) || guildData($_GET["guild"]) === false) {
        ?>
                <a href='../' style='position: absolute; top: 5px; left: 2px;'><- Back</a>
                <h1>All Sharky-Servers</h1>
                <div class="all-guilds">
                    <?php
                    if (!isset($_GET["error"])) {
                        guildDivs();
                    } else {
                        echo "<h1 style='color: red; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>Internal error!</h1>";
                    }
                    ?>
                </div>
        <?php
            } else {
                guildLb($_GET["guild"]);
            }
        ?>
    </body>
</html>
