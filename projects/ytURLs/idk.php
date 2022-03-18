<?php
require_once "../publicFunc.php";
$str = "AA";
while ($str !== "00") {
    $str = nextLetterCom($str);
    echo($str."<br>");
}