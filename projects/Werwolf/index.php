<?php
require_once "functions.php";
session_start();

if (isset($_SESSION["gameid"]) && gameData($_SESSION["gameid"]) === false) {
    session_unset();
    session_destroy();
    header("location: ./");
    exit();
}

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
    if (playerDataByName($_SESSION["plName"], $_SESSION["gameid"])["dead"] == 0) {
        votePlayer($_SESSION["plName"], $_POST["wervote"], $_SESSION["gameid"]);
        werVoting($_SESSION["gameid"]);
    }
}

elseif (isset($_POST["hexeOpfer"])) {
    if ($_POST["hexeOpfer"] == "heal" && gameData($_SESSION["gameid"])["hexHeals"] > 0) {
        hexeUsedHeal($_SESSION["gameid"]);
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

elseif (isset($_POST["vervote"])) {
    if (playerDataByName($_SESSION["plName"], $_SESSION["gameid"])["dead"] == 0) {
        votePlayer($_SESSION["plName"], $_POST["vervote"], $_SESSION["gameid"]);
        if (gameData($_SESSION["gameid"])["status"] == 101) {
            verVotingResult($_SESSION["gameid"]);
        }
    }
}

elseif (isset($_POST["next"])) {
    setGameStatus($_SESSION["gameid"], 0);
    header("location: ./");
    exit();
}

elseif (isset($_POST["love"])) {
    if ($_POST["player1"] == $_POST["player2"]) {
        header("location: ./?love=playerEqual");
    } else {
        setPlayerLover($_POST["player1"], $_SESSION["gameid"], $_POST["player2"]);
        setPlayerLover($_POST["player2"], $_SESSION["gameid"], $_POST["player1"]);
        setGameStatus($_SESSION["gameid"], 2);
        header("location: ./");
    }
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
    <h2>Alpha 0.0.5</h2><br>
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
        $pNeeded = $game["wercount"]*2+1;
        echo "<h1 id='name'>".$player["name"]."</h1>";

        if ($game["status"] > 1) {
            if ($player["dead"] != "0") {
                echo '<p style="color: red; margin-top: 1px; font-size: 1.2rem; text-decoration: underline">Du bist tot</p>';
            }
            if ($player["lover"] != "0") {
                echo '<p style="color: deeppink; margin-top: 1px;">'.$player["lover"].': '.getRoles()[playerDataByName($player["lover"], $game["id"])["role"]].'</p>';
            }
            echo '<p style="color: gray; margin-top: 1px;">Du: '.getRoles()[$player["role"]].'</p>';
        }

        if ($game["status"] == 0) {
            // Pre Game
            ?>
            <p style="color: gray"><?php echo("Spiel-Id: #" . $game["id"]); ?></p>

            <div style='border: solid #8e8e8e; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 60%; background-color: #363636; overflow: hidden; overflow-y: initial'>
                <?php echoPlayers($game["id"]); ?>
            </div>
            <form action="./" method="post">
                <button type="submit" name="start" <?php if(playerCount($game["id"])<$pNeeded || gameData($_SESSION["gameid"])["host"] != $_SESSION["plName"]){echo("disabled");} ?>>
                    Start <span style="color: grey;">(<?php echo(playerCount($game["id"])."/".$pNeeded." Spieler"); ?>)</span></button>
            </form>

            <?php
            header("refresh: 5");
        }

        elseif ($game["status"] == 1) {
            // Role generation

            if ($game["host"] == $player["name"]) {
                resetValues($game["id"]);
                sleep(1);
                generateRoles($game["id"]);
            } else {
                sleep(1);
            }
            $player = playerDataByName($_SESSION["plName"], $_SESSION["gameid"]);
            $role = getRoles()[$player["role"]];
            echo "<p style='font-size: 2rem; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>
                    Deine Rolle ist '".$role."'!<br>
                    <span style='color: lime'>Spiel startet in 20 Sekunden!</span>
                </p>";
            setGameStatus($game["id"], 5);
            header("refresh: 20");
        }

        elseif ($game["status"] >= 2 && $game["status"] < 100) {
            // Real Game

            if ($game["status"] == 2) {
                // Werwölfe
                if ($player["role"] != 1) {
                    echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: lightcoral; font-size: 1.5rem'>**Die Werwölfe erwachen**</p>";
                } else {
                    echo "<div style='border: solid #424242; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 70%; background-color: #303030; overflow: hidden; overflow-y: initial'>";
                    werVotePlayers($game["id"]);
                    echo "</div>";
                }
            }

            elseif ($game["status"] == 3) {
                // Hexe healing
                if ($player["role"] != 2) {
                    echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: lightcoral; font-size: 1.5rem'>**Die Hexe erwacht**</p>";
                } else {
                    hexeHealing($game["id"]);
                }
            }

            elseif ($game["status"] == 4) {
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

            elseif ($game["status"] == 5) {
                // Armor verlieben
                $isLoved = false;
                foreach (gamePlayers($game["id"]) as $p) {
                    if ($p["lover"] != "0") {
                        $isLoved = true;
                    }
                }
                if ($isLoved) {
                    setGameStatus($game["id"], 2);
                }
                if (playerDataByName($_SESSION["plName"], $_SESSION["gameid"])["role"] == 3) {
                    echo "<div style='border: solid #424242; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 60%; background-color: #303030; overflow: hidden; overflow-y: initial'>";
                    echo "<form action='./' method='post'>";
                    echo "<select name='player1'>";
                    foreach (gamePlayers($game["id"]) as $p) {
                        echo "<option value='".$p['name']."'>".$p['name']."</option>";
                    }
                    echo "</select>";
                    echo "<select name='player2'>";
                    foreach (gamePlayers($game["id"]) as $p) {
                        echo "<option value='".$p['name']."'>".$p['name']."</option>";
                    }
                    echo "</select><br><br>";
                    echo "<button type='submit' name='love'>Verlieben</button>";
                    echo "</form>";
                    echo "</div>";
                    if (isset($_GET["love"])) {
                        if ($_GET["love"] == "playerEqual") {
                            echo "<p style='font-size: 1.7rem; color: #8e2533'>Du kannst nicht einen Spieler in sich selbst verlieben!</p>";
                        }
                    }
                } else {
                    echo "<p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: lightcoral; font-size: 1.5rem'>**Der Armor erwacht**</p>";
                }
            }

            header("refresh: 2");
        }

        elseif ($game["status"] == 100) {
            // Day
            echo "<div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-decoration: underline'><p>Das Dorf trauert um:</p>";
            todesAnzeigen();
            setGameStatus($_SESSION["gameid"], 101);
            header("refresh: 15");
        }

        elseif ($game["status"] == 101) {
            echo "<h3 style='margin-top: 20px;'>Wen möchtest du anklagen?</h3>";
            echo "<div style='border: solid #424242; border-radius: 14px; width: 60%; margin: 20px auto 30px; height: 60%; background-color: #303030; overflow: hidden; overflow-y: initial'>";
            voting($game["id"]);
            echo "</div>";
            header("refresh: 3");
        }

        elseif ($game["status"] == 102) {
            echo "<div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)'><p>Ende der Abstimmung:</p>";
            todesAnzeigen();
            setGameStatus($_SESSION["gameid"], 5);
            testWin($game["id"]);
            header("refresh: 15");
        }

        elseif ($game["status"] == 1000) {
            echo "<div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)'>";
            $winner = $_GET["win"];
            $wString = "";
            if ($winner == 1) {
                $wString = "<span style='color: red; font-size: 2.5rem'>Die Werwölfe gewinnen</span>";
            } elseif ($winner == 0) {
                $wString = "<span style='color: lime; font-size: 2.5rem'>Das Dorf gewinnt</span>";
            } elseif ($winner == 3) {
                $wString = "<span style='color: deeppink; font-size: 2.5rem'>Die Verliebten gewinnen</span>";
            }
            echo($wString);
            echo "<br><br>";
            foreach (gamePlayers($game["id"]) as $p) {
                $pName = $p['name'];
                if ($p["dead"] != 0) {
                    $pName = "<span style='text-decoration: line-through'>".$p['name']."</span>";
                }
                echo "<p>".$pName." (".getRoles()[$p['role']].")</p>";
            }
            echo "</div><br>";
            echo "<form action='./' method='post'><button type='submit' name='next'>Neues Spiel</button></form>";
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
