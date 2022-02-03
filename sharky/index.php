<?php

//require_once "config/dbh.php";
require_once "extensions/functions.php";
session_start();
?>

<html lang="en">
<head>
    <title>Sharky | The Discord Bot</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="img/sharky.png">
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
    tr {
        margin: 20px 0;
    }
    th {
        padding: 5px 5px 10px;
        background-color: #424242;
        border: 2px solid #424242;
        border-radius: 10px;
        margin: 20px 0;
    }
    td {
        padding: 20px 0;
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
    .invite {
        position: fixed;
        background-color: #6652EC;
        height: fit-content;
        width: 250px;
        border: 10px solid #6652EC;
        border-radius: 15px;
        top: 10px;
        right: 20px;
        float: right;
        color: white;
    }
    .invite p {
        text-align: center;
        position: center;
        font-size: 16pt;
    }
    .invite:hover {
        color: white;
        background-color: #3430A8;
        border-color: #3430A8;
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
</style>
<body>
<a class="invite" href="https://www.sebis.net/bot"><p>Add to Discord</p></a>
<div class="stats">
    <h2>Statistics</h2>
    <p>Servers: <?php //echo(getGuildCount()); ?></p>
    <p>Saved Members: <?php //echo(getMemberCount()); ?></p>
    <p>Operators: <?php //echo(getOperatorCount()); ?></p>
</div>
<div class="main">
    <h1>Features:</h1>
    <div class="sub">
        <h2>Levels</h2>
        <p>You gain xp for every message you sent.<br>If your server is premium-enabled you can set a xp multiplier, which
            applies to every message.<br><br>You can see the leaderboard of every server online <a href="https://www.sebis.net/sharky/lbs">here</a>!<br><br>
        Admins can set up "levelroles". With the command '+levelrole set (level) <@role>'. If you´ve reached the specified level, the role will be added to you.
        </p>
    </div>
    <div class="sub">
        <h2>Moderation</h2>
        <p>There are some moderation commands like ban, kick, mute or warn.<br>The amount of warns a user has will decrease the amount of xp for each message!<br>
        You can delete messages with the command 'purge (amount)'</p>
    </div>
    <div class="sub">
        <h2>Misc</h2>
        <p>You can see the statistics of a user with the command 'userinfo <@user>' and more specific the level and xp of the user with the command 'rank <@user>'</p>
    </div>
</div>
</body>
</html>
