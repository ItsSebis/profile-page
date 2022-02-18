<?php
require_once 'phpqrcode/phpqrcode.php';

$path = "imgs/";
$file = $path."latest.png";

$content = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
QRcode::png($content, $file);

echo "<img src='".$file."'>";
