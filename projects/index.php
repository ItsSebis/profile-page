<html lang="de">
<head>
    <title>ItsSebis | Projects</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/title-bar.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main">
    <h1>Projekte</h1><br>
    <?php
    function listFolderFiles($dir){
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        foreach($ffs as $ff){
            if (strpos($ff, ".") !== 0 && is_dir($ff)) {

                echo '<a href="'.$dir.'/'.$ff.'">'.$ff.'</a><br>';

                // List also sub-subs
                //if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            }
        }
    }

    echo '<a href="..">..</a><br><br>';
    listFolderFiles('.');
    ?>
</div>
<?php
include_once "../footer.html";