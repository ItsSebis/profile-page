<html lang="de">
<head>
    <title>Projects | Seb32</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<h1>Seb32 Encoder/Decoder</h1>
<div class="main">
    <br>
<?php
#echo "Loading functions.php";
require_once "./functions.php";
#echo "Loaded functions.php";
$normal = "Encode/Decode something!";
#echo "Loaded default \$normal";
$encoded = encode($normal);
#echo "Loaded default \$encoded";
if (isset($_POST["encode"])) {
    $normal = $_POST["encode"];
    $encoded = encode($normal);
} elseif (isset($_POST["decode"])) {
    $encoded = $_POST["decode"];
    $normal = decode($encoded);
}
echo '
    <h4>Readable:</h4>
    <form action="./" method="post">
        <textarea rows="12" cols="60" name="encode">'.$normal.'</textarea><br>
        <button type="submit">Encode</button>
    </form>
    <br><br>
    <h4>Seb32:</h4>
    <form action="./" method="post">
        <textarea rows="12" cols="60" name="decode">'.$encoded.'</textarea><br>
        <button type="submit">Decode</button>
    </form>
    ';

