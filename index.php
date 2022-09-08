<?php
$GLOBALS["site"] = "Home";
include_once "header.php";
?>

<main>
    <section class="gallery-links">
        <div class="wrapper">
            <h1>Gallery</h1>
            <div class="gallery-container">
                
                <?php

                $con = con();
                $qry = "SELECT * FROM `gallery` ORDER BY `order` DESC;";
                $st = mysqli_stmt_init($con);
                if (!mysqli_stmt_prepare($st, $qry)) {
                    echo "Sql failed!";
                    exit();
                }
                $st->execute();
                $rs = mysqli_stmt_get_result($st);

                #<img src="img/gallery/'.$row["imgName"].'" alt="'.$row["imgName"].'">

                while ($row = mysqli_fetch_assoc($rs)) {
                    echo '
                    <a href="img/gallery/'.$row["imgName"].'">
                        <div style=\'background-image: url("img/gallery/'.$row["imgName"].'")\'></div>
                        <h3>'.$row["title"].'</h3>
                        <p>'.$row["desc"].'</p>
                    </a>
                    ';
                }
                ?>
            </div>

            <?php
            if (isset($_SESSION["id"]) && userHasPerm($_SESSION["id"], "photoAdmin")) {
                echo '
                    <div class="gallery-upload">
                        <h1>Upload</h1>
                        <form action="posts/gallery-upload.post.php" method="post" enctype="multipart/form-data">
                            <input type="text" name="filename" placeholder="File name..."><br>
                            <input type="text" name="filetitle" placeholder="Image title..."><br>
                            <input type="text" name="filedesc" placeholder="Image Description..."><br>
                            <input type="file" name="file"><br>
                            <button type="submit" name="submit">Push it</button>
                        </form>
                    </div>
                ';
            }
            ?>

        </div>
    </section>
</main>

    <!--
<h1 style="margin-top: 60px; font-size: 3rem">Home</h1>
<div class="main" style="text-align: center">
    <p>Moin,<br>
        diese Sachen kannst du dir mal ansehen:<br><br>
        <a style="font-size: 1.2rem" target="_self" href="./projects">-> Maine Projekte <-</a><br>
        <a style="font-size: 1.2rem" target="_self" href="./users.php">-> Alle Benutzer <-</a><br>
        <a style="font-size: 1.2rem" target="_blank" href="https://github.com/itssebis">-> Main GitHub <-</a><br>
        <a style="font-size: 1.2rem" target="_blank" href="./nas">-> Main NAS <-</a><br>
    </p>
    <br>
    <h2>Ich kann (vielleicht):</h2>
    <li>Websites erstellen (PHP, HTML, CSS)</li>
    <li>Discord Server verwalten (User/Role management)</li>
    <li>Discord Bots programmieren (JDA)</li>
    <li>Minecraft plugins programmieren (Spigot/Paper)</li>
    <p style="font-size: 1.3rem">;^)</p>
</div>
    -->
<?php

// Errors
if (isset($_GET["error"])) {
    if ($_GET["error"] == "0") {
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Erfolgreich angemeldet!";
    } elseif ($_GET["error"] == "notSignedIn") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
Du musst dich anmelden, um diese Seite zu betreten!</p>";
    } elseif ($_GET["error"] == "1") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>
MySQL Fehler! Versuche es später erneut!</p>";
    }
}