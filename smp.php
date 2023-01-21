<?php

$json_stats = file_get_contents("/opt/stats_TrackMe.json");

$stats = json_decode($json_stats, true);

if (!isset($_GET["api"])) {
    require_once "header.php";
} else {
    echo $json_stats;
    exit();
}

?>
<h1 style="margin-top: 70px">SMP Stats</h1>
<table class="table">
    <thead>
    <tr>
        <th>Player</th>
    </tr>
    </thead>
    <?php
    foreach ($stats as $player) {
        echo "<tr>";
        echo "<td>".$player['IGN']."</td>";
        foreach ($player as $stat) {
            if (is_array($stat)) {
                // display stat
                echo "<td>".$stat['value']."</td>";
            }
        }
        echo "</tr>";
    }
    ?>
</table>