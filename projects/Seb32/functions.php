<?php

#echo "Loading pattern.php";
require_once "./pattern.php";
#echo "Loaded pattern.php";

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
