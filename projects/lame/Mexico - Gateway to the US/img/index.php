<?php
session_start();
require_once "../../../../config.php";
require_once "../../../publicFunc.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo("Mexico - Gateway to the US | Pictures"); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../../../style.css">
</head>
<style>
    td {
        border: solid #030303;
        border-radius: 4px;
    }
</style>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<div class="main">
    <h1>Pictures</h1><br>
    <?php
    $imgs = array(
        "fig1_mexican_pop_.png" => "Visualization of Immigrants<br><a href='https://www.migrationpolicy.org/sites/default/files/source_images/fig1_mexican_pop_.png' style='font-size: 0.9rem;' target='_blank'>-> Source <-</a>",
        "routes.png" => "Popular routes of immigrants<br><a href='https://i.insider.com/5a3795617101ad23b854de47?width=700&format=jpeg&auto=webp' style='font-size: 0.9rem;' target='_blank'>-> Source <-</a>");
    foreach ($imgs as $img => $des) {
        echo "<div class='sub' style='width: min-content'><p style='font-size: 1.2rem'>".$des."</p><br><img src='".$img."' alt='Picture'></div>";
    }
    ?>
</div>
