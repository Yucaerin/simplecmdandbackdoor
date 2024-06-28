<?php
// Menjalankan perintah sistem dan menyimpan outputnya
$output = shell_exec('ls -la');

// Menampilkan output perintah
echo $output;
?>
