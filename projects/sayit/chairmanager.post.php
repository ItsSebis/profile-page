<?php

require_once "functions.php";

if (isset($_POST["say"])) {
    $chair = chairData($_POST["say"]);
    if (empty($_POST["content"])) {
        header("location: ./?error=emptyf&c=".$chair['id']);
        exit();
    }
    say($_POST["content"], $_SESSION["id"], $chair["id"]);
    header("location: ./?error=sent&c=".$chair['id']);
}

elseif (isset($_POST["delsay"])) {
    $say = sayData($_POST["delsay"]);
    $chair = chairData($say["in"]);
    if ($say["by"] == $_SESSION["id"] || $chair["owner"] == $_SESSION["id"] || userHasPerm($_SESSION["id"], "managechairs")) {
        delSay($say["id"]);
    }
    header("location: ./?c=".$chair['id']);
}

elseif (isset($_POST["create"])) {
    if (myChair($_SESSION["id"]) === false) {
        createRoom(accountData($_SESSION["id"])["username"]."s Raum", $_SESSION["id"]);
    }
    header("location: ./?c=".myChair($_SESSION["id"])["id"]);
}

elseif (isset($_POST["delroom"])) {
    $chair = $_POST["delroom"];
    if (chairData($chair)["owner"] == $_SESSION["id"] || userHasPerm($_SESSION["id"], "managechairs")) {
        delRoom($chair);
    }
    header("location: ./");
}

else {
    header("location: ./?error=notFromSubmit");
}
exit();