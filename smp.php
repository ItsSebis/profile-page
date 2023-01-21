<?php

$json_stats = file_get_contents("/opt/mc/java/server/stats_TrackMe.json");

$stats = json_decode($json_stats, true);

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
        <?php
        foreach ($display as $key => $value) {
            echo "<th>".$key."</th>";
        }
        ?>
    </tr>
    </thead>
    <?php
    foreach ($stats as $player) {
        echo "<tr>";
        echo "<td>".$player['IGN']."</td>";
        $totalSecs = $player["minecraft:play_one_minute"]["value"] / 20;
        $days = $totalSecs / 86400;
        $hours = ($totalSecs % 86400) / 3600;
        $minutes = (($totalSecs % 86400) % 3600) / 60;
        $seconds = $totalSecs % 60;
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