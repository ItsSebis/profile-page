<?php
include_once "../config.php";
include_once "../projects/publicFunc.php";
session_start();
if (!isset($_POST["login"])) {
    header("location: ../?error=notFromSubmit");
    exit();
}

sleep(2);

if (empty($_POST["usr"]) || empty($_POST["pw"])) {
    header("location: ../login.php?error=emptyf");
    exit();
}

$usr = $_POST["usr"];
$pw = $_POST["pw"];

if (accountDataByName($usr) === false) {
    header("location: ../login.php?error=wrongid");
    exit();
}

$data = accountDataByName($usr);

$check = password_verify($pw, $data["pw"]);

if ($check === false) {
    header("location: ../login.php?error=wrongpw");
    exit();
}

#setcookie("token", $data["token"], time()+60*60*24*30);
login($data["id"]);
