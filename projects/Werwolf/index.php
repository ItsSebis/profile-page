<?php
require_once "functions.php";
session_start();
if (isset($_POST["join"])) {
    if (empty($_POST["gameid"]) || empty($_POST["plName"])) {
        header("location: ./?error=emptyf");
        exit();
    } elseif (gameData($_POST["gameid"]) !== false && playerDataByName($_POST["plName"], $_POST["gameid"]) === false) {
        $_SESSION["plName"] = $_POST["plName"];
        $_SESSION["gameid"] = $_POST["gameid"];
        createPlayer($_SESSION["gameid"], $_SESSION["plName"]);
        header("location: ./");
        exit();
    } elseif (gameData($_POST["gameid"]) === false) {
        header("location: ./?error=noGame");
        exit();
    } elseif (playerDataByName($_POST["plName"], $_POST["gameid"]) !== false) {
        header("location: ./?error=nameExists");
        exit();
    }
}

elseif (isset($_POST["start"])) {
    if (gameData($_SESSION["gameid"])["host"] == $_SESSION["plName"]) {
        setGameStatus($_SESSION["gameid"], 1);
    }
    header("location: ./");
    exit();
}

elseif (isset($_POST["create"])) {
    if (empty($_POST["plName"])) {
        header("location: ./?error=emptyfC");
        exit();
    } else {
        $_SESSION["plName"] = $_POST["plName"];
        $_SESSION["gameid"] = createGame();
        createPlayer($_SESSION["gameid"], $_SESSION["plName"]);
    }
    header("location: ./");
    exit();
}

elseif (isset($_POST["wervote"])) {
    votePlayer($_SESSION["plName"], $_POST["wervote"], $_SESSION["gameid"]);
    werVoting($_SESSION["gameid"]);
}

elseif (isset($_POST["hexeOpfer"])) {
    if ($_POST["hexeOpfer"] == "heal" && gameData($_SESSION["gameid"])["hexHeals"] > 0) {
        setPlayerDying($_POST["target"], $_SESSION["gameid"], 0);
    }
    if (gameData($_SESSION["gameid"])["hexMagic"] > 0) {
        setGameStatus($_SESSION["gameid"], 4);
    } else {
        setGameStatus($_SESSION["gameid"], 100);
    }
    header("location: ./");
    exit();
}

elseif (isset($_POST["hexKill"])) {
    setPlayerDying($_POST["hexKill"], $_SESSION["gameid"], 2);
    hexeUsedPoison($_SESSION["gameid"]);
    setGameStatus($_SESSION["gameid"], 100);
    header("location: ./");
    exit();
}
?>
<html lang="de" style="overflow: hidden;">
<head>
    <title>Projects | <?php echo(basename(__DIR__)); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<a style="position: fixed; top: 10px; left: 10px;" href="..">← Back</a>
<!--style="position: fixed; top: 10px; right: 10px;"-->
<div class="stats">
    <h2>Alpha 0.0.2</h2><br>
    <p>Open Games: <span style="color: #00cccc"><?php echo(gamesCount()); ?></span></p>
    <p>Online Players: <span style="color: #00cccc"><?php echo(allPlayersCount()); ?></span></p>
</div>
<div class="main" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); height: 75%;">
    <?php
    if (isset($_GET["error"]) && $_GET["error"] == "1") {
        echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: red; font-size: 3rem;'>MySQL error!<br> Try again later!</p>";
        exit();
    }
    if (isset($_SESSION["gameid"]) && gameData($_SESSION["gameid"]) !== false && playerDataByName($_SESSION["plName"], $_SESSION["gameid"]) !== false) {
        // In Game
        $game = gameData($_SESSION["gameid"]);
        $player = playerDataByName($_SESSION["plName"], $_SESSION["gameid"]);
        echo "<h1 id='name'>".$player["name"]."</h1>";

        if ($game["status"] === 0) {
            // Pre Game
            ?>
            <p style="color: gray"><?php echo("Spiel-Id: #" . $game["id"]); ?></p>

            <div style='border: solid #8e8e8e; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 60%; background-color: #363636; overflow: hidden; overflow-y: scroll'>
                <?php echoPlayers($game["id"]); ?>
            </div>
            <form action="./" method="post">
                <button type="submit" name="start" <?php if(playerCount($game["id"])<=4 || gameData($_SESSION["gameid"])["host"] != $_SESSION["plName"]){echo("disabled");} ?>>
                    Start <span style="color: grey;">(<?php echo(playerCount($game["id"])."/5 Spieler"); ?>)</span></button>
            </form>

            <?php
            header("refresh: 5");
        }

        elseif ($game["status"] == 1) {
            // Role generation

            if ($game["host"] == $player["name"]) {
                generateRoles($game["id"]);
            }
            sleep(2);
            $role = getRoles()[$player["role"]];
            echo "<p style='font-size: 2rem; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>
                    Deine Rolle ist '".$role."'!<br>
                    <span style='color: lime'>Spiel startet in 20 Sekunden!</span>
                </p>";
            setGameStatus($game["id"], 2);
            header("refresh: 20");
        }

        elseif ($game["status"] >= 2 && $game["status"] < 100) {
            // Real Game
            ?>

            <p style="color: gray; margin-top: 1px;"><?php echo(getRoles()[$player["role"]]); ?></p>

            <?php

            if ($game["status"] == 2) {
                // Werwölfe
                if ($player["role"] != 1) {
                    echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: lightcoral; font-size: 1.5rem'>**Die Werwölfe erwachen**</p>";
                } else {
                    echo "<div style='border: solid #424242; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 70%; background-color: #303030; overflow: hidden; overflow-y: initial'>";
                    werVotePlayers($game["id"]);
                    echo "</div>";
                }
            } elseif ($game["status"] == 3) {
                // Hexe healing
                if ($player["role"] != 2) {
                    echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: lightcoral; font-size: 1.5rem'>**Die Hexe erwacht**</p>";
                } else {
                    hexeHealing($game["id"]);
                }
            } elseif ($game["status"] == 4) {
                // Hexe killing
                if ($player["role"] != 2) {
                    echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: lightcoral; font-size: 1.5rem'>**Die Hexe erwacht**</p>";
                } else {
                    echo "<p style='margin-top: 20px;'>Wenn du einen Verdacht hast, kannst du jetzt noch jemanden Vergiften!</p>";
                    echo "<div style='border: solid #424242; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 60%; background-color: #303030; overflow: hidden; overflow-y: initial'>";
                    hexeKilling($game["id"]);
                    echo "</div>";
                    echo "<form action='./' method='post'>
                        <button type='submit' name='hexKillSkip'>Niemanden vergiften</span></button>
                        </form>";
                }
            }

            header("refresh: 2");
        }

        elseif ($game["status"] >= 100) {

        }

    } else {
        // Game selection
        ?>

        <form action="./" method="post"
              style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <input type="text" name="plName" placeholder="Dein Spitzname..."><br>
            <input type="number" name="gameid" max="99999999" min="0" placeholder="Spiel Id..."><br><br>
            <button type="submit" name="join">Beitreten</button><br><br>
            <button type="submit" name="create">Spiel erstellen</button>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "noGame") {
                    echo "<br><br><p style='color: red'>Dieser Spielcode existiert nicht!</p>";
                } elseif ($_GET["error"] == "nameExists") {
                    echo "<br><br><p style='color: red'>In dem angegebenem Spiel ist bereits ein Spieler mit diesem Namen!</p>";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<br><br><p style='color: red'>Du musst alle Felder ausfüllen, um einem Spiel beizutreten!</p>";
                } elseif ($_GET["error"] == "emptyfC") {
                    echo "<br><br><p style='color: red'>Gib bitte deinen Namen an, um ein Spiel zu erstellen!</p>";
                }
            }
            ?>
        </form>

        <?php
    }
    ?>
</div>
