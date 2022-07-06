<?php
require_once "../../config.php";
require_once "../publicFunc.php";
require_once "functions.php";

if (isset($_POST["delete"]) && getLink($_POST["delete"]) !== false) {
    $data = getLink($_POST["delete"]);
    if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false && $data["owner"] == $_SESSION["id"]) {
        delLink($_POST["delete"]);
    }
    header("location: ./?dError=0");
} else {
    print_r(getLink($_POST["delete"]));
    print_r($_POST);
    #header("location: ./?error=notFromSubmit");
}