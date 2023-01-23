<?php

require_once "helper/smp.help.php";

$all_players_json_stats_files = array_diff(scandir("/opt/mc/java/server/stats/"), array(".", ".."));
$json_stats = "{";
foreach ($all_players_json_stats_files as $players_json_stats_file) {
    $json_stats .= "\"".pathinfo($players_json_stats_file, PATHINFO_FILENAME)."\":";
    $json_stats .= file_get_contents("/opt/mc/java/server/stats/".$players_json_stats_file);
    if ($players_json_stats_file !== end($all_players_json_stats_files)) {
        $json_stats .= ",";
    }
}
$json_stats .= "}";
$stats = json_decode($json_stats, true);
$display = array("minecraft:leave_game");
$all_statistics = getStatistics();

if (isset($_GET["api"])) {
    echo $json_stats;
    exit();
} elseif (isset($_GET["display"])) {
    echo json_encode($display);
    exit();
} elseif (isset($_GET["statistics"])) {
    echo json_encode($all_statistics);
    exit();
} else {
    require_once "header.php";
}

?>
<script>
    function httpGet(theUrl) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
        xmlHttp.send( null );
        return xmlHttp.responseText;
    }

    function getPlayerTimeStr(uuid, data) {
        let totalSecs = data[uuid]["minecraft:play_one_minute"]["value"]/20;
        let days = Math.floor(totalSecs/86400);
        let hours = Math.floor((totalSecs % 86400)/3600);
        let minutes = Math.floor(((totalSecs % 86400)%3600)/60);
        let seconds = Math.floor(totalSecs%60);
        let timeStr = "";
        if (days > 0) {
            timeStr += days+"d "
        }
        if (hours > 0) {
            timeStr += hours+"h "
        }
        if (minutes > 0) {
            timeStr += minutes+"m "
        }
        if (seconds > 0) {
            timeStr += seconds+"s"
        }
        return timeStr;
    }
</script>
<h1 style="margin-top: 80px">SMP Stats</h1>
<table class="table">
    <thead>
    <tr>
        <th>Player</th>
        <th>Playtime</th>
        <?php
        foreach ($display as $key) {
            echo "<th>".$key."</th>";
        }
        ?>
    </tr>
    </thead>
    <?php
    foreach ($stats as $uuid => $player) {
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
        echo "<td class='time-td' uuid='".$uuid."'>".$timeStr."</td>";

        foreach ($display as $stat) {
            $data = $player[$stat];
            $Cal = new Field_calculate();
            $value = round($Cal->calculate($data["value"].$all_statistics[$stat]["factor"]), 2);
            echo "<td class='other-stat' namespace_key='".$stat."' uuid='".$uuid."'>".$value.$all_statistics[$stat]["symbol"]."</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
<script>
    function updateHomeLoop() { // create a loop function
        setTimeout(function() { // call a 3s setTimeout when the loop is called
            // your code here
            let data = JSON.parse(httpGet("https://sebis.net/smp.php?api"));
            let all_statistics = JSON.parse(httpGet("https://sebis.net/smp.php?statistics"));

            // update playtime
            let players_playtimes = document.getElementsByClassName("time-td");
            for (let playtime of players_playtimes) {
                let uuid = playtime.getAttribute("uuid");
                playtime.innerText = getPlayerTimeStr(uuid, data);
            }

            // update other stats
            let tds = document.getElementsByClassName("other-stat");
            for (let td of tds) {
                let key = td.getAttribute("namespace_key");
                let uuid = td.getAttribute("uuid");
                let value = parseFloat(data[uuid][key]["value"]+all_statistics[key]["factor"]);
                td.innerText = value+all_statistics[key]["symbol"];
            }

            updateHomeLoop(); // again which will trigger another
        }, 1000)
    }

    updateHomeLoop(); // start the loop
</script>