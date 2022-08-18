<?php
session_start();
require_once "../../config.php";
require_once "../publicFunc.php";
require_once "functions.php";
$last = "";
if (isset($_SESSION["vocabs"])) {
    $last = $_SESSION["vocabs"]["last"] ?? null;
    $collection = $_SESSION["vocabs"]["col"] ?? 0;
} else {
    $_SESSION["vocabs"] = array();
    $collection = 0;
}

if (isset($_GET["col"])) {
    if (colData($_GET["col"]) !== false && count(vocabArray($_GET["col"])) > 0) {
        $collection = $_GET["col"];
        $_SESSION["vocabs"]["col"] = $collection;
    } elseif ($_GET["col"] == 0) {
        unset($_SESSION["vocabs"]["col"]);
        $collection = 0;
    }
}

if (isset($_POST["finish"])) {
    if ($last != $_POST["vocab"] && isset($_SESSION["id"]) && accountData($_SESSION["id"])) {
        vocabFinish($_POST["vocab"], mirrorBoolInInts($_POST["finish"]), $_SESSION["id"]);
    }
    $_SESSION["vocabs"]["last"] = $_POST["vocab"];
}

if ($collection !== 0) {
    $currentVocab = rngArray(vocabArray($collection));
    $correct = 0;
    $incorrect = 0;
    if (isset($_SESSION["id"]) && vusrData($currentVocab["id"], $_SESSION["id"]) !== false && vusrData($currentVocab["id"], $_SESSION["id"])["done"] > 0) {
        $correct = round(vusrData($currentVocab["id"], $_SESSION["id"])["correct"] / vusrData($currentVocab["id"], $_SESSION["id"])["done"] * 100, 2);
        $incorrect = (100 - round(vusrData($currentVocab["id"], $_SESSION["id"])["correct"] / vusrData($currentVocab["id"], $_SESSION["id"])["done"] * 100, 2));
    }
}
?>
<html lang="de">
<head>
    <title>Projekte | <?php echo(projectData(basename(__DIR__))["name"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<style>
    .sol {
        visibility: visible;
    }

    .sol.invisible {
        visibility: hidden;
    }

    .col {
        display: flex;
        cursor: pointer;
        background-color: #333333;
        padding: 10px;
        margin: 5px;
        align-items: center;
        justify-content: center;
        height: 140px;
        width: 100px;
        border: 4px solid #333;
        border-radius: 20px;
    }
</style>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); height: max-content; max-height: 75%; max-width: 75%;
overflow: hidden; overflow-y: initial; width: 60%; background-color: #262626; border: 9px solid #262626; border-radius: 20px">
    <?php
    if ($collection != 0 && colData($collection) !== false) {
        if (isset($currentVocab) && $collection !== null && isset($correct) && isset($incorrect)) {
            echo '

        <a style="position: absolute; top: 10px; left: 10px;" href="./?col=0">← Menu</a>
    <h1>' . $currentVocab["shown"] . '</h1><br>
    <button id="sol-btn">Lösung</button>

    <div class="sol invisible">
        <h3 style="margin-top: 20px">' . $currentVocab["hidden"] . '</h3><br>
        <form action="./" method="post">
            <input type="hidden" name="vocab" value="' . $currentVocab["id"] . '">
            <button type="submit" name="finish" value="1">Richtig <span style="color: lime">' . $correct . '%</span></button>
            <button type="submit" name="finish" value="0">Falsch <span style="color: red">' . $incorrect . '%</span></button>
        </form>
    </div>
    <script>
        let btn = document.querySelector("#sol-btn");
        let sol = document.querySelector(".sol");

        btn.onclick = function () {
            sol.classList.toggle("invisible")
            btn.setAttribute("disabled", "")
        }
    </script>
';
        }
    } else {?>

        <div style="display: flex; width: 100%; height: 100%; min-height: 400px; flex-wrap: wrap; align-items: center; justify-content: center">
            <?php
            foreach (colArray() as $col) {
                if (count(vocabArray($col["id"])) > 0) {
                    echo '
                    <div class="col" onclick="window.location.href = \'./?col='.$col["id"].'\'">
                        <h2>'.$col["name"].'</h2>
                    </div>
                    ';
                }
            }
            ?>
        </div>

    <?php
    }
    ?>
</div>
</body>
</html>

