<?php
require_once "../../config.php";
require_once "../publicFunc.php";
?>
<html lang="de">
<head>
    <title>Projects | <?php echo(projectData(basename(__DIR__))["name"]); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="../../img/title-bar.png">
    <link rel="stylesheet" href="../style.css">
</head>
<style>
    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        border: 5px solid #424242;
        border-radius: 15px;
        background: #424242;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        border: 5px solid #262626;
        border-radius: 15px;
        background: #262626;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        border-color: #555;
        background: #555;
    }
    input {
        width: 100px;
        max-width: 100px;
    }
    select {
        width: 100px;
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
    $eq = $_POST["eq"];
    $x = $_POST["x"];
    $y = $_POST["y"];
    if (!empty($eq) && !empty($x) && !empty($y)) {
        $calced = array();
        $Y=1;

        if ($_POST["op"] == "plus") {
            echo "Set X: ".$_POST['X']."<br>";
            echo "Set Y: ".$_POST['Y']."<br>";

            if (!empty($_POST["X"]) || $_POST["X"] == 0) {
                echo "Löse auf nach X<br>";
                $Y = ($eq - $_POST["X"] * $x) / $y;
                if (!empty($_POST["Y"]) || $_POST["Y"] == 0) {
                    if ($Y == $_POST["Y"]) {
                        $calced[$_POST["X"]] = $Y;
                    }
                } else {
                    $calced[$_POST["X"]] = $Y;
                }
            } elseif (!empty($_POST["Y"]) || $_POST["Y"] == 0) {
                echo "Löse auf nach Y<br>";
                $X = ($eq - $_POST["Y"] * $y) / $x;
                $calced[$X] = $_POST["Y"];
            } else {
                $X = 0;
                while ($X <= $eq && $Y > 0) {
                    $Y = ($eq - $X * $x) / $y;
                    if (is_int($Y)) {
                        $calced[$X] = $Y;
                    }
                    $X++;
                }
            }
        } elseif ($_POST["op"] == "minus") {
            if (!empty($_POST["X"]) || $_POST["X"] == 0) {
                $Y = ($_POST["X"] * $x - $eq) / $y;
                if (!empty($_POST["Y"]) || $_POST["Y"] == 0) {
                    if ($Y == $_POST["Y"]) {
                        $calced[$_POST["X"]] = $Y;
                    }
                } else {
                    $calced[$_POST["X"]] = $Y;
                }
            } elseif (!empty($_POST["Y"]) || $_POST["Y"] == 0) {
                $X = ($eq + $y * $_POST["Y"]) / $x;
                $calced[$X] = $_POST["Y"];
            } else {
                $X=$eq;
                while ($X > 0 && $Y > 0) {
                    $Y = ($X * $x - $eq) / $y;
                    if (is_int($Y)) {
                        $calced[$X] = $Y;
                    }
                    $X--;
                }
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
        <input type="number" name="eq" placeholder="Dies" step="0.01" <?php if (isset($_POST["eq"])) {echo("value=\"".$_POST['eq']."\"");} ?>><span> =</span>
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
        <input type="number" name="X" placeholder="X" step="0.01" <?php if (isset($_POST["X"])) {echo("value=\"".$_POST['X']."\"");} ?>>
        <input type="number" name="Y" placeholder="Y" step="0.01" <?php if (isset($_POST["Y"])) {echo("value=\"".$_POST['Y']."\"");} ?>><br>
        <button type="submit" name="calc" style="background-color: #262626">Lösen</button>
        <?php if (isset($error)) {echo($error);} ?>
    </form>
    <?php if (isset($diff)) {echo("<p style='margin-top: 15px'><u>Pro Zeile</u><br>".$diff."</p>");} ?>
    <?php if (isset($calc) && isset($calced)) {echo("<p style='margin-top: 15px'><u>Lösungen</u><br>".count($calced)."</p>"); echo($calc);} ?>
</div>
</body>
</html>