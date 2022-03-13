<?php
session_start();
require_once "../config.php";
require_once "publicFunc.php";
$name = "Nicht angemeldet!";
if (isset($_SESSION["id"]) && accountData($_SESSION["id"]) !== false) {
    $name = accountData($_SESSION["id"])["username"];
}

function allProjects() {
    $con = con();
    $sql = "SELECT * FROM projects ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=allProjects");
        exit();
    }

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

?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <title><?php echo("Projekte | ".$name); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/title-bar.png">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .project {
        color: #00cccc;
    }
    .project:hover {
        color: #007777;
    }
    .project:active {
        color: #00ff9d;
    }
    td {
        border: solid #030303;
        border-radius: 4px;
    }
</style>
<body>
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<div class="main">
    <h1>Projekte</h1><br>
    <?php
    function listFolderFiles($dir){
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        foreach($ffs as $ff){
            if (strpos($ff, ".") !== 0 && is_dir($ff)) {

                echo '<a href="'.$dir.'/'.$ff.'">'.$ff.'</a><br>';

                // List also sub-subs
                //if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            }
        }
    }

    #echo '<a href="..">..</a><br><br>';
    #listFolderFiles('.');

    echo "
<table>
<thead>
<tr><th>Name</th><th>Erschaffer</th><th>Beschreibung</th></tr>
</thead>
";
    foreach (allProjects() as $project) {
        echo '<tr><td><a class="project" href="'.$project["dir"].'">'.$project["name"].'</a></td><td>'.$project["user"].'</td><td>'.$project["des"].'</td></tr>';
    }
    echo "</tbody></table>";
    ?>
</div>
<?php
include_once "../footer.html";