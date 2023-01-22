<?php

class Field_calculate {
    const PATTERN = '/(?:-?\d+(?:\.?\d+)?[+\-*\/])+-?\d+(?:\.?\d+)?/';

    const PARENTHESIS_DEPTH = 10;

    public function calculate($input){
        if(strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null){
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+-\*/\(\)]', '', $input);

            //  Calculate each of the parenthesis from the top
            $i = 0;
            while(strpos($input, '(') || strpos($input, ')')){
                $input = preg_replace_callback('/\(([^()]+)\)/', 'self::callback', $input);

                $i++;
                if($i > self::PARENTHESIS_DEPTH){
                    break;
                }
            }

            //  Calculate the result
            if(preg_match(self::PATTERN, $input, $match)){
                return $this->compute($match[0]);
            }
            // To handle the special case of expressions surrounded by global parenthesis like "(1+1)"
            if(is_numeric($input)){
                return $input;
            }

            return 0;
        }

        return $input;
    }

    private function compute($input){
        $compute = create_function('', 'return '.$input.';');

        return 0 + $compute();
    }

    private function callback($input){
        if(is_numeric($input[1])){
            return $input[1];
        }
        elseif(preg_match(self::PATTERN, $input[1], $match)){
            return $this->compute($match[0]);
        }

        return 0;
    }
}

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
$display = array("Games quit" => array("key" => "minecraft:leave_game", "name" => "LEAVE_GAME", "factor" => "", "symbol" => ""));

if (isset($_GET["api"])) {
    echo $json_stats;
    exit();
} elseif (isset($_GET["display"])) {
    echo json_encode($display);
    exit();
} else {
    require_once "header.php";
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

        foreach ($display as $stat) {
            $namespace_key = $stat["key"];
            $data = $player[$namespace_key];
            $Cal = new Field_calculate();
            $value = round($Cal->calculate($data["value"].$stat["factor"]), 2);
            echo "<td class='".$namespace_key."' uuid='".$uuid."'>".$value.$stat["symbol"]."</td>";
            echo "<script>console.log('".$data["value"].$stat["factor"]." = ".$value."')</script>";
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

    function myLoop() {         // create a loop function
        setTimeout(function() {   // call a 3s setTimeout when the loop is called
            // your code here

            // update playtime
            let json_data = httpGet("https://sebis.net/smp.php?api");
            let data = JSON.parse(json_data);
            let players_playtimes = document.getElementsByClassName("time-td");
            for (let playtime of players_playtimes) {
                let uuid = playtime.getAttribute("uuid");
                let totalSecs = data[uuid]["minecraft:play_one_minute"]["value"]/20;
                let days = Math.floor(totalSecs/86400);
                let hours = Math.floor((totalSecs % 86400)/3600);
                let minutes = Math.floor(((totalSecs % 86400)%3600)/60);
                let seconds = Math.floor(totalSecs%60)
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
                playtime.innerText = timeStr;
            }

            // update other stats
            let json_display = httpGet("https://sebis.net/smp.php?display");
            let display = JSON.parse(json_display);
            for (let stat of Object.values(display)) {
                console.log(stat)
                let tds = document.getElementsByClassName(stat["key"]);
                for (let td of tds) {
                    let uuid = td.getAttribute("uuid");
                    let value = parseFloat(data[uuid][stat["key"]]["value"]+stat["factor"]);
                    console.log(value);
                    td.innerText = value+stat["symbol"];
                }
            }

            myLoop();             // again which will trigger another
        }, 1000)
    }

    myLoop();                   // start the loop
</script>