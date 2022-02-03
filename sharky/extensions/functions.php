<?php

/** @noinspection DuplicatedCode */
function getGuildCount() {
    $sql = "SELECT * FROM guilds;";
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
        header("location: index.php?error=1");
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
        header("location: ../index.php?error=1");
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
        header("location: index.php?error=1");
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