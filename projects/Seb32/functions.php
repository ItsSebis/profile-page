<?php

#echo "Loading pattern.php";
require_once "./pattern.php";
#echo "Loaded pattern.php";

function getStats($key) {
    $lines = file("seb32.stats");
    foreach ($lines as $line) {
        $arrayL = explode(":", $line);
        if ($arrayL[0] === $key) {
            return $arrayL[1];
        }
    }
    return 0;
}

function getEncodedCount() {
    $count = getStats("encoded");
    return reformatBIgInts($count);
}

function getDecodedCount() {
    $count = getStats("decoded");
    return reformatBIgInts($count);
}

/**
 * @param string $count
 * @return string
 */
function reformatBIgInts(string $count): string
{
    if ($count > 1050000000) {
        $count = round($count / 1000000000, 2) . "G";
    } elseif ($count > 1250000) {
        $count = round($count / 1000000, 2) . "M";
    } elseif ($count > 1500) {
        $count = round($count / 1000, 2) . "K";
    }
    return $count;
}

function setStat($key, $val) {
    $lines = file("seb32.stats");
    $tempFile = "";
    for ($i=0;$i<count($lines);$i++) {
        $arrayL = explode(":", $lines[$i]);
        if ($arrayL[0] === $key) {
            $lines[$i] = $key.":".$val;
        }
        $br = "";
        if (isset($lines[$i-1]) && !empty($lines[$i-1])) {
            $br = "\n";
        }
        $tempFile.=$br.$lines[$i];
    }
    $lines=$tempFile;
    $writer = fopen("seb32.stats", "w");
    fwrite($writer, $lines);
    fclose($writer);
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
function encode($str) {
    $pattern = random_int(0, count(patterns())-1);
    for ($i=0;$i<strlen($str);$i++) {
        $str[$i] = encryptLetter($str[$i], $pattern);
    }
    $str .= "-".bin2hex($pattern);
    $str = base64_encode($str);
    $str = bin2hex($str);
    return base64_encode($str);
}

/**
 * @throws Exception
 */
function decode($str) {
    $str = base64_decode($str);
    $str = hex2bin($str);
    $str = base64_decode($str);
    $exploded = explode("-", $str);
    $str = $exploded[0];
    $pattern = hex2bin($exploded[1]);
    for ($i=0;$i<strlen($str);$i++) {
        $str[$i] = decryptLetter($str[$i], $pattern);
    }
    return $str;
}
