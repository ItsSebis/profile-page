<?php

function getLink($id) {
    $con = con();
    $sql = "SELECT * FROM links WHERE `lid` = ?;";
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

function createLink($target, $owner=null) {
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