<?php

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
$display = array("Playtime" => array("PLAY_ONE_MINUTE", "/20/60/60", "h"));

if (!isset($_GET["api"])) {
    require_once "header.php";
} elseif (isset($_GET["display"])) {
    echo json_encode($display);
    exit();
} else {
    echo $json_stats;
    exit();
}

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

        foreach ($display as $namespace_key => $stat) {
            $data = $player[$namespace_key];
            $value = round(eval("return ".$data['value'].$stat[1].";"), 2);
            echo "<td class='".$namespace_key."' uuid='".$uuid."'>".$value.$stat[2]."</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
<script>
    function httpGet(theUrl) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
        xmlHttp.send( null );
        return xmlHttp.responseText;
    }

    let i = 0

    function myLoop() {         // create a loop function
        setTimeout(function() {   // call a 3s setTimeout when the loop is called
            // your code here

            let json_data = httpGet("https://sebis.net/smp.php?api");
            let data = JSON.parse(json_data);
            i++;
            let players_playtimes = document.getElementsByClassName("time-td");
            for (let playtime of players_playtimes) {
                let uuid = playtime.getAttribute("uuid");
                playtime.innerText = data[uuid]["minecraft:play_one_minute"]["value"];
            }

            myLoop();             // again which will trigger another
        }, 1000)
    }

    myLoop();                   // start the loop
</script>