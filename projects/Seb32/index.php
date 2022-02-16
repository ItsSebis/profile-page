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

function encode($str) {
    $str = base64_encode($str);
    $str = bin2hex($str);
    return base64_encode($str);
}

function decode($str) {
    $str = base64_decode($str);
    $str = hex2bin($str);
    return base64_decode($str);
}

$normal = "";
$encoded = "";

if (isset($_POST["encode"])) {
    $normal = $_POST["encode"];
    $encoded = encode($normal);
} elseif (isset($_POST["decode"])) {
    $encoded = $_POST["decode"];
    $normal = decode($encoded);
} else {
    $normal = "Tippe hier etwas, um es zu encoden!";
    $encoded = encode($normal);
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

