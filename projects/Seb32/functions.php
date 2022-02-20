<?php

#echo "Loading pattern.php";
require_once "./pattern.php";
require_once "../publicFunc.php";
#echo "Loaded pattern.php";

function getEncodedCount() {
    $count = getStats("seb32encoded")["value"];
    return reformatBIgInts($count);
}

function getDecodedCount() {
    $count = getStats("seb32decoded")["value"];
    return reformatBIgInts($count);
}

function getStats($key) {
    $con = con();
    $sql = "SELECT * FROM stats WHERE `key` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $key);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function setStat($key, $val) {
    $con = con();
    $sql = "UPDATE stats SET value=? WHERE `key` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $val, $key);
    mysqli_stmt_execute($stmt);
}

/**
 * @throws Exception
 */
function encryptLetter($let, $pattern) {
    if (!isset(patterns()[$pattern][$let])) {
        return $let;
    }
    return patterns()[$pattern][$let];
}

/**
 * @throws Exception
 */
function decryptLetter($let, $pattern) {
    if (array_search($let, patterns()[$pattern]) === false) {
        return $let;
    }
    return array_search($let, patterns()[$pattern]);
}

/**
 * @throws Exception
 */
function seb32_encode($str) {
    $pattern = random_int(0, count(patterns())-1);
    for ($i=0;$i<strlen($str);$i++) {
        $str[$i] = encryptLetter($str[$i], $pattern);
    }
    $str .= "-".bin2hex($pattern);
    return $str;
}

/**
 * @throws Exception
 */
function seb32Decode($str) {
    $exploded = explode("-", $str);
    $str = $exploded[0];
    $pattern = hex2bin($exploded[1]);
    for ($i=0;$i<strlen($str);$i++) {
        $str[$i] = decryptLetter($str[$i], $pattern);
    }
    return $str;
}

/**
 * @throws Exception
 */
function encode($str) {
    $str = seb32_encode($str);
    $str = base64_encode($str);
    $str = seb32_encode($str);
    return base64_encode($str);
}

/**
 * @throws Exception
 */
function decode($str) {
    $str = base64_decode($str);
    $str = seb32Decode($str);
    $str = base64_decode($str);
    return seb32Decode($str);
}
