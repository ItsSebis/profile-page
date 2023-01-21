<?php

/** @noinspection DuplicatedCode */
function getGuildCount() {
    $sql = "SELECT * FROM guilds;";
    return extractedCounter($sql);
}

function getMemberCountGuild($id) {
    $sql = "SELECT * FROM members WHERE guild=".$id.";";
    return extractedCounter($sql);
}

function getMemberCount() {
    $sql = "SELECT * FROM members;";
    return extractedCounter($sql);
}

function getOperatorCount() {
    $sql = "SELECT * FROM ops;";
    return extractedCounter($sql);
}

/**
 * @param $sql
 * @return int|void
 */
function extractedCounter($sql)
{
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../smp.php?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    $count = 0;

    if ($rs->num_rows > 0) {
        while ($rs->fetch_assoc()) {
            $count++;
        }
    }

    return $count;
}

function opData($tag) {
    $con = con();
    $sql = "SELECT * FROM ops WHERE userTag = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $tag);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function guildData($id) {
    $con = con();
    $sql = "SELECT * FROM guilds WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1");
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

function toggleGuildPremium($id) {
    $con = con();
    $qry = "UPDATE guilds SET premium=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../smp.php?error=1");
        exit();
    }

    $premiumCurrent = guildData($id)["premium"];
    if ($premiumCurrent) {
        $premium = 0;
    } else {
        $premium = 1;
    }

    mysqli_stmt_bind_param($stmt, "ii", $premium, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function guilds() {
    $con = con();
    $qry = "SELECT * FROM guilds ORDER BY `premium` DESC, `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../smp.php?error=1");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            if ($row["premium"]) {
                $premium = "<td><a href='./?premium&guild=".$row["id"]."' style='color: lime'>Yes</a></td>";
            } else $premium = "<td><a href='./?premium&guild=".$row["id"]."' style='color: red'>No</a></td>";
            echo '
                <tr>
                    <td>'.$row["name"].'</td>
                    <td>'.$row["owner"].'</td>
                    '.$premium.'
                </tr>
            ';
        }
    }

    mysqli_stmt_close($stmt);
}

function usersGuildData($usrId, $guildId) {
    $sql = "SELECT * FROM members WHERE userId = ? AND guild=?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../smp.php?error=1&part=usrData");
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
        header("location: ../smp.php?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $guild);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $users = array();

    while ($row = $rs->fetch_assoc()) {
        $users[] = $row;
    }
    // in_array($needle, $array) for isTeamerOfTeam
    return $users;
}

function allUsersArray() {
    $sql = "SELECT * FROM members ORDER BY `level` DESC, `xp` DESC, `msgs` DESC, `cmds` DESC, `money` DESC, `userName` ASC;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../smp.php?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $users = array();

    while ($row = $rs->fetch_assoc()) {
        $users[] = $row;
    }
    // in_array($needle, $array) for isTeamerOfTeam
    return $users;
}

function allWordsArray() {
    $sql = "SELECT * FROM words ORDER BY `sent` DESC, `word` ASC";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../smp.php?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $users = array();

    while ($row = $rs->fetch_assoc()) {
        $users[] = $row;
    }
    // in_array($needle, $array) for isTeamerOfTeam
    return $users;
}

function counterArray() {
    $sql = "SELECT * FROM counters";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../smp.php?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $users = array();

    while ($row = $rs->fetch_assoc()) {
        $users[] = $row;
    }
    // in_array($needle, $array) for isTeamerOfTeam
    return $users;
}

function countedNumbers() {
    $msgs = 0;
    foreach (counterArray() as $data) {
        $msgs = $msgs+$data["number"];
    }
    if ($msgs > 1500000000) {
        $msgs = round($msgs/1000000000, 2). "G";
    } elseif ($msgs > 1500000) {
        $msgs = round($msgs/1000000, 2). "M";
    } elseif ($msgs > 1500) {
        $msgs = round($msgs/1000, 2). "K";
    }
    return $msgs;
}

function readMessageCount() {
    $msgs = 0;
    foreach (allUsersArray() as $data) {
        $msgs = $msgs+$data["msgs"];
    }
    if ($msgs > 1500000000) {
        $msgs = round($msgs/1000000000, 2). "G";
    } elseif ($msgs > 1500000) {
        $msgs = round($msgs/1000000, 2). "M";
    } elseif ($msgs > 1500) {
        $msgs = round($msgs/1000, 2). "K";
    }
    return $msgs;
}

function readWordCount() {
    $array = allWordsArray();
    $words = 0;
    foreach ($array as $item) {
        $words += $item["sent"];
    }
    if ($words > 1500000000) {
        $words = round($words/1000000000, 2). "G";
    } elseif ($words > 1500000) {
        $words = round($words/1000000, 2). "M";
    } elseif ($words > 1500) {
        $words = round($words/1000, 2). "K";
    }
    return $words;
}

function allMemberCount() {
    $words = getMemberCount();
    if ($words > 1500000000) {
        $words = round($words/1000000000, 2). "G";
    } elseif ($words > 1500000) {
        $words = round($words/1000000, 2). "M";
    } elseif ($words > 1500) {
        $words = round($words/1000, 2). "K";
    }
    return $words;
}

function guildDivs() {
    $sql = "SELECT * FROM guilds ORDER BY `premium` DESC, `name` ASC;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../lbs/smp.php?error=1&part=makeQuery");
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
        header("location: ../lbs/smp.php?error=1&part=echoGuild");
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
                <h5>Members: ".getMemberCountGuild($id)."</h5>
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

function xpNeeded($lvl) {
    return 50+$lvl*100;
}

function guildLb($id) {
    echo "<a href='./' style='position: absolute; top: 5px; left: 2px;'><- Back</a>";
    $data = guildData($id);
    echo "<h1 style='margin-bottom: 2px;'>".$data['name']."</h1>";
    echo "<h3><a href='".$data['invite']."'>JOIN</a></h3><br>";
    $rank = 1;
    foreach (usersArray($id) as $data) {
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

function getLevelRoleCount($id) {
    $sql = "SELECT * FROM lvlroles WHERE guild=?;";
    $con = con();
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../lbs/?error=1&part=lvlRoles");
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