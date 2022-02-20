<?php

/**
 * @param $count
 * @return mixed|string
 */
function reformatBIgInts($count)
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