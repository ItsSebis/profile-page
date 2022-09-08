<?php

session_start();
require_once "config.php";
require_once "projects/publicFunc.php";

if (isset($_POST["submit"])) {
    $newFileName = $_POST["filename"];
    if (empty($newFileName)) {
        $newFileName = "photo";
    } else {
        $newFileName = strtolower(str_replace(" ", "-", $newFileName));
    }
    $imageTitle = $_POST["filetitle"];
    $imageDesc = $_POST["filedesc"];

    $file = $_FILES["file"];

    $fileName = $file["name"];
    $fileType = $file["type"];
    $fileTmp = $file["tmp_name"];
    $fileError = $file["error"];
    $fileSize = $file["size"];

    $fileExt = explode(".", $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array("jpg", "jpeg", "png");

    if (!in_array($fileActualExt, $allowed)) {
        echo "You need to upload a proper file type!";
        exit();
    }

    if ($fileError !== 0) {
        echo "Error 1";
        exit();
    }

    if ($fileSize > 200000) {
        echo "Your file is too long!";
        exit();
    }

    $imageFullName = $newFileName . "-" . uniqid("p", true) . "." . $fileActualExt;
    $fileDest = "img/gallery/".$imageFullName;

    if (empty($imageTitle) || empty($imageDesc)) {
        echo "You forgot to fill in some fields! Please go back :D";
        exit();
    }

    $qry = "SELECT * FROM gallery;";
    $st = mysqli_stmt_init(con());
    if (!mysqli_stmt_prepare($st, $qry)) {
        echo "Sql failed!";
        exit();
    }
    mysqli_stmt_execute($st);
    $rs = mysqli_stmt_get_result($st);
    $rowCount = mysqli_num_rows($rs);
    $setImageOrder = $rowCount+1;

    $qry = "INSERT INTO gallery (title, `desc`, imgName, `order`) VALUES (?, ?, ?, ?);";
    if (!mysqli_stmt_prepare($st, $qry)) {
        echo "Sql failed!";
        exit();
    }
    mysqli_stmt_bind_param($st, "ssss", $imageTitle, $imageDesc, $imageFullName, $setImageOrder);
    mysqli_stmt_execute($st);

    move_uploaded_file($fileTmp, $fileDest);
    header("location: ../?upload=success");

}