<?php
require_once "./functions.php";
?>
<html lang="de">
<head>
    <title>Projekte | <?php echo(projectData(basename(__DIR__))["name"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<h1>Seb32 Encoder/Decoder</h1>
<div class="main" style="position: absolute; top: 100px; left: 50%; transform: translate(-50%, -2%)">
    <br>
    <h4>Readable:</h4>
    <textarea rows="12" id="read" cols="60" name="encode" maxlength="1500" placeholder="Encode...">Encrypt something!</textarea><br>
    <button type="submit" id="encBtn">Encode</button>
    <br><br>
    <h4>Seb32:</h4>
    <textarea rows="12" id="crypt" cols="60" name="decode" maxlength="2000" placeholder="Decode..."></textarea><br>
    <button type="submit" id="decBtn">Decode</button>
</div>
<script>
    function httpGet(theUrl) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", theUrl, false); // false for synchronous request
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }

    let encBtn = document.getElementById("encBtn");
    let decBtn = document.getElementById("decBtn");

    let encF = document.getElementById("read");
    let decF = document.getElementById("crypt");

    encF.oninput = function () {
        let str = encF.value;
        let uri = "api.php?w=e&str="+str
        decF.innerText = "";
        decF.innerText = httpGet(uri);
    }
</script>
</body>