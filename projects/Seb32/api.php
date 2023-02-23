<?php

if (isset($_GET["w"]) && isset($_GET["str"])) {
    $way = $_GET["w"] == "e";
    $str = $_GET["str"];
} else {
    echo "!!! Err: Not enough information given !!!";
    exit();
}

require_once "functions.php";

if ($way) {
    echo encode($str, 2);
} else {
    echo decode($str, 2);
}
