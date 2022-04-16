<?php
require_once "functions.inc.php";

$offset = 0;
if (isset($_GET["offset"])) {
    $offset = $_GET["offset"];
}

$limit = 10;
if (isset($_GET["limit"])) {
    $limit = $_GET["limit"];
}

echo json_encode(postArray($offset, $limit));