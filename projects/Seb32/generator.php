<?php

/**
 * @throws Exception
 */
function allowedSymbols() {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $syms = array();
    for ($i=0; $i<strlen($chars); $i++) {
        $test = $chars[random_int(0, strlen($chars)-1)];
        while (array_search($test, $syms) !== false) {
            $test = $chars[random_int(0, strlen($chars)-1)];
        }
        $syms[$chars[$i]] = $test;
    }

    return $syms;
}

$str = "(";

try {
    foreach (allowedSymbols() as $key => $val) {
        $str .= "\"$key\" => \"$val\", ";
    }
    $str .= ")";

    echo $str;

} catch (Exception $e) {
}