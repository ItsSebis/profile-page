<?php
include_once "../config.php";
include_once "../projects/publicFunc.php";
session_start();

if (!isset($_SESSION["id"]) || !userHasPerm($_SESSION["id"], "mail")) {
    header("location: ../?error=noPerm");
    exit();
}

if (isset($_POST["send"])) {
    if (empty($_POST["to"]) || empty($_POST["subject"]) || empty($_POST["text"])) {
        header("location: ../admin.php?page=mail&error=emptyf");
        exit();
    }
    if (!filter_var($_POST["to"], FILTER_VALIDATE_EMAIL)) {
        header("location: ../admin.php?page=mail&error=invalidTo");
        exit();
    }
    sendMail($_POST["mail"], $_POST["to"], $_POST["subject"], $_POST["text"]);
    header("location: ../admin.php?page=mail&error=sent");
    exit();
} else {
    header("location: ../?error=notFromSubmit");
    exit();
}