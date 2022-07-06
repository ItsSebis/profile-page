<?php
require_once "../../config.php";
require_once "../publicFunc.php";
require_once "functions.php";

if (isset($_POST["create"]) && isset($_POST["target"])) {
    $uri = $_POST["target"];
    if (filter_var($uri, FILTER_VALIDATE_URL) === false || empty($uri)) {
        header("location: ./?error=invalid&uri=".$uri);
        exit();
    }
    $owner = null;
    if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
        $owner = $_SESSION["id"];
        #echo "Set \$owner";
    }
    #echo $_SESSION["id"]."<br>";
    #echo $owner."<br>";
    #echo $uri."<br>";
    $short = createLink($uri, $owner);
    header("location: ./?error=0&uri=".$short);
} else {
    header("location: ./?error=notFromSubmit");
}