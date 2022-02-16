<html lang="de">
<head>
    <title>SebSurf | Projects</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon-title.png">
</head>
<style>
    * {
        text-align: center;
        color: #FFFFFF;
        margin: 0;
        padding: 0;
    }
    body {
        background-color: #363636;
        font-family: 'Roboto Mono', monospace;
        font-size: 11pt;
    }
    a {
        text-decoration: none;
        color: white;
        text-align: center;
    }
    a:hover {
        color: GRAY;
    }
    a:active {
        color: red;
    }
    .main {
        width: 60%;
        background-color: #262626;
        float: none;
        margin: 10px auto;
        border: 10px solid #262626;
        border-radius: 20px;
    }
    td {
        font-size: 0.95rem;
    }
    table {
        align-items: center;
        float: none;
        margin: 30px auto;
        font-size: larger;
    }
    th {
        padding: 5px 5px 10px;
    }
    td {
        padding-left: 10px;
        padding-right: 10px;
    }
    footer {
        align-items: center;
        position: absolute;
        bottom: 0;
        width: 100%;
        padding-bottom: 20px;
        padding-top: 20px;
        min-height: 100px;
        max-height: 250px;
        background-color: #262626;
    }
</style>
<body>
<div class="main">
    <h1>Projects</h1><br>
    <?php
    function listFolderFiles($dir){
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        foreach($ffs as $ff){
            if (!str_starts_with($ff, ".") && is_dir($ff)) {

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
<footer>
    <h2>ItsSebis</h2><br>
    <p style="color: gray">16.02 - <?php echo(date("d.m")); ?></p>
    <p style="color: gray">2022 - <?php echo(date("Y")); ?></p>
</footer>
</body>
</html>