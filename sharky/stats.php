<?php
require_once "config/dbh.php";
require_once "extensions/functions.php";
?>

<div class="stats">
    <h2>Statistics</h2>
    <p>Servers: <?php echo(getGuildCount()); ?></p>
    <p>Saved Members: <?php echo(getMemberCount()); ?></p>
    <p>Operators: <?php echo(getOperatorCount()); ?></p>
    <p>Read Messages: <?php echo(readMessageCount()); ?></p>
    <p>Read Words: <?php echo(readWordCount()); ?></p>
</div>