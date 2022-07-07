<?php
session_start();

function linksArray($user) {
    $con = con();
    $sql = "SELECT * FROM links WHERE `owner` = ? ORDER BY `id` DESC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=linksArray");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}

# Depracted
/*
function getLink($id) {
    $con = con();
    $sql = "SELECT * FROM links WHERE `id` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}
*/

function getLinkByLid($lid) {
    $con = con();
    $sql = "SELECT * FROM links WHERE `lid` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $lid);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function createLink($target, $owner) {
    $lid = rngString();
    $con = con();
    $sql = "INSERT INTO links (lid, target, owner) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=createLink");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $lid, $target, $owner);
    mysqli_stmt_execute($stmt);

    return $lid;
}

function delLink($lid) {
    $con = con();
    $qry = "DELETE FROM links WHERE lid=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $lid);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function addView($lid) {
    $con = con();
    $qry = "UPDATE links SET `views`=? WHERE lid=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setRoleColor");
        exit();
    }

    $views = getLinkByLid($lid)["views"];
    $views++;

    mysqli_stmt_bind_param($stmt, "ss", $views, $lid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}