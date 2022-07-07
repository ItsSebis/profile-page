<?php
require_once "../../config.php";
require_once "../publicFunc.php";
require_once "functions.php";

if (isset($_POST["delete"]) && getLinkByLid($_POST["delete"]) !== false) {
    $data = getLinkByLid($_POST["delete"]);
    if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false && $data["owner"] == $_SESSION["id"]) {
        delLink($_POST["delete"]);
    }
    header("location: ./?dError=0");
} else {
    print_r(getLinkByLid($_POST["delete"]));
    print_r($_POST);
    #header("location: ./?error=notFromSubmit");
}