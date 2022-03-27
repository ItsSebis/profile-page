<?php
include_once "../config.php";
include_once "../projects/publicFunc.php";
session_start();

if (!userHasPerm($_SESSION["id"], "editroles")) {
    header("location: ../admin.php?error=noPerm");
    exit();
}

if (isset($_POST["perm"])) {
    $role = $_POST["role"];
    $perm = $_POST["perm"];
    if ($role != 1) {
        mirrorRolePerm($role, $perm);
    }
    header("location: ../admin.php?page=roles&role=".$role);
}

elseif (isset($_POST["edit"])) {
    $role = $_POST["role"];
    $nname = $_POST["newacc"];
    $color = $_POST["color"];
    if (!empty($nname)) {
        setRoleName($role, $nname);
    }
    if (!empty($color)) {
        setRoleColor($role, $color);
    }
    header("location: ../admin.php?page=roles&error=edited&role=".$role);
}

elseif (isset($_POST["create"])) {

}

elseif (isset($_POST["del"])) {

}

else {
    header("location: ../admin.php?page=roles&error=notFromSubmit");
}
exit();