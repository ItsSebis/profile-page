<?php

/**
 * @param $count
 * @return mixed|string
 */
function reformatBIgInts($count) {
    if ($count > 1100000000) {
        $count = round($count / 1000000000, 2) . "G";
    } elseif ($count > 1250000) {
        $count = round($count / 1000000, 2) . "M";
    } elseif ($count > 1500) {
        $count = round($count / 1000, 2) . "K";
    }
    return $count;
}

function getYtIdPossible() {
    return "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_1234567890";
}

function randomLetter() {
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    try {
        return $letters[random_int(0, strlen($letters) - 1)];
    } catch (Exception $e) {
    }
    return " - NULL - ";
}

function yt_exists($videoID) {
    try {
        $theURL = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$videoID&format=json";
        $headers = get_headers($theURL);
    } catch (Exception $e) {
        $headers = get_headers("https://example.com");
    }

    #$info = file_get_contents($theURL);

    return !((substr($headers[0], 9, 3) === "400") || (substr($headers[0], 9, 3) === "404"));
    #return strpos($info, "Bad Request") === false;
}

function yt_info($videoID) {
    $theURL = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$videoID&format=json";
    return json_decode(file_get_contents($theURL), true);
}

function untilNow($date) {
    $dateTimeObject1 = date_create($date);
    $dateTimeObject2 = date_create(gmdate("Y-m-d H:i:s"));
    return date_diff($dateTimeObject1, $dateTimeObject2);
}

function rngByPerCent($percent) {
    try {
        return random_int(1, 10000000000) <= $percent*100000000;
    } catch (Exception $e) {
        return false;
    }
}

function getStats($key) {
    $con = con();
    $sql = "SELECT * FROM stats WHERE `key` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $key);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function setStat($key, $val) {
    $con = con();
    $sql = "UPDATE stats SET value=? WHERE `key` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $val, $key);
    mysqli_stmt_execute($stmt);
}

function isEven($number) {
    return $number % 2 == 0;
}

function allProjects() {
    $con = con();
    $sql = "SELECT * FROM projects ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=allProjects");
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

function projectData($dir) {
    $con = con();
    $sql = "SELECT * FROM projects WHERE dir = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=projectData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $dir);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function accountData($id) {
    $con = con();
    $sql = "SELECT * FROM users WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=accountData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function accountDataByName($acc) {
    $con = con();
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=accountDataById");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $acc, $acc);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function login($id) {
    $data = accountData($id);
    $_SESSION["id"] = $data["id"];
    header("location: ../?error=0");
    exit();
}

function roleData($id) {
    $con = con();
    $sql = "SELECT * FROM roles WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=roleData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function rolesArray() {
    $con = con();
    $sql = "SELECT * FROM roles ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=rolesArray");
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

function usersArray() {
    $con = con();
    $sql = "SELECT * FROM users ORDER BY `username` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=usersArray");
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

function rolesUsersArray($role) {
    $con = con();
    $sql = "SELECT * FROM users WHERE `role`=? ORDER BY `username` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=rolesUsersArray");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $role);
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

function createUser($name, $pw, $mail) {
    $con = con();
    $sql = "INSERT INTO users (username, pw, email) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=createUser");
        exit();
    }

    $pw = password_hash($pw, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $name, $pw, $mail);
    mysqli_stmt_execute($stmt);
}

function setUserStat($usr, $stat, $value) {
    $con = con();
    $qry = "UPDATE users SET ".$stat."=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserStat");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $value, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserPw($usr, $value) {
    $con = con();
    $qry = "UPDATE users SET pw=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserPw");
        exit();
    }

    $value = password_hash($value, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $value, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserName($usr, $value) {
    $con = con();
    $qry = "UPDATE users SET username=?,chName=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserName");
        exit();
    }

    $date = date("Y-m-d h:i:s");

    mysqli_stmt_bind_param($stmt, "sss", $value, $date, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserSeen($usr) {
    $con = con();
    $qry = "UPDATE users SET lastseen=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserSeen");
        exit();
    }

    $date = date("Y-m-d H:i:s");

    mysqli_stmt_bind_param($stmt, "ss", $date, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}