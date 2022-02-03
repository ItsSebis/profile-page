<?php

require_once "../config/dbh.php";

function guildData($id) {
    $sql = "SELECT * FROM guilds WHERE id = ?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./index.php?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function usersGuildData($usrId, $guildId) {
    $sql = "SELECT * FROM members WHERE userId = ? AND guild=?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./index.php?error=1&part=usrData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $usrId, $guildId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function usersArray($guild) {
    $sql = "SELECT * FROM members WHERE guild=? ORDER BY `level` DESC, `xp` DESC, `msgs` DESC, `cmds` DESC, `money` DESC, `userName` ASC;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $guild);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $users = array();

    while ($row = $rs->fetch_assoc()) {
        $users[] = $row["userId"];
    }
    // in_array($needle, $array) for isTeamerOfTeam
    return $users;
}

function guildDivs() {
    $sql = "SELECT * FROM guilds ORDER BY `premium` DESC, `name` ASC;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./index.php?error=1&part=makeQuery");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            echoGuild($row["id"]);
        }
    }
}

function echoGuild($id) {
    $sql = "SELECT * FROM guilds WHERE id = ?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./index.php?error=1&part=echoGuild");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        if ($row["premium"] == 1) {
            $premium = "<span style='color: lime'>Yes</span>";
        } else {
            $premium = "<span style='color: red'>No</span>";
        }
        echo "
            <div class='guild'>
                <h3><a href='./?guild=".$row["id"]."' class='glink'>".$row['name']."</a></h3>
                <h5>Premium: ".$premium."</h5>
                <h5>Members: ".getMemberCount($id)."</h5>
                <h5>XP Multiplier: ".$row['xpMulti']."x</h5>
                <h5>Level-roles: ".getLevelRoleCount($id)."</h5>
                <h5>Owner: ".$row['owner']."</h5>
            </div>
        ";
        return true;
    }
    else {
        return false;
    }
}

function xpNeeded($lvl): float|int {
    return 50+$lvl*100;
}

function guildLb($id) {
    echo "<a href='./' style='position: absolute; top: 5px; left: 2px;'><- Back</a>";
    $data = guildData($id);
    echo "<h1 style='margin-bottom: 2px;'>".$data['name']."</h1>";
    echo "<h3><a href='".$data['invite']."'>JOIN</a></h3><br>";
    $rank = 1;
    foreach (usersArray($id) as $usrId) {
        $data = usersGuildData($usrId, $id);
        $color = "#44bb78";
        if ($rank == 1) {
            $color = "gold";
        } elseif ($rank == 2) {
            $color = "silver";
        } elseif ($rank == 3) {
            $color = "#8d4714";
        }
        echo "
        <div class='user'>
            <h2 style='float: left; line-height: 1; position: relative; top: -15px; color: ".$color."'>".$rank."</h2>
            <h3>".$data['userName']."</h3>
            <p>LEVEL: <span class='value'>".$data['level']."</span> | XP: <span class='value'>".$data['xp']."/".xpNeeded($data['level']+1)." ".round(100/xpNeeded($data['level']+1)*$data['xp'], 2)."%</span></p>
        </div>";
        $rank++;
    }
}

function getMemberCount($id) {
    $sql = "SELECT * FROM members WHERE guild=?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=1&part=memberCount");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    $count=0;

    if ($rs->num_rows > 0) {
        while ($rs->fetch_assoc()) {
            $count++;
        }
    }

    return $count;
}

function getLevelRoleCount($id) {
    $sql = "SELECT * FROM lvlroles WHERE guild=?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=1&part=lvlRoles");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    $count=0;

    if ($rs->num_rows > 0) {
        while ($rs->fetch_assoc()) {
            $count++;
        }
    }

    return $count;
}
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
