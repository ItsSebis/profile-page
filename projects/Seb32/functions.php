<?php

require_once "../../config.php";
require_once "../publicFunc.php";

/**
 * @throws Exception
 */

function allLetters() {
    return "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890=?!+*~.,()[]{}\/:|&<>";
}

function getEncPass() {
    return bin2hex("YWhVR0slOHlIOG81UmIkODM1NFo3VTJFS3ZAIzNnIyVGcGdLM1hNVTc0eWlYUiYqcjVWQUZnI29pZ2QzS0R6I1ZkZjRvUFdiOGpRNHU1UlZLQW14RGNHS1BFIUViN3FOOVlISk4zalU1ZXUhOUp6a3RGejViSEhIRkQ5Mm50OSM=");
}

function ciphers() {
    return array("aes-256-ctr", "aes-128-ctr"); // "aria-256-gcm", "aes-256-ctr", "aes-256-gcm", "aes-256-cbc", "aes-128-ccm"
}

function encodeCiphers($str) {
    foreach (ciphers() as $cipher) {
        if (openssl_cipher_iv_length($cipher) > 0) {
            $iv = str_repeat("1", openssl_cipher_iv_length($cipher));
        } else {
            $iv = "";
        }
        $str = openssl_encrypt($str, $cipher, base64_decode(hex2bin(getEncPass())), 0, $iv, $tag);
    }
    return $str;
}

function decodeCiphers($str) {
    foreach (array_reverse(ciphers()) as $cipher) {
        if (openssl_cipher_iv_length($cipher) > 0) {
            $iv = str_repeat("1", openssl_cipher_iv_length($cipher));
        } else {
            $iv = "";
        }
        $str = openssl_decrypt($str, $cipher, base64_decode(hex2bin(getEncPass())), 0, $iv);
    }
    return $str;
}

function encode($str, $times) {
    $str = base64_encode($str);
    for ($i=0;$i<$times;$i++) {
        $str = encodeCiphers($str);
    }
    return urlencode($str);
}

function decode($str, $times) {
    for ($i=0;$i<$times;$i++) {
        $str = decodeCiphers($str);
    }
    return urldecode(base64_decode($str));
}
