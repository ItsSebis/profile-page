<?php

if (!isset($_POST["post"])) {
    header("location: ../");
    exit();
}

if (!isset($_SESSION["id"]) || accountData($_SESSION["id"]) === false) {
    header("location: ../login.php?path=../lobbynerdz/");
    exit();
}

$content = $_POST["content"];

$content = str_replace("<script>", "script->", $content);
$content = str_replace("</script>", "<-script", $content);

$pattern = '~[a-z]+://\S+~';

if($num_found = preg_match_all($pattern, $content, $out)) {
    $found = $out[0];
    #echo "FOUND ".$num_found." LINKS:\n";
    #echo "<br>".count($found)."<br>";
    #print_r($out[0]);

    foreach ($found as $link) {
        $content = str_replace($link, "<a href='".$link."'>".$link."</a>", $content);
    }
}

#echo $content;

require_once "../lobbynerdz/functions.inc.php";
session_start();
post($content);

header("location: ../lobbynerdz/");