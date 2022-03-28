<?php
require_once "functions.php";
if (!isset($_SESSION["id"])) {
    header("location: ../../?error=notSignedIn");
    exit();
}
$user = accountData($_SESSION["id"]);
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
<body>
<h1>SayIt!</h1>
<?php
if (!isset($_GET["c"]) || chairData($_GET["c"]) === false) {
?>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>

    <div class="main">

        <?php

        if (myChair($_SESSION["id"]) === false && userHasPerm($_SESSION["id"], "chairs")) {
            echo "
        <h2>Mein Raum</h2>
        <br>
<form action='chairmanager.post.php' method='post'>
    <button name='create'>Erstellen</button>
</form>
            ";
        } elseif (userHasPerm($_SESSION["id"], "chairs")) {
            $chair = myChair($_SESSION["id"]);
            echo "
        <h2>Mein Raum</h2>
        <br>
        <a href='./?c=".$chair['id']."'>".$chair['name']."</a>";
        }

        if (count(chairs()) > 0 && !(count(chairs()) == 1 && chairs()[0] == myChair($_SESSION["id"]))) {
            if (userHasPerm($_SESSION["id"], "chairs")) {
                echo "<br><br>";
            }
            echo "
<h2>Andere Räume</h2>
<table style='width: 75%'>
<thead>
<tr>
<th style='width: 70%'>Name</th>
<th style='width: 30%'>Besitzer</th>
</tr>
</thead>
<tbody>
";

            $count = 1;
            foreach (chairs() as $chair) {
                if ($chair["owner"] == $_SESSION["id"]) {
                    continue;
                }
                if (isEven($count)) {
                    $color = "4a4a4a";
                } else {
                    $color = "3c3c3c";
                }
                echo "
<tr>
<td style='background-color: #".$color."; padding: 3px;'><a href='./?c=".$chair['id']."'>".$chair['name']."</a></td>
<td style='background-color: #".$color."; color: ".roleData(accountData($chair['owner'])['role'])['color']."'>
".accountData($chair['owner'])['username']."
</td>
</tr>
";
                $count++;
            }
            echo "
</tbody>
</table>
";
        }

    echo "
    </div>";

} else {
    $chair = chairData($_GET["c"]);
    ?>
    <a style="position: absolute; top: 10px; left: 10px;" href="./">← Back</a>
    <div class="main">
        <h2 style="font-size: 2rem"><?php echo($chair["name"]); ?></h2><br>
        <form action='chairmanager.post.php' method='post'>
            <button type='submit' name='delroom' value='<?php echo($chair["id"]); ?>' style='border:none; font-size: 1.2rem; color: red;'>Raum löschen</button>
        </form><br>
        <h3>Post!</h3>
        <form action="chairmanager.post.php" method="post">
            <textarea rows="12" cols="60" name="content" minlength="20" maxlength="2000" placeholder="Text..."></textarea><br>
            <button name="say" type="submit" value="<?php echo($chair["id"]); ?>">Say!</button>
        </form><br>
        <h3>Andere Posts</h3>
<?php
        foreach (says($chair["id"]) as $say) {
            echo "
            <div class='sub' style='min-height: 100px; max-height: 500px; overflow: hidden; overflow-y: initial; padding: 15px;'>";
                if ($say["by"] == $_SESSION["id"] || $chair["owner"] == $_SESSION["id"]) {
                    echo "
            <form action='chairmanager.post.php' method='post'>
            <button type='submit' name='delsay' value='".$say["id"]."' style='border:none; font-size: 1.2rem; float: right; margin: 0; width: auto; height: auto; border-radius: 0; padding: 0; position: relative; right: 5px; color: red;'>x</button>
            </form>
                ";
            }
            echo "<p style='color: gray; font-size: 0.8rem; float: top; margin-top: -10px; margin-bottom: 10px;'>".$say['wrote']."</p>";
            echo "
        ".$say['content']."
        </div>";
    }
    echo "
    </div>";
}
?>
</body>
</html>