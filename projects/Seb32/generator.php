<?php
require_once "functions.php";

try {
    generateNew();
} catch (Exception $e) {
}

header("location: ./");
exit();