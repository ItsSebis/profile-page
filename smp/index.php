<?php
$json_stats = file_get_contents("/opt/stats_TrackMe.json");

$stats = json_decode($json_stats, true);

print_r($stats);