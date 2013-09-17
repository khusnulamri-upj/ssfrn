<?php
// Proses Batch NIM
session_start();
include_once "db.mysql.php";
include_once "connectdb.php";
include_once "dwo.lib.php";
include_once "mhswbaru.sav.php";
include_once "mhswbaru.lib.php";
include_once "mhswbaru.import.php";

function PRCNIM() {
    echo "<body bgcolor=#EEFFFF>";
    $tahun_angkatan = $_REQUEST['thnangkatan'];

    $pss = $_SESSION['NIMPOS'];
    $pmbid = $_SESSION['NIM'.$pss];
   
    $w = GetFields('pmb', 'PMBID', $pmbid, '*');
    
    // Jika ada data
    if (!empty($pmbid)) {
      if (empty($w['NIM'])) {
	if (!empty($w['ProdiID'])) {
	   echo "<p>#<font size=+2>". $pss . "</font> &raquo; <b>$tahun_angkatan</b> &raquo; " .$_SESSION['NIM'.$pss]."</p><hr>";
	   
	  $MhswID = ImportPMB($w, $tahun_angkatan);
	  echo "<p><font color=green>DIPROSES</font></p>";
	}
      }
    }
    // refresh page
    if ($_SESSION['NIMPOS'] < $_SESSION['NIMCOUNT']) {
      echo "<script type='text/javascript'>window.onload=setTimeout('window.location.reload()', 2);</script>";
    }
    else {
      echo "<p>Proses Pembuatan NIM untuk angkatan <b>$tahun_angkatan</b> sudah <font size=+2>SELESAI</font></p>";
    }
  
    $_SESSION['NIMPOS']++;
}

if (!empty($_REQUEST['gos'])) $_REQUEST['gos']();

include_once "disconnectdb.php";
?>