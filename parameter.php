<?php
// Parameter
$_ProductName = "Sisfo Kampus";
$_Institution = 'Universitas SisfoKampus';
$_Identitas = "SISFO";
$_Author = "Sisfokampus Team";
$_AuthorEmail = "";
$_URL = "";

$_Themes = "default";

if (!defined(KodeID)) define(KodeID, $_Identitas);
$arrID = GetFields('identitas', 'Kode', KodeID, '*');

// System
$_lf = "\r\n";
$_defmnux = 'login';
$_maxbaris = 10;

// PMB
$_PMBDigit = 4;


// Penanggalan
$arrBulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
  'Agustus', 'September', 'Oktober', 'November', 'Desember');
$arrHari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

?>
