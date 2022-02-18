<head>
    <title>Project | QRCode</title>
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
        width: fit-content;
        background-color: #262626;
        float: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: 10px auto;
        padding: 30px;
        border: 10px solid #262626;
        border-radius: 20px;
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
    input, select {
        background: none;
        border: solid #333333;
        border-radius: 24px;
        width: 350px;
        color: white;
        margin: 20px auto;
        height: 70px;
        padding: 14px 10px;
        outline: none;
        transition: 0.2s;
        font-size: larger;
    }
    select:hover {
        background-color: #363636;
    }
    option {
        color: #000000;
    }
    input:hover {
        background-color: #FFFFF0;
        color: #000000;
        border: solid royalblue;
    }
    input:focus {
        border: solid aqua;
    }
</style>

<?php

if (isset($_POST["content"])) {
    require_once 'phpqrcode/qrlib.php';
    $content = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";

    QRcode::png($content);
} else {
    echo '
        <div class="main">
            <form action="./" method="post">
                <input type="text" name="content" placeholder="Link/Text..."><br>
                <button type="submit">Generate</button>
            </form>
        </div>
    ';
}
