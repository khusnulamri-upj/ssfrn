<?php
/* Konfigurasi untuk SisfoKampus */

define("VERSION", "3.5");

// Absolute Path

if (!defined("DS")) {
    define("DS", DIRECTORY_SEPARATOR);
}

if (!defined("HOME_FOLDER")) {
    $home = $_SERVER["SCRIPT_FILENAME"];
    $path_home = str_replace("index.php", '', $home);
    define("HOME_FOLDER", trim($path_home));
    set_include_path(get_include_path() . PATH_SEPARATOR . HOME_FOLDER);
}

if (!defined("MODULES_FOLDER")) {
    define("MODULES_FOLDER", HOME_FOLDER . DS . "modules");
}

if (!defined("IMAGES")) {
    define ("IMAGES", HOME_FOLDER . DS . "themes" . DS . $themes . DS . "icon");
}

// Include sugeng library
include_once(HOME_FOLDER . DS . "library" . DS . "sg.lib.php");

// Cek apakah sisfoKampus sudah diinstall ???
if (!checkInstallation()) {
    header("Location: install.php");
    exit;
}

// Function Include Folder yang dibutuhkan
$folder = array("config", "script", "include", "library");
includeFolder($folder);

// Include Library
include_once("dwo.lib.php");

// Include Modules Path
includeModules(MODULES_FOLDER);
?>