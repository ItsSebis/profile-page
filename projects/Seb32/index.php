<html lang="de">
<head>
    <title>Projects | <?php echo(basename(__DIR__)); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<h1>Seb32 Encoder/Decoder</h1>
<div class="main" style="position: absolute; top: 100px; left: 50%; transform: translate(-50%, -2%)">
    <br>
<?php
#echo "Loading functions.php";
require_once "dbh.conf.php";
require_once "./functions.php";
generateNew(2);
#echo getStats("test");
#setStat("hey", getStats("test")+1);
#echo getStats("test");
#echo "Loaded functions.php";
$normal = "Encode/Decode something!";
#echo "Loaded default \$normal";
$encoded = encode($normal);
#echo "Loaded default \$encoded";
if (isset($_POST["encode"])) {
    $normal = $_POST["encode"];
    $encoded = encode($normal);
    setStat("seb32encoded", getStats("seb32encoded")["value"]+1);
} elseif (isset($_POST["decode"])) {
    $encoded = $_POST["decode"];
    $normal = decode($encoded);
    setStat("seb32decoded", getStats("seb32decoded")["value"]+1);
}
echo '
    <h4>Readable:</h4>
    <form action="./" method="post">
        <textarea rows="12" cols="60" name="encode" maxlength="1500"placeholder="Encode...">'.$normal.'</textarea><br>
        <button type="submit">Encode</button>
    </form>
    <br><br>
    <h4>Seb32:</h4>
    <form action="./" method="post">
        <textarea rows="12" cols="60" name="decode" maxlength="2000" placeholder="Decode...">'.$encoded.'</textarea><br>
        <button type="submit">Decode</button>
    </form>
    </div>
    <div class="stats" style="
        width: 300px;
        background-color: #262626;
        float: left;
        position: fixed;
        top: 100px;
        left: 20px;
        height: fit-content;
        padding: 5px;
        border: 10px solid #262626;
        border-radius: 20px;
        ">
        <h2>Statistics</h2><br>
        <p>Encoded: '.getEncodedCount().'</p>
        <p>Decoded: '.getDecodedCount().'</p>
    </div>
    ';

