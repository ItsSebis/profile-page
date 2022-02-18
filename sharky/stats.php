<?php
require_once "config/dbh.php";
require_once "extensions/functions.php";
?>

<div class="stats">
    <h2>Statistics</h2>
    <p>Servers: <?php echo(getGuildCount()); ?></p>
    <p>Saved Members: <?php echo(allMemberCount()); ?></p>
    <p>Read Messages: <?php #echo(readMessageCount()); ?></p>
    <p>Read Words: <?php echo(readWordCount()); ?></p>
    <p>Numbers counted: <?php echo(countedNumbers()); ?></p>
    <p>Operators: <?php echo(getOperatorCount()); ?></p>
</div>