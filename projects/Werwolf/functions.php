<?php

require_once "dbh.conf.php";
require_once "../publicFunc.php";

function getRoles() {
    return array("<span style='color: lime'>Dorfbewohner</span>", "<span style='color: red'>Werwolf</span>", "<span style='color: green'>Hexe</span>");
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

function hexeHealing($game) {
    $dying = "";
    foreach (gamePlayers($game) as $player) {
        if ($player["dying"] !== 0) {
            $dying = $player;
        }
    }
    echo "<div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>";
    echo "<h4>'".$dying["name"]."' ist das Opfer der Werwölfe.</h4>";
    echo "<p>Was möchtest du tun?</p><br>";
    $healDis = "";
    if (gameData($game)["hexHeals"] > 0) {
        $healDis = " disabled";
    }
    echo "<form action='./' method='post'>
                    <input type='hidden' name='target' value='" . $dying["name"] . "' style='visibility: hidden'>
                    <button type='submit' name='hexeOpfer' value='heal'".$healDis." style='width: 320px; border-color: lime'>Heilen</button><br><br>
                    <button type='submit' name='hexeOpfer' value='nothing' style='width: 320px; border-color: orange'>Nichts tun</button>
                </form>
            ";
    echo "</div>";
}

function echoPlayers($game)
{
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
            echo "<p style='margin: 10px auto; font-size: 1.8rem;'>" . $preHost . $row['name'] . $host . "</p>";
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
            if ($row["role"] != 1 && $row["dead"] == 0) {
                echo "<p style='margin: 10px auto; font-size: 1.8rem;'><form action='./' method='post'>
                        <button type='submit' name='wervote' value='" . $row['name'] . "'>" . $row['name'] . " <span style='color: red;'>" . playerVotes($row['name'], $game) . "</span></button>
                        </form></p>
                    ";
            }
        }
    }
    mysqli_stmt_close($stmt);
}

function hexeKilling($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=hexeKilling");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            if ($row["role"] != 2 && $row["dead"] == 0 && $row["dying"] == 0) {
                echo "<p style='margin: 10px auto; font-size: 1.8rem;'><form action='./' method='post'>
                        <button type='submit' name='hexKill' value='".$row['name']."'>".$row['name']."</span></button>
                        </form></p>
                    ";
            }
        }
    }
    mysqli_stmt_close($stmt);
}

function werVoting($game) {
    $voted = "0";
    foreach (gamePlayers($game) as $player) {
        if ($player["role"] == 1) {
            if ($player["voted"] == "0") {
                return;
            } else {
                if ($voted == "0") {
                    $voted = $player["voted"];
                } elseif ($voted != $player["voted"]) {
                    return;
                }
            }
        }
    }

    setPlayerDying($voted, $game, 1);
    resetVotes($game);
    setGameStatus($game, 3);
    header("location: ./");
    exit();
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

function playerVotes($player, $game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? AND voted = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=countPlayersVotes");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $game, $player);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $count = 0;
    if ($rs->num_rows > 0) {
        while ($rs->fetch_assoc()) {
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
    } else {
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
    } else {
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

function hexeUsedHeal($game) {
    $con = con();
    $qry = "UPDATE wergames SET hexHeals=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setGameStatus");
        exit();
    }

    $heals = gameData($game)["hexHeals"]-1;

    mysqli_stmt_bind_param($stmt, "ss", $heals, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function hexeUsedPoison($game) {
    $con = con();
    $qry = "UPDATE wergames SET hexMagic=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setGameStatus");
        exit();
    }

    $magic = gameData($game)["hexMagic"]-1;

    mysqli_stmt_bind_param($stmt, "ss", $magic, $game);
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

function setPlayerDying($player, int $game, $by) {
    $pData = playerDataByName($player, $game);
    $con = con();
    $qry = "UPDATE werplayer SET dying=? WHERE name=? AND game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setPlayerDying");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $by, $player, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function killPlayer($player, int $game, $by) {
    $pData = playerDataByName($player, $game);
    $con = con();
    $qry = "UPDATE werplayer SET dead=? WHERE name=? AND game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=killPlayer");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $by, $player, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($pData["lover"] !== 0) {
        killPlayer($pData["lover"], $game, 3);
    }
}

function votePlayer($voted, $target, $game) {
    $con = con();
    $qry = "UPDATE werplayer SET voted=? WHERE name=? AND game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=votePlayer");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $target, $voted, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function resetVotes($game) {
    $con = con();
    $qry = "UPDATE werplayer SET voted=? WHERE game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=resetVotes");
        exit();
    }

    $voted = "0";

    mysqli_stmt_bind_param($stmt, "ss", $voted, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function resetRoles($game) {
    $con = con();
    $qry = "UPDATE werplayer SET role=? WHERE game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=resetRoles");
        exit();
    }

    $zero = "0";

    mysqli_stmt_bind_param($stmt, "ss", $zero, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function generateRoles($game) {
    resetRoles($game);
    $gameData = gameData($_SESSION["gameid"]);
    $werwolfe = array();
    for ($i = 0; $i < $gameData["wercount"]; $i++) {
        $get = random_int(0, count(gamePlayers($game)) - 1);
        $wolf = gamePlayers($game)[$get];
        setPlayerRole($wolf["name"], $game, 1);
        $werwolfe[] = $wolf;
    }
    $get = random_int(0, count(gamePlayers($game)) - 1);
    while (array_search(gamePlayers($game)[$get], $werwolfe) !== false) {
        $get = random_int(0, count(gamePlayers($game)) - 1);
    }
    $hexe = gamePlayers($game)[$get];
    setPlayerRole($hexe["name"], $game, 2);
}

function gamesCount() {
    $con = con();
    $sql = "SELECT * FROM wergames;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=countGames");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $count = 0;
    if ($rs->num_rows > 0) {
        while ($rs->fetch_assoc()) {
            $count++;
        }
    }
    mysqli_stmt_close($stmt);

    return $count;
}

function allPlayersCount() {
    $con = con();
    $sql = "SELECT * FROM werplayer;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=countAllPlayer");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $count = 0;
    if ($rs->num_rows > 0) {
        while ($rs->fetch_assoc()) {
            $count++;
        }
    }
    mysqli_stmt_close($stmt);

    return reformatBIgInts($count);
}