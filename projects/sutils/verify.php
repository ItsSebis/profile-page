<?php

if (!isset($_GET["licence"])) {
    echo "You need to give a licence!";
    exit();
}

require_once "functions.inc.php";
$licence = $_GET["licence"];

if (liceData($licence) === false) {
    echo "false";
    exit();
}

else {
    echo json_encode(liceData($licence));
}

if (isset($_GET["use"])) {
    useLice($licence);
}

