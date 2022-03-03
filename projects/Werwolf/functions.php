<?php

require_once "dbh.conf.php";
require_once "../publicFunc.php";

function getRoles() {
    return array("<span style='color: lime'>Dorfbewohner</span>", "<span style='color: red'>Werwolf</span>", "<span style='color: green'>Hexe</span>",
        "<span style='color: #003cff'>Amor</span>", "<span style='color: brown'>Urwolf</span>");
}

function getStati() {
    return array(0 => "Pregame", 1 => "Setup", 2 => "Werwölfe", 3 => "Hexe", 4 => "Hexe", 5 => "Amor", 6 => "Urwolf",
        100 => "Postround", 101 => "Anklage", 102 => "Abstimmungsende", 1000 => "Postgame");
}

function getDeathMsgs() {
    return array(0 => "Lebend", 1 => "Opfer", 2 => "Vergiftet", 3 => "Liebeskummer", 4 => "Hingerichtet");
}

/**
 * @throws Exception
 */
function createGame($test=0) {
    $game = random_int(1, 99999999);
    while (gameData($game) !== false) {
        $game = random_int(1, 99999999);
    }
    $con = con();
    $sql = "INSERT INTO wergames (id, host, test) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=createGame");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $game, $_SESSION["plName"], $test);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $game;
}

function testWin($game) {
    $host = gameData($game)["host"];
    $living = gameLiving($game);

    if (count($living) == 2 && $living[0]["lover"] != "0" && $living[0]["lover"] == $living[1]["name"]) {
        if ($host == $_SESSION["plName"]) {
            foreach ($living as $item) {
                if (accountDataByName($item["name"]) !== false) {
                    $usr = accountDataByName($item["name"]);
                    setUserStat($usr["id"], "werlovwin", $usr["werlovwin"] + 1);
                }
            }
        }
        setGameStatus($game, 1000);
        header("location: ./?win=3");
        exit();
    }

    $dorftrottel = array();
    $wolfe = array();
    foreach ($living as $livingPlayer) {
        if ($livingPlayer["role"] == 1 || $livingPlayer["role"] == 4) {
            $wolfe[] = $livingPlayer;
        } else {
            $dorftrottel[] = $livingPlayer;
        }
    }
    if (count($wolfe) > count($dorftrottel)) {
        if ($host == $_SESSION["plName"]) {
            foreach ($wolfe as $item) {
                if (accountDataByName($item["name"]) !== false) {
                    $usr = accountDataByName($item["name"]);
                    setUserStat($usr["id"], "werwerwin", $usr["werwerwin"] + 1);
                }
            }
        }
        setGameStatus($game, 1000);
        header("location: ./?win=1");
        exit();
    } elseif (count($wolfe) == 0) {
        if ($host == $_SESSION["plName"]) {
            foreach ($dorftrottel as $item) {
                if (accountDataByName($item["name"]) !== false) {
                    $usr = accountDataByName($item["name"]);
                    setUserStat($usr["id"], "wervilwin", $usr["wervilwin"] + 1);
                }
            }
        }
        setGameStatus($game, 1000);
        header("location: ./?win=0");
        exit();
    }
}

/**
 * @throws Exception
 */
function createPlayer($game, $name) {
    if (isset($_SESSION["id"]) && accountData($_SESSION["id"])["username"] == $name) {
        $user = accountData($_SESSION["id"]);
        $player = playerDataLoggedIn($user["username"]);
        if ($player["game"] != $game) {
            delPlayer($player["name"], $player["game"]);
        } else {
            return;
        }
    }
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
        if ($player["dying"] == 1) {
            $dying = $player;
        }
    }
    echo "<div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>";
    echo "<h4>'".$dying["name"]."' ist das Opfer der Werwölfe.</h4>";
    echo "<p>Was möchtest du tun?</p><br>";
    $healDis = "";
    if (gameData($game)["hexHeals"] == 0) {
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
            $color = "#e8e8fe";
            if (!preg_match("/[^a-zA-Z]+/", $row["name"]) && accountDataByName($row["name"]) !== false) {
                $color = roleData(accountDataByName($row["name"])["role"])["color"];
            }
            $host = "";
            $preHost = "";
            if (gameData($_SESSION["gameid"])["host"] == $row["name"]) {
                $host = "<span style='font-size: 1.3rem; color: gold'>*</span>";
                $preHost = "<span style='font-size: 1.3rem; color: gold'>*</span>";
            }
            echo "<p style='margin: 10px auto; font-size: 1.8rem; color: ".$color."'>" . $preHost . $row['name'] . $host . "</p>";
        }
    }
    mysqli_stmt_close($stmt);
}

function voting($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=verVoting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $wer = false;
            if (playerDataByName($_SESSION["plName"], $_SESSION["gameid"])["role"] == 1 || playerDataByName($_SESSION["plName"], $_SESSION["gameid"])["role"] == 4) {
                $wer = true;
            }
            if ($wer && ($row["role"] == 1 || $row["role"] == 4)) {
                $wStr = " <span style='color: red'>Werwolf</span>";
            } else {
                $wStr = "";
            }
            if ($row["name"] != $_SESSION["plName"] && $row["dead"] == 0) {
                echo "<p style='margin: 10px auto; font-size: 1.8rem;'><form action='./' method='post'>
                        <button type='submit' name='vervote' value='".$row['name']."'>".$row['name']." <span style='color: red;'>".playerVotes($row['name'], $game).$wStr."</span></button>
                        </form></p>
                    ";
            }
        }
    }
    mysqli_stmt_close($stmt);
}

function verVotingResult($game) {
    $voted = array();
    foreach (gamePlayers($game) as $player) {
        if ($player["dead"] != 0) {
            continue;
        }
        if ($player["voted"] == "0") {
            return;
        } else {
            $voted[] = $player["voted"];
        }
    }

    $sorted = array_count_values($voted);

    if (array_values($sorted)[0] == array_values($sorted)[1]) {
        return;
    }

    $voted = array_search(array_values($sorted)[0], $sorted);

    setPlayerDying($voted, $game, 4);
    resetVotes($game);
    setGameStatus($game, 102);
    header("location: ./?killed=".$voted);
    exit();
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
            if ($row["role"] != 1 && $row["dead"] == 0 && $row["role"] != 4) {
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

function urwolfSelect($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=urwolfSelect");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            if ($row["role"] != 1 && $row["dead"] == 0 && $row["dying"] == 0 && $row["role"] != 4) {
                echo "<p style='margin: 10px auto; font-size: 1.8rem;'><form action='./' method='post'>
                        <button type='submit' name='ursel' value='".$row['name']."'>".$row['name']."</span></button>
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
        if ($player["dead"] != 0) {
            continue;
        }
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

function gameLiving($game) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE game = ? AND dead=? ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=gameLiving");
        exit();
    }

    $zero = 0;

    mysqli_stmt_bind_param($stmt, "ss", $game, $zero);
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

function allGames() {
    $con = con();
    $sql = "SELECT * FROM wergames ORDER BY `id` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=allGames");
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
        while ($rs->fetch_assoc()) {
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

function playerDataLoggedIn($str) {
    $con = con();
    $sql = "SELECT * FROM werplayer WHERE name = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=playerDataLoggedIn");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $str);
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

function setPlayerLover($player, $game, $lover) {
    $con = con();
    $qry = "UPDATE werplayer SET lover=? WHERE name=? AND game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setPlayerLover");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $lover, $player, $game);
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

    $loverData = playerDataByName($pData["lover"], $game);
    if ($pData["lover"] != "0" && $loverData["dying"] == 0 && $loverData["dead"] == 0) {
        setPlayerDying($pData["lover"], $game, 3);
    }
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

    $loverData = playerDataByName($pData["lover"], $game);
    if ($pData["lover"] != "0" && $loverData["dying"] == 0 && $loverData["dead"] == 0) {
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

function resetValues($game) {
    resetGameValues($game);
    $con = con();
    $qry = "UPDATE werplayer SET role=?, dead=?, dying=?, voted=?, lover=? WHERE game = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=resetValues");
        exit();
    }

    $zero = "0";

    mysqli_stmt_bind_param($stmt, "ssssss", $zero, $zero, $zero, $zero, $zero, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function resetGameValues($game) {
    $con = con();
    $qry = "UPDATE wergames SET hexMagic=?, hexHeals=? WHERE id = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=resetGameValues");
        exit();
    }

    $zero = 1;

    mysqli_stmt_bind_param($stmt, "sss", $zero, $zero, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setGameHost($game, $host) {
    $con = con();
    $qry = "UPDATE wergames SET host=? WHERE id = ?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setGameHost");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $host, $game);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/**
 * @throws Exception
 */
function generateRoles($game) {
    // generate Werwölfe
    $living = gamePlayers($game);
    $gameData = gameData($game);
    for ($i = 0; $i < $gameData["wercount"]; $i++) {
        $get = random_int(0, count($living) - 1);
        $wolf = $living[$get];
        setPlayerRole($wolf["name"], $game, 1);
        if (accountDataByName($wolf["name"]) !== false) {
            $usr = accountDataByName($wolf["name"]);
            setUserStat($usr["id"], "werwer", $usr["werwer"]+1);
        }
        unset($living[$get]);
    }

    // generate Hexe
    $lTemp = $living;
    $living = array();
    foreach ($lTemp as $temp) {
        $living[] = $temp;
    }
    $get = random_int(0, count($living) - 1);
    $hexe = $living[$get];
    setPlayerRole($hexe["name"], $game, 2);
    if (accountDataByName($hexe["name"]) !== false) {
        $usr = accountDataByName($hexe["name"]);
        setUserStat($usr["id"], "werhex", $usr["werhex"]+1);
    }
    unset($living[$get]);

    // generate Amor
    $lTemp = $living;
    $living = array();
    foreach ($lTemp as $temp) {
        $living[] = $temp;
    }
    $get = random_int(0, count($living) - 1);
    $amor = $living[$get];
    setPlayerRole($amor["name"], $game, 3);
    if (accountDataByName($amor["name"]) !== false) {
        $usr = accountDataByName($amor["name"]);
        setUserStat($usr["id"], "weramor", $usr["weramor"]+1);
    }
    unset($living[$get]);

    // generate Urwolf
    if (gameData($_SESSION["gameid"])["uron"]) {
        $lTemp = $living;
        $living = array();
        foreach ($lTemp as $temp) {
            $living[] = $temp;
        }
        $get = random_int(0, count($living) - 1);
        $sel = $living[$get];
        setPlayerRole($sel["name"], $game, 4);
        if (accountDataByName($sel["name"]) !== false) {
            $usr = accountDataByName($sel["name"]);
            setUserStat($usr["id"], "werur", $usr["werur"] + 1);
        }
        unset($living[$get]);
    }

    // setting played statistic
    foreach (gameLiving($game) as $item) {
        if (accountDataByName($item["name"]) !== false) {
            $usr = accountDataByName($item["name"]);
            setUserStat($usr["id"], "werplayed", $usr["werplayed"]+1);
        }
    }
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

/**
 * @return void
 */
function todesAnzeigen(): void {
    foreach (gamePlayers($_SESSION["gameid"]) as $player) {
        if ($player["dying"] != 0) {
            killPlayer($player["name"], $_SESSION["gameid"], $player["dying"]);
        }
    }
    foreach (gamePlayers($_SESSION["gameid"]) as $player) {
        if ($player["dying"] != 0) {
            $diedFrom = "";
            if ($player["dying"] == 1) {
                $diedFrom = " (<span style='color: red'>Opfer</span>)";
            } elseif ($player["dying"] == 2) {
                $diedFrom = " (<span style='color: #8e2533'>Vergiftet</span>)";
            } elseif ($player["dying"] == 3) {
                $diedFrom = " (<span style='color: deeppink'>Wahre Liebe</span>)";
            } elseif ($player["dying"] == 4) {
                $diedFrom = " (<span style='color: royalblue'>Verurteilt vom Volk</span>)";
            }
            echo "<p>" . $player['name'] . $diedFrom . "</p>";
            setPlayerDying($player["name"], $_SESSION["gameid"], 0);
        }
    }
    echo "</div>";
}

/**
 * @throws Exception
 */
function delPlayer($player, $game) {
    $con = con();
    $qry = "DELETE FROM werplayer WHERE name=? AND game=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $player, $game);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (count(gamePlayers($game)) == 0) {
        delGame($game);
    } elseif (gameData($game)["host"] == $player) {
        setGameHost($game, gamePlayers($game)[random_int(0, count(gamePlayers($game))-1)]["name"]);
    }
}

function delGame($game) {
    $con = con();
    $qry = "DELETE FROM wergames WHERE id=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $game);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

/**
 * @throws Exception
 */
function clearGames() {
    foreach (allGames() as $game) {
        $diff = untilNow($game["update"]);
        $mins = $diff->days * 24 * 60;
        $mins += $diff->h * 60;
        $mins += $diff->i;
        #echo($game["id"]." is $mins minutes old.\n");
        if ($mins >= 30) {
            foreach (gamePlayers($game["id"]) as $p) {
                delPlayer($p["name"], $game["id"]);
            }
            delGame($game["id"]);
        }
    }
}

function isLiving($role, $game) {
    $living = gameLiving($game);
    $live = false;
    foreach ($living as $item) {
        if ($item["role"] == $role) {
            $live = true;
        }
    }
    return $live;
}
