<?php
require_once '../../phpqrcode/qrlib.php';

$path = "imgs/";
$file = $path.uniqid().".png";

$content = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
QRcode::png($content, $file);

echo "<img src='".$file."'>";
