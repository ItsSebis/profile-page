<?php
function vocabArray($collection) {
    $con = con();
    $sql = "SELECT * FROM vocabs WHERE `col` = ? ORDER BY `id` DESC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=vocabArray");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $collection);
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

function colArray() {
    $con = con();
    $sql = "SELECT * FROM vcollections ORDER BY `book` ASC, `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=vocabArray");
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

function vocabData($id) {
    $con = con();
    $sql = "SELECT * FROM vocabs WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=vocabData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

function colData($id) {
    $con = con();
    $sql = "SELECT * FROM vcollections WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=colData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

function vusrData($vocab, $user) {
    $con = con();
    $sql = "SELECT * FROM vusr WHERE vocab = ? AND `user` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=vusrData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $vocab, $user);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

function createVUsr($vocab) {
    $con = con();
    $sql = "INSERT INTO vusr (`vocab`, `user`) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1");
        exit();
    }

    $me = $_SESSION["id"];

    mysqli_stmt_bind_param($stmt, "ss",$vocab, $me);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function vocabFinish($vocab, $correct, $user) {

    if (vusrData($vocab, $user) === false) {
        createVUsr($vocab);
    }

    $con = con();
    $qry = "UPDATE vusr SET done=?, correct=? WHERE vocab=? AND `user`=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=vocabFinish");
        exit();
    }

    $correct = vusrData($vocab, $user)["correct"] + mirrorBoolInInts($correct);
    $done = vusrData($vocab, $user)["done"] + 1;

    mysqli_stmt_bind_param($stmt, "ssss", $done, $correct, $vocab, $user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}