
<head>
    <meta charset="utf-8">
    <title>Custom En-/Decoding</title>
</head>

<?php
require_once "functions.php";

if (isset($_GET["encode"]) && isset($_GET["pattern"]) && isset($_GET["text"]) && isset($_GET["bank"])) {
    try {
        #echo (urldecode($_GET["pattern"]) ." | ". urldecode($_GET["text"]) ." | ".  urldecode($_GET["bank"])."<br>");
        echo (encodeWithPattern(urldecode($_GET["pattern"]), urldecode($_GET["text"]), urldecode($_GET["bank"])));
    } catch (Exception $e) {
        echo "Sorry there was an error!";
    }
}

elseif (isset($_GET["decode"]) && isset($_GET["pattern"]) && isset($_GET["text"]) && isset($_GET["bank"])) {
    try {
        #echo (urldecode($_GET["pattern"]) ." | ". urldecode($_GET["text"]) ." | ".  urldecode($_GET["bank"]));
        echo (decodeWithPattern(urldecode($_GET["pattern"]), urldecode($_GET["text"]), urldecode($_GET["bank"])));
    } catch (Exception $e) {
        echo "Sorry there was an error!";
    }
}

else {
    echo "Looks like you´ve forgot something!";
}
