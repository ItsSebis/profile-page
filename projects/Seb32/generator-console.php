<?php
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

    return $syms;
}

$amount = 20;
$lines = file("pattern.php");
$str = "";
$lineReading = 0;
while (strpos($lines[$lineReading], ");") === false) {
    $add = "";
    if (strpos($lines[$lineReading+1], ");") !== false) {
        $add = ",";
    }
    $str .= $lines[$lineReading].$add;
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
    echo(fread($writer, 100));
    fwrite($writer, $str);
    fclose($writer);

} catch (Exception $e) {}