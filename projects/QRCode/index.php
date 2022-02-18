<?php
require_once 'phpqrcode/qrlib.php';

$path = "imgs/";
$file = $path."latest.png";

$content = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";

$QRCode = new QRcode();

QRcode::png($content, $file);

echo "<img src='".$file."' alt='QR'>";
