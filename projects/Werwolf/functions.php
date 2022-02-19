<?php

require_once "dbh.conf.php";

function getRoles() {
    return array("Dorfbewohner", "Werwolf", "Hexe/-r");
}

function createGame() {
    $game = random_int(1, 99999999);
    while (gameData($game) !== false) {
        $game = random_int(1, 99999999);
    }
    $con = con();
    $sql = "INSERT INTO wergames (id, host) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=createGame");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $game, $_SESSION["plName"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $game;
}

function createPlayer($game, $name) {
    $con = con();
    $sql = "INSERT INTO werplayer (game, name) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=createPlayer");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $game, $name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function echoPlayers($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=echoPlayers");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $host = "";
            $preHost = "";
            if (gameData($_SESSION["gameid"])["host"] == $row["name"]) {
                $host = "<span style='font-size: 1.3rem; color: gold'>*</span>";
                $preHost = "<span style='font-size: 1.3rem; color: gold'>*</span>";
            }
            echo "<p style='margin: 10px auto; font-size: 1.8rem;'>".$preHost.$row['name'].$host."</p>";
        }
    }
    mysqli_stmt_close($stmt);
}

function werVotePlayers($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=werVotePlayers");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            if ($row["role"] != 1) {
                echo "<p style='margin: 10px auto; font-size: 1.8rem;'><form action='./' method='post'><button type='submit' name='vote' value='".$row['name']."'>".$row['name']."</button></form></p>";
            }
        }
    }
    mysqli_stmt_close($stmt);
}

function gamePlayers($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=gamePlayers");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
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

function playerCount($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=countPlayers");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $count = 0;
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $count++;
        }
    }
    mysqli_stmt_close($stmt);

    return $count;
}

function gameData($id) {
    $con = con();
    $sql = "SELECT * FROM wergames WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function playerDataByName($str, $game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE name = ? AND game = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $str, $game);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function setGameStatus($game, $status) {
    $con = con();
    $qry = "UPDATE wergames SET status=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setGameStatus");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $status, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setPlayerRole($player, $game, $role) {
    $con = con();
    $qry = "UPDATE werplayer SET role=? WHERE name=? AND game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setPlayerRole");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $role, $player, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function generateRoles($game) {
    $gameData = gameData($_SESSION["gameid"]);
    $werwolfe = array();
    for ($i=0;$i<$gameData["wercount"];$i++) {
        $get = random_int(0, count(gamePlayers($game))-1);
        $wolf = gamePlayers($game)[$get];
        setPlayerRole($wolf["name"], $game, 1);
        $werwolfe[] = $wolf;
    }
    $get = random_int(0, count(gamePlayers($game))-1);
    while (array_search(gamePlayers($game)[$get], $werwolfe) !== false) {
        $get = random_int(0, count(gamePlayers($game))-1);
    }
    $hexe = gamePlayers($game)[$get];
    setPlayerRole($hexe["name"], $game, 2);
}
