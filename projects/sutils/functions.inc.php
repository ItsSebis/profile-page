<?php

require_once "config.inc.php";

function liceData($lice) {
    $con = con();
    $sql = "SELECT * FROM licences WHERE licence = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=liceData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $lice);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function useLice($lice) {
    $con = con();
    $qry = "UPDATE users SET used=? WHERE licence=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=useLice");
        exit();
    }

    $used = 1;

    mysqli_stmt_bind_param($stmt, "ss", $used, $lice);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}