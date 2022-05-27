<?php
require_once "../../config.php";
require_once "../publicFunc.php";
session_start();
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
    input {
        width: 100px;
        max-width: 100px;
        background-color: #2c2c2c;
    }
    select {
        width: 100px;
        background-color: #2c2c2c;
    }
    th {
        font-size: 1.4rem;
    }
    td {
        border: solid #262626;
        font-size: 1.2rem;
    }
</style>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>

<?php

if (isset($_POST["calc"])) {
    $eq = (float) $_POST["eq"];
    $x = (float) $_POST["x"];
    $y = (float) $_POST["y"];
    if (empty($_POST["X"]) && $_POST["X"] !== "0") {
        $_POST["X"] = "ich bin leer";
    } else {
        $sX = (float)$_POST["X"];
    }
    if (empty($_POST["Y"]) && $_POST["Y"] !== "0") {
        $_POST["Y"] = "ich bin leer";
    } else {
        $sY = (float)$_POST["Y"];
    }
    if ((!empty($eq) || $eq === 0.0) && !empty($x) && !empty($y)) {
        $calced = array();
        $Y=1;

        if (isset($_SESSION["id"]) && userHasPerm($_SESSION["id"], "debugs") && accountData($_SESSION["id"])["sdebug"]) {
            echo "<br>Posts: " . $_POST['X'] . " | " . $_POST['Y'];
            echo "<br>X isset: " . (isset($sX));
            echo "<br>Y isset: " . (isset($sY));
            echo "<br>X empty: " . (empty($_POST['X']));
            echo "<br>Y empty: " . (empty($_POST['Y']));
        }

        if ($_POST["op"] == "plus") {
            $nr = "
                <div class='stats' style='left: auto; right: 20px; float: unset'>
                <h2>nach Y auflösen</h2>
                <br>
                <p>
                " . $eq . " = " . $x . "x + " . $y . "y | - " . $x . "x <br>
                " . $eq . " - " . $x . "x = " . $y . "y | : " . $y . " <br>
                y = " . $eq / $y . " - " . $x . "x/" . $y . " <br>
                </p><br>
                <h2>nach X auflösen</h2>
                <br>
                <p>
                " . $eq . " = " . $x . "x + " . $y . "y | - " . $y . "y <br>
                " . $eq . " - " . $y . "y = " . $x . "x | : " . $x . " <br>
                x = " . $eq / $x . " - " . $y . "y/" . $x . " <br>
                </p>
                </div>
            ";
            if ($_POST["X"] == "ich bin leer" && $_POST["Y"] == "ich bin leer") {
                $X = 0;
                while ($X <= $eq && $Y > 0) {
                    $Y = ($eq - $X * $x) / $y;
                    if ((is_int($Y) || is_float($Y)) && strlen(substr(strrchr($Y, "."), 1)) == 0) {
                        $calced[$X . ""] = $Y;
                    }
                    $X++;
                }
            } elseif (isset($sX)) {
                $Y = ($eq - $sX * $x) / $y;
                if (isset($sY)) {
                    if ($Y == $sY) {
                        $calced[$sX.""] = $Y;
                    }
                } else {
                    $calced[$sX.""] = $Y;

                    $nr = "
                        <div class='stats' style='left: auto; right: 20px; float: right'>
                        <h2>Nebenrechnungen</h2>
                        <br>
                        <p>
                        ".$eq." = ".$x*$sX." + ".$y."y | - ".$x*$sX." <br>
                        ".$eq." - ".$x*$sX." = ".$y."y | : ".$y." <br>
                        y = ".$eq/$y." - ".$x*$sX/$y." <br>
                        y = ".($eq/$y - $x*$sX/$y)." <br>
                        </p>
                        </div>
                    ";

                }
            } elseif (isset($sY)) {
                $X = ($eq - $sY * $y) / $x;
                $calced[$X.""] = $sY;

                $nr = "
                    <div class='stats' style='left: auto; right: 20px; float: right'>
                    <h2>Nebenrechnungen</h2>
                    <br>
                    <p>
                    ".$eq." = ".$x."x + ".$y*$sY." | - ".$y*$sY." <br>
                    ".$eq." - ".$y*$sY." = ".$x."x | : ".$x." <br>
                    x = ".$eq/$x." - ".$y*$sY/$x." <br>
                    x = ".($eq/$x - $y*$sY/$x)." <br>
                    </p>
                    </div>
                ";
            }
        } elseif ($_POST["op"] == "minus") {
            $nr = "
                <div class='stats' style='left: auto; right: 20px; float: right'>
                <h2>Auflösen</h2>
                <br>
                <h3>Y</h3>
                <p>
                ".$eq." = ".$x."x - ".$y."y | + ".$y."y <br>
                ".$eq." + ".$y."y = ".$x."x | - ".$eq." <br>
                ".$y."y = ".$x."x - ".$eq." | : ".$y." <br>
                y = ".$x."x/".$y." - ".$eq/$y." <br>
                </p>
                <br>
                <h3>X</h3>
                <p>
                ".$eq." = ".$x."x - ".$y."y | + ".$y."y <br>
                ".$eq." + ".$y."y = ".$x."x | : ".$x." <br>
                x = ".$eq/$x." + ".$y."y/".$x." <br>
                </p>
                </div>
            ";
            if ($_POST["X"] == "ich bin leer" && $_POST["Y"] == "ich bin leer") {
                $X = $eq;
                while ($X > 0 && $Y > 0) {
                    $Y = ($X * $x - $eq) / $y;
                    if ((is_int($Y) || is_float($Y)) && strlen(substr(strrchr($Y, "."), 1)) == 0) {
                        $calced[$X . ""] = $Y;
                    }
                    $X--;
                }
            } elseif (isset($sX)) {
                $Y = ($sX * $x - $eq) / $y;
                if (isset($sY)) {
                    if ($Y == $sY) {
                        $calced[$sX.""] = $Y;
                    }
                } else {
                    $calced[$sX.""] = $Y;

                    $nr = "
                        <div class='stats' style='left: auto; right: 20px; float: right'>
                        <h2>Nebenrechnungen</h2>
                        <br>
                        <p>
                        " . $eq . " = " . $x * $sX . " - " . $y . "y | + " . $y . "y <br>
                        " . $eq . " + " . $y . "y = " . $x * $sX . " | - " . $eq . " <br>
                        " . $y . "y = " . $x * $sX . " - " . $eq . " | : " . $y . " <br>
                        y = " . $x * $sX / $y . " - " . $eq / $y . " <br>
                        y = " . ($x * $sX / $y - $eq / $y) . " <br>
                        </p>
                        </div>
                    ";
                }
            } elseif (isset($sY)) {
                $X = ($eq + $y * $sY) / $x;
                $calced[$X.""] = $sY;

                $nr = "
                    <div class='stats' style='left: auto; right: 20px; float: right'>
                    <h2>Nebenrechnungen</h2>
                    <br>
                    <p>
                    ".$eq." = ".$x."x - ".$y."y | + ".$y*$sY." <br>
                    ".$eq." + ".$y*$sY." = ".$x."x | : ".$x." <br>
                    x = ".$eq/$x." + ".$y*$sY/$x." <br>
                    x = ".($eq/$x + $y*$sY/$x)." <br>
                    </p>
                    </div>
                ";
            }
        } else {
            header("location: ./");
            exit();
        }

        if (count($calced) > 1) {
            $Xs = array();
            $Ys = array();
            foreach ($calced as $X => $Y) {
                $Xs[] = $X;
                $Ys[] = $Y;
            }
            $diffX = $Xs[1]-$Xs[0];
            $diffY = $Ys[1]-$Ys[0];
            if ($diffX > 0) {
                $diffX = "+".$diffX;
            }
            if ($diffY > 0) {
                $diffY = "+".$diffY;
            }
            $diff = $diffX."x | ".$diffY."y";
        }

        $body = "";
        foreach ($calced as $key => $value) {
            $body .= "<tr><td>".$key."</td><td>".$value."</td></tr>";
        }
        $calc = "
<table>
<thead>
<tr>
<th>X</th>
<th>Y</th>
</tr>
</thead>
<tbody>
".$body."
</tbody>
</table>
";
    } else {
        $error = "<p style='color: red; margin-top: 30px'>Fülle bitte alle Felder!</p>";
    }
}

?>

<body style="overflow-y: hidden">
<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-height: 75%; max-width: 75%;
overflow: hidden; overflow-y: initial; width: 60%; background-color: #333333; border: 9px solid #333333; border-radius: 20px">
    <form action="./" method="post">
        <input type="number" name="eq" placeholder="Dies" <?php if (isset($_POST["eq"])) {echo("value=\"".$_POST['eq']."\"");} ?>><span> =</span>
        <input type="number" name="x" placeholder="X" step="0.01" <?php if (isset($_POST["x"])) {echo("value=\"".$_POST['x']."\"");} ?>><span>x </span>
        <select name="op">
            <?php
            if (isset($_POST["op"]) && $_POST["op"] == "minus") {
                echo '<option value="minus">-</option>
                      <option value="plus">+</option>
                ';
            } else {
                echo '<option value="plus">+</option>
                      <option value="minus">-</option>
                ';
            }
            ?>
        </select>
        <input type="number" name="y" placeholder="Y" step="0.01" <?php if (isset($_POST["y"])) {echo("value=\"".$_POST['y']."\"");} ?>><span>y</span><br>
        <h3 style="margin-bottom: -20px;">Auflösen nach...</h3>
        <input type="number" name="X" placeholder="X" step="0.01" <?php if (isset($_POST["X"])) {echo("value=\"".$_POST['X']."\"");}?>>
        <input type="number" name="Y" placeholder="Y" step="0.01" <?php if (isset($_POST["Y"])) {echo("value=\"".$_POST['Y']."\"");}?>><br>
        <button type="submit" name="calc" style="background-color: #262626">Lösen</button>
        <?php if (isset($error)) {echo($error);} ?>
    </form>
    <?php if (isset($diff)) {echo("<p style='margin-top: 15px'><u>Pro Zeile</u><br>".$diff."</p>");} ?>
    <?php if (isset($calc) && isset($calced)) {echo("<p style='margin-top: 15px'><u>Lösungen</u><br>".count($calced)."</p>"); echo($calc);} ?>
</div>
<?php if (isset($nr)) {echo($nr);} ?>
</body>
</html>