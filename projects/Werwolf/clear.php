<?php
require_once "functions.php";

foreach (allGames() as $game) {
    $diff = untilNow($game["update"]);
    $mins = $diff->days * 24 * 60;
    $mins += ($diff->h+1) * 60;
    $mins += $diff->i;
    echo($game["id"]." is $mins minutes old.\n");
    if ($mins >= 45) {
        foreach (gamePlayers($game["id"]) as $p) {
            delPlayer($p["name"], $game["id"]);
        }
        delGame($game["id"]);
    }
}