<?php
include_once "../config.php";
include_once "../projects/publicFunc.php";
session_start();
if (isset($_POST["register"])) {
}

else {
    header("location: ../?error=notFromSubmit");
}