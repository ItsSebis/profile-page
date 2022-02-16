<?php
include_once "header.html";
require_once "config.php";

$curl = curl_init(getLogHook());
echo(getLogHook());
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("content" => "Hey")));
curl_exec($curl);

?>

<div class="main" style="margin-top: 60px; text-align: center">
    <h1>ItsSebis</h1>
    <p>Moin!<br>
        - Sebi (14), programmiert gerne Sachen.<br><br>
        Check doch mal meine Kreationen ab:<br><br>
        <a target="_blank" href="https://github.com/itssebis">-> Main GitHub <-</a>
    </p><br>
    <h2>Ich kann:</h2>
    <ul style="width: 45%; margin: 0 auto; text-align: left">
        <li>Websites erstellen (PHP, HTML, CSS)</li>
        <li>Discord Server verwalten (User/Role management)</li>
        <li>Discord Bots programmieren (JDA)</li>
        <li>Minecraft plugins programmieren (Spigot/Paper)</li>
    </ul>
</div>