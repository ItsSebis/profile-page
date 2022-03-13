<html lang="de">
<head>
    <title>Projects | <?php echo(basename(__DIR__)); ?></title>
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
            $X=0;
            while ($Y > 0) {
                $Y = ($eq - $X * $x) / $y;
                if (is_int($Y)) {
                    $calced[$X] = $Y;
                }
                $X++;
            }
        } elseif ($_POST["op"] == "minus") {
            $X=$eq;
            while ($Y > 0) {
                $Y = ($X * $x - $eq) / $y;
                if (is_int($Y)) {
                    $calced[$X] = $Y;
                }
                $X--;
            }
        } else {
            header("location: ./");
            exit();
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
        <input type="number" name="x" placeholder="X" <?php if (isset($_POST["x"])) {echo("value=\"".$_POST['x']."\"");} ?>><span>x </span>
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
        <input type="number" name="y" placeholder="Y" <?php if (isset($_POST["y"])) {echo("value=\"".$_POST['y']."\"");} ?>><span>y</span><br>
        <button type="submit" name="calc">Lösen</button>
        <?php if (isset($error)) {echo($error);} ?>
    </form>
    <?php if (isset($calc) && isset($calced)) {echo("<p style='margin-top: 15px'>".count($calced)." Lösungen</p>"); echo($calc);} ?>
</div>
</body>
</html>