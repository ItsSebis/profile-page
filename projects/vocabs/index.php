<?php
session_start();
require_once "../../config.php";
require_once "../publicFunc.php";
if (!isset($_SESSION["id"])) {
    header("location: ../../?error=notSignedIn");
    exit();
}
$user = accountData($_SESSION["id"]);
$last = "";
if (isset($_SESSION["vocabs"])) {
    $last = $_SESSION["vocabs"]["last"] ?? null;
    $collection = $_SESSION["vocabs"]["col"] ?? 1;
} else {
    $_SESSION["vocabs"] = array();
    $collection = 1;
}

if (isset($_POST["finish"])) {
    if ($last != $_POST["vocab"]) {
        vocabFinish($_POST["vocab"], mirrorBoolInInts($_POST["finish"]));
    }
    $_SESSION["vocabs"]["last"] = $_POST["vocab"];
}

function vocabArray($collection) {
    $con = con();
    $sql = "SELECT * FROM vocabs WHERE `old` = ? AND `col` = ? ORDER BY `done` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=vocabArray");
        exit();
    }

    $null = 0;

    mysqli_stmt_bind_param($stmt, "ii", $null, $collection);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}

function vocabData($id) {
    $con = con();
    $sql = "SELECT * FROM vocabs WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=vocabData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function vocabFinish($vocab, $correct) {
    $con = con();
    $qry = "UPDATE vocabs SET done=?, correct=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserStat");
        exit();
    }

    $correct = vocabData($vocab)["correct"]+mirrorBoolInInts($correct);
    $done = vocabData($vocab)["done"]+1;

    mysqli_stmt_bind_param($stmt, "sss", $done, $correct, $vocab);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if ($collection !== null) {
    $currentVocab = rngArray(vocabArray($collection));
    $correct = 0;
    $incorrect = 0;
    if ($currentVocab["done"] > 0) {
        $correct = round($currentVocab["correct"]/$currentVocab["done"]*100, 2);
        $incorrect = (100-round($currentVocab["correct"]/$currentVocab["done"]*100, 2));
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
</style>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<?php
#echo print_r(vocabArray());
?>
<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); height: max-content; max-height: 75%; max-width: 75%;
overflow: hidden; overflow-y: initial; width: 60%; background-color: #262626; border: 9px solid #262626; border-radius: 20px">
    <?php
    if (isset($currentVocab) && $collection !== null && isset($correct) && isset($incorrect)) {
        echo '    
    <h1>'.$currentVocab["shown"].'; ?></h1><br>
    <button id="sol-btn">Lösung</button>

    <div class="sol invisible">
        <h3 style="margin-top: 20px">'.$currentVocab["hidden"].'</h3><br>
        <form action="./" method="post">
            <input type="hidden" name="vocab" value="'.$currentVocab["id"].'">
            <button type="submit" name="finish" value="1">Richtig <span style="color: lime">'.$correct.'; ?>%</span></button>
            <button type="submit" name="finish" value="0">Falsch <span style="color: red">'.$incorrect.'%</span></button>
        </form>
    </div>';
    }
    ?>
</div>
</body>
</html>

<script>
    let btn = document.querySelector("#sol-btn");
    let sol = document.querySelector(".sol");

    btn.onclick = function () {
        sol.classList.toggle("invisible")
        btn.setAttribute("disabled", "")
    }
</script>