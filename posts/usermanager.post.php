<?php
include_once "../config.php";
include_once "../projects/publicFunc.php";
session_start();
if (isset($_POST["register"])) {

    if (empty($_POST["usr"]) || empty($_POST["pw"]) || empty($_POST["pwr"]) || empty($_POST["email"])) {
        header("location: ../login.php?error=emptyf&register");
        exit();
    }

    $name = $_POST["usr"];
    $mail = $_POST["email"];
    $pw = $_POST["pw"];
    $pwr = $_POST["pwr"];

    if (preg_match("/[^a-zA-Z]+/", $name)) {
        header("location: ../login.php?error=invalidid&register");
        exit();
    }

    if (accountDataByName($name) !== false) {
        header("location: ../login.php?error=wrongid&register");
        exit();
    }

    if (strpos(strtolower($name), "sebi") !== false) {
        header("location: ../login.php?error=sebi&register");
        exit();
    }

    if (strlen($name) < 2) {
        header("location: ../login.php?error=nameTooShort&register");
        exit();
    }

    if (strlen($name) > 64) {
        header("location: ../login.php?error=nameTooLong&register");
        exit();
    }

    if (accountDataByName($mail) !== false) {
        header("location: ../login.php?error=wrongmail&register");
        exit();
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header("location: ../login.php?error=invalidmail&register");
        exit();
    }

    if ($pw !== $pwr) {
        header("location: ../login.php?error=wrongpw&register");
        exit();
    }

    if (strlen($pw) < 8) {
        header("location: ../login.php?error=pwTooShort&register");
        exit();
    }

    createUser($name, $pw, $mail);
    login(accountDataByName($name)["id"]);
}

else {
    header("location: ../?error=notFromSubmit");
    exit();
}