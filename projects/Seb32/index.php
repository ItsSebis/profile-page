<html lang="de">
<head>
    <title>Projects | Base64</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
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
    h1 {
        font-size: 3rem;
        margin: 20px;
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
    textarea {
        background: none;
        padding: 5px;
        border: 4px solid #424242;
        border-radius: 10px;
        font-size: 1.3rem;
        margin: 15px;
        min-height: 200px;
        max-height: 700px;
        max-width: 60%;
        min-width: 60%;
    }
    button {
        background: none;
        width: 200px;
        border-radius: 24px;
        height: 40px;
        color: rgb(199, 199, 199);
        border: solid #333333;
        transition: 0.25s;
    }
    button:hover {
        width: 280px;
        border: solid rgb(0, 255, 221);
        cursor: crosshair;
    }
</style>
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

