<?php
require_once "../config.php";
require_once "../projects/publicFunc.php";

function post($text) {
    $con = conLobby();
    $sql = "INSERT INTO `posts` (usr, content) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=post");
        exit();
    }

    $me = $_SESSION["id"];

    mysqli_stmt_bind_param($stmt, "ss",$me, $text);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function postArray($offset, $limit) {
    $con = conLobby();
    $sql = "SELECT * FROM posts ORDER BY `released` DESC LIMIT $offset, $limit;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=postsArray");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}
