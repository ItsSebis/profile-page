<?php
include_once "../config.php";
include_once "../projects/publicFunc.php";
session_start();

if (isset($_POST["register"])) {

    header("location: ../");
    exit();

    $urlExtensions = "";
    if (count($_GET) > 0) {
        foreach ($_GET as $key => $value) {
            $urlExtensions.=$key."=".$value."&";
        }
    }

    if (empty($_POST["usr"]) || empty($_POST["pw"]) || empty($_POST["pwr"]) || empty($_POST["email"])) {
        header("location: ../login.php?error=emptyf&register&".$urlExtensions);
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

elseif (isset($_POST["chPw"])) {
    if (empty($_POST["oldPw"]) || empty($_POST["pw"]) || empty($_POST["pwr"])) {
        header("location: ../settings.php?error=emptyf");
        exit();
    }

    $data = accountData($_SESSION["id"]);
    $old = $_POST["oldPw"];
    if (password_verify($old, $data["pw"]) === false) {
        header("location: ../settings.php?error=invalidPassword");
        exit();
    }
    if ($_POST["pw"] !== $_POST["pwr"]) {
        header("location: ../settings.php?error=pwr");
        exit();
    }
    setUserPw($data["id"], $_POST["pw"]);
    header("location: ../settings.php?error=done");
    exit();
}

elseif (isset($_POST["chName"])) {
    $name = $_POST["name"];
    $data = accountData($_SESSION["id"]);
    $diff = untilNow($data["chName"]);
    $days = $diff->y*365;
    $days += $diff->days;

    if ($days < 30) {
        header("location: ../settings.php?error=time");
        exit();
    }

    if (preg_match("/[^a-zA-Z]+/", $name)) {
        header("location: ../settings.php?error=invalidid");
        exit();
    }

    if (accountDataByName($name) !== false) {
        header("location: ../settings.php?error=wrongid");
        exit();
    }

    if (strpos(strtolower($name), "sebi") !== false) {
        header("location: ../settings.php?error=sebi");
        exit();
    }

    if (strlen($name) < 2) {
        header("location: ../settings.php?error=nameTooShort");
        exit();
    }

    if (strlen($name) > 64) {
        header("location: ../settings.php?error=nameTooLong");
        exit();
    }
    setUserName($_SESSION["id"], $name);
    header("location: ../settings.php?error=dona");
    exit();
}

elseif (isset($_POST["edit"])) {
    $user = accountData($_POST["user"]);
    if (!userHasPerm($_SESSION["id"], "grantroles") || $user["id"] == $_SESSION["id"]) {
        header("location: ../admin.php?error=noPerm&page=users&usr=".$_POST['user']);
        exit();
    }
    $role = $_POST["role"];
    if ($role != "null") {
        setUserRole($user["id"], $role);
    }
    header("location: ../admin.php?error=setRole&page=users&usr=".$_POST['user']);
    exit();
}

elseif (isset($_POST["respw"])) {
    $user = accountData($_POST["user"]);
    if (!userHasPerm($_SESSION["id"], "respw") || $user["id"] == 1) {
        header("location: ../admin.php?error=noPerm&page=users&usr=".$_POST['user']);
        exit();
    }
    $pw = rngNumPw();
    setUserPw($user["id"], $pw);
    header("location: ../admin.php?error=respw&pw=".$pw."&page=users&usr=".$_POST['user']);
    exit();
}

elseif (isset($_POST["del"])) {
    $user = accountData($_POST["user"]);
    if (!userHasPerm($_SESSION["id"], "delusrs") || $user["id"] == 1) {
        header("location: ../admin.php?error=noPerm&page=users&usr=".$_POST['user']);
        exit();
    }
    if ($user["id"] == $_SESSION["id"]) {
        header("location: ../admin.php?error=respw&page=users&error=delself&usr=".$user['id']);
        exit();
    }
    delUser($user["id"]);
    header("location: ../admin.php?error=respw&page=users&error=del");
    exit();
}

else {
    header("location: ../?error=notFromSubmit");
    exit();
}