<?php
/* Sugeng Library For SisfoKampus */

function includeFolder($arr_folder){
    if (is_array($arr_folder)) {
        foreach($arr_folder as $folder) {
            if (is_dir($folder)) {
                set_include_path(get_include_path() . PATH_SEPARATOR . realpath(HOME_FOLDER . DIRECTORY_SEPARATOR . $folder));
            }
        }
    }
}

function includeModules($dir) {
    if(is_dir($dir)) {
        if($dh = opendir($dir)) {
            while(($file = readdir($dh)) !== false) {
                if($file != "." && $file != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                        set_include_path(get_include_path() . PATH_SEPARATOR . realpath($dir . DIRECTORY_SEPARATOR . $file));
                        includeModules($dir . DS . $file);
                    }
                }
            }
        }
        closedir($dh);
    }
}

function modulesExists($module){
    if($dh = opendir(MODULES_FOLDER)) {
        while(($folder = readdir($dh)) !== false) {
            if($folder != "." && $folder != "..") {
                if (is_dir(MODULES_FOLDER . DS . $folder)) {
                    if ($fl = opendir(MODULES_FOLDER . DS . $folder)) {
                        while(($file = readdir($fl)) !== false) {
                            if($file != "." && $file != "..") {
                                if (is_dir(MODULES_FOLDER . DS . $folder . DS . $file)) {
                                    if ($fl2 = opendir(MODULES_FOLDER . DS . $folder . DS . $file)) {
                                        while(($file2 = readdir($fl2)) !== false) {
                                            if ($file2 != "." && $file2 !== "..") {
                                                $arr_mod[] = $file2;
                                            }
                                        }
                                    }
                                    closedir($fl2);
                                } else {
                                    $arr_mod[] = $file;
                                }
                            }
                        }
                    }
                closedir($fl);
                }
            }
        }
        closedir($dh);
    }

    if (in_array($module, $arr_mod)) {
        return true;
    } else {
        return false;
    }
}

function checkInstallation(){
    if (file_exists(HOME_FOLDER . DS . "include" . DS . "lock" . DS . "install.cfg")) {
        return true;
    } else {
        return false;
    }
}

function stripTable($n, $cls1='odd', $cls2='dd') {
    $c = (int) $n;
    $cls = (($c % 2) == 0) ? $cls1 : $cls2;

    return $cls;
}

function gridTable($id, $width, $height){
    $js = "<script type=\"text/javascript\">
	    jQuery(document).ready(function() {
		jQuery('#$id').Scrollable($width, $height);
	    });
	  </script>";
    return $js;
}

function readModDir(){
    if ($dh = opendir(MODULES_FOLDER)) {
        while (($folder = readdir($dh)) !== false) {
            if($folder != "." && $folder != "..") {
                if (is_dir(MODULES_FOLDER . DS . $folder)) {
                    $mods[$folder] = ucfirst($folder);               
                    //$opt .= "<option value=$folder>".$folder."</option>" . "\n";
                }
            }
        }
        foreach($mods as $id=>$name_mod) {
            $opt .= "<option value=$id>".$name_mod."</option>" . "\n";
        }
        return $opt;
    } else return false;
}

function NameDate($date, $separator='/') {
    $a_bulan = array('01' => 'Januari',
                     '02' => 'Februari',
                     '03' => 'Maret',
                     '04' => 'April',
                     '05' => 'Mei',
                     '06' => 'Juni',
                     '07' => 'Juli',
                     '08' => 'Agustus',
                     '09' => 'September',
                     '10' => 'Oktober',
                     '11' => 'November',
                     '12' => 'Desember');
    
    $a_date  = explode($separator, $date);
    $nama_bulan = $a_bulan[$a_date[1]];
    $new_date = $a_date[0] . ' ' . $nama_bulan . ' ' . $a_date[2];
    return $new_date;
}
?>