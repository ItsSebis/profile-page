<?php

function con() {
    $con = mysqli_connect($serverName, $dbUsr, $dbPw, $dbName);
    if (!$con) {
        header("location: noconnection.htm");
        exit();
    }
    return $con;
}
