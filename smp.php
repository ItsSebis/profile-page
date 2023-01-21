<?php

$all_players_json_stats_files = array_diff(scandir("/opt/mc/java/server/stats/"), array(".", ".."));
$stats = array();
foreach ($all_players_json_stats_files as $players_json_stats_file) {
    if (pathinfo($players_json_stats_file, PATHINFO_EXTENSION) == "json") {
        $stats[pathinfo($players_json_stats_file, PATHINFO_FILENAME)] = json_decode(file_get_contents("/opt/mc/java/server/stats/".$players_json_stats_file));
    } else {
        echo pathinfo($players_json_stats_file, PATHINFO_EXTENSION);
    }
}
$json_stats = json_encode($stats);
$stats = json_decode($json_stats);

if (!isset($_GET["api"])) {
    require_once "header.php";
} else {
    echo $json_stats;
    exit();
}

$display = array("Playtime" => "PLAY_ONE_MINUTE");
$all_json_statistics = file_get_contents("/opt/mc/java/server/statistics.list");
$all_statistics = json_decode($all_json_statistics, true);

?>
<h1 style="margin-top: 80px">SMP Stats</h1>
<table class="table">
    <thead>
    <tr>
        <th>Player</th>
        <th>Playtime</th>
        <?php
        foreach ($display as $key => $value) {
            echo "<th>".$key."</th>";
        }
        ?>
    </tr>
    </thead>
    <?php
    foreach ($all_players_json_stats_files as $players_file) {
        $player = json_decode(file_get_contents($players_file));
        print_r($player);
        exit();
        echo "<tr>";
        echo "<td>".$player['IGN']."</td>";

        $totalSecs = $player["minecraft:play_one_minute"]["value"] / 20;
        $days = floor($totalSecs / 86400);
        $hours = floor(($totalSecs % 86400) / 3600);
        $minutes = floor((($totalSecs % 86400) % 3600) / 60);
        $seconds = floor($totalSecs % 60);
        $timeStr = "";
        if ($days > 0) {
            $timeStr .= $days."d ";
        }
        if ($hours > 0) {
            $timeStr .= $hours."h ";
        }
        if ($minutes > 0) {
            $timeStr .= $minutes."m ";
        }
        if ($seconds > 0) {
            $timeStr .= $seconds."s";
        }
        echo "<td>".$timeStr."</td>";

        foreach ($player as $stat) {
            if (in_array($stat["name"], $display)) {
                // display stat
                echo "<td>".$stat['value']."</td>";
            }
        }
        echo "</tr>";
    }
    ?>
</table>