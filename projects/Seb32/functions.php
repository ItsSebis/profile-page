<?php

#echo "Loading pattern.php";
require_once "dbh.conf.php";
#require_once "./pattern.php";
require_once "../publicFunc.php";
#echo "Loaded pattern.php";

/**
 * @throws Exception
 */

function allLetters() {
    return "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890=?!+*~.,()[]{}\/:|&<>";
}

function getPattern($id) {
    $con = con();
    $sql = "SELECT * FROM sebpatts WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=getPattern");
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

/**
 * @throws Exception
 */
function generatePattern() {
    $chars = allLetters();
    #echo $chars."<br>";
    $syms = array();
    for ($i=0; $i<strlen($chars); $i++) {
        $test = $chars[random_int(0, strlen($chars)-1)];
        while (array_search($test, $syms) !== false) {
            $test = $chars[random_int(0, strlen($chars)-1)];
        }
        #echo $test." | $i<br>";
        $syms[] = $test;
    }
    /*echo "Finished gen! <br>";

    foreach ($syms as $sym) {
        echo $sym;
    }
    echo "<br>";*/
    #echo $str."<br>";
    return implode("", $syms);
}

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
    #echo $pattern."/".pattCount()." | ";
    $pattern = getPattern($pattern)["changed"];

    if ($let == " ") {
        return "%";
    } elseif ($let == "-") {
        return "#";
    }

    if (strpos(allLetters(), $let) === false) {
        return $let;
    }

    #echo $pattern[strpos(allLetters(), $let)]."<br>";
    return $pattern[strpos(allLetters(), $let)];
}

/**
 * @throws Exception
 */
function decryptLetter($let, $pattern) {
    $pattern = getPattern($pattern)["changed"];

    if ($let == "%") {
        return " ";
    } elseif ($let == "#") {
        return "-";
    }

    if (strpos(allLetters(), $let) === false) {
        return $let;
    }

    return allLetters()[strpos($pattern, $let)];
}

/**
 * @throws Exception
 */
function seb32_encode($str) {
    $pattern = random_int(1, pattCount());
    while (getPattern($pattern) === false) {
        $pattern = random_int(1, pattCount());
    }
    for ($i=0;$i<strlen($str);$i++) {
        $str[$i] = encryptLetter($str[$i], $pattern);
    }
    $str .= "-".bin2hex(base64_encode($pattern));
    return $str;
}

/**
 * @throws Exception
 */
function seb32Decode($str) {
    $exploded = explode("-", $str);
    $str = $exploded[0];
    $pattern = base64_decode(hex2bin($exploded[1]));
    for ($i=0;$i<strlen($str);$i++) {
        $str[$i] = decryptLetter($str[$i], $pattern);
    }
    return $str;
}

/**
 * @throws Exception
 */
function encode($str) {
    #$str = seb32_encode($str);
    $str = base64_encode($str);
    return seb32_encode($str);
}

/**
 * @throws Exception
 */
function decode($str) {
    $str = seb32Decode($str);
    return base64_decode($str);
    #return seb32Decode($str);
}

/**
 * @throws Exception
 */
function generateNew($amount=1) {
    for ($i=0;$i<$amount;$i++) {
        insertPattern(generatePattern());
    }
}

function insertPattern($pattern) {
    $con = con();
    $sql = "INSERT INTO sebpatts (changed) VALUES (?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        #header("location: ../?error=1&part=insertPattern");
        #exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $pattern);
    mysqli_stmt_execute($stmt);
}

function pattCount() {
    $con = con();
    $sql = "SELECT id FROM sebpatts;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=countPatts");
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
    mysqli_stmt_close($stmt);

    return $count;
}
