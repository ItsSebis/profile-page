<?php

#echo "Loading pattern.php";
require_once "./pattern.php";
require_once "../publicFunc.php";
#echo "Loaded pattern.php";

/**
 * @throws Exception
 */
function allowedSymbols() {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789/()[]{}.,;+*~?=&\$<>";
    $syms = array();
    for ($i=0; $i<strlen($chars); $i++) {
        $test = $chars[random_int(0, strlen($chars)-1)];
        while (array_search($test, $syms) !== false) {
            $test = $chars[random_int(0, strlen($chars)-1)];
        }
        $syms[$chars[$i]] = $test;
    }

    $syms[" "] = "%";
    $syms[":"] = "#";
    $syms["-"] = "§";

    return $syms;
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

function generateNew($amount=1) {
    $lines = file("pattern.php");
    $str = "";
    $lineReading = 0;
    while (strpos($lines[$lineReading], ");") === false) {
        $add = "";
        if (strpos($lines[$lineReading+1], ");") !== false) {
            $add = ",";
        }
        $line = str_replace(array("\r", "\n"), "", $lines[$lineReading]);
        $str .= $line.$add;
        echo $line.$add;
        $lineReading++;
    }

    try {
        for ($i=0;$i<$amount;$i++) {
            $str.="
        array(";
            foreach (allowedSymbols() as $key => $val) {
                $str .= "\"$key\" => \"$val\"";
                $array = allowedSymbols();
                if ($val != end($array)) {
                    $str .= ", ";
                }
            }
            $str .= ")";
            if ($i != $amount-1) {
                $str.=",";
            }
        }

        $str.="
    );
}";

        $writer = fopen("pattern.php", "w+");
        fwrite($writer, $str);
        fclose($writer);

    } catch (Exception $e) {}
}
