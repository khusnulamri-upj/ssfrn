<?php
// Author : Irvandy Goutama
// Email  : irvandygoutama@gmail.com
// Start  : 05 Juni 2008

session_start();

include_once "../dwo.lib.php";
include_once "../db.mysql.php";
include_once "../connectdb.php";
include_once "../parameter.php";
include_once "../cekparam.php";
include_once "../fpdf.php";

// *** Parameters ***
$TahunID = GetSetVar('TahunID');
$ProdiID = GetSetVar('ProdiID');

// *** Init PDF
$pdf = new FPDF();
$pdf->SetTitle("Laporan Tunggakan Mahasiswa");
$pdf->SetAutoPageBreak(true, 5);
$pdf->AddPage();
$tahunstring = (empty($TahunID))? '' : "Tahun $TahunID";
HeaderLogo("Laporan Tunggakan Mahasiswa $tahunstring", $pdf, 'P');
BuatHeaderTable($TahunID, $ProdiID, $pdf);
$lbr = 190;

BuatIsinya($TahunID, $ProdiID, $pdf);

$pdf->Output();

// *** Functions ***
function BuatIsinya($TahunID, $ProdiID, $p) {
  $whr_prodi = (empty($ProdiID))? '' : "and k.ProdiID = '$ProdiID' ";
  $whr_tahun = (empty($TahunID))? '' : "and k.TahunID = '$TahunID'";
  $s = "select k.MhswID, m.Nama, k.ProdiID, k.IP, k.SKS, k.TahunID,  
      (k.Biaya - k.Potongan) as Tagihan,
	  k.Bayar
    from khs k 
      left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
    where k.KodeID='".KodeID."'
	  $whr_tahun
      $whr_prodi
	  and ((k.Biaya-k.Potongan)-k.Bayar) > 0
    order by k.MhswID";
  $r = _query($s); $n = 0;
  $t = 5; $ttl = 0; $_mhsw = ';alskdjfa;lsdhguairgsofjhjg9e8rgjpsofjg';
  
  if(_num_rows($r) > 0)
  {
	  while ($w = _fetch_array($r)) {	
		$n++;
		$ttlsks += $w['SKS'];
		$ttlipk += $w['IP'];
		$selisih = $w['Tagihan']-$w['Bayar'];
		$ttlselisih += $selisih;
		$p->SetFont('Helvetica', '', 10);
		$p->Cell(10, $t, $n, 'LB', 0);
		$p->Cell(30, $t, $w['MhswID'], 'B', 0);
		$p->Cell(70, $t, $w['Nama'], 'B', 0);
		$p->Cell(22, $t, $w['IP'].'/'.$w['SKS'], 'B', 0, 'C');
		$p->Cell(15, $t, $w['TahunID'], 'B', 0, 'C');
		$p->Cell(22, $t, number_format($selisih, 0, ',', '.'), 'B', 0, 'R');
		$p->Ln($t); 
	  }
	  $_ttl = number_format($ttl+0);
	  $p->SetFont('Helvetica', 'B', 11);
	  $p->Cell($lbr, 1,  ' ', 1, 1);
	  $p->Cell(110, $t, 'TOTAL :', 0, 0, 'R');
	  $p->Cell(22, $t, number_format($ttlipk/$n, 2).'/'.number_format($ttlsks/$n, 2), 0, 0, 'R');
	  $p->Cell(22, $t, $_ttl, 0, 0, 'R'); 
	  $p->Ln($t+2);
  }
}
function BuatHeadertable($TahunID, $ProdiID, $p) {
  global $lbr;
  $t = 5;
  $prd = GetaField('prodi', "ProdiID = '$ProdiID' and KodeID", KodeID, 'Nama');
  $p->SetFont('Helvetica', 'B', 14);
  $tahunstring = (empty($TahunID))? '' : "pada $TahunID";
  $p->Ln(4);
  
  $p->SetFont('Helvetica', 'BI', 10);
  $p->Cell(10, $t, 'Nmr', 1, 0);
  $p->Cell(30, $t, 'N I M', 1, 0);
  $p->Cell(70, $t, 'Nama Mhsw', 1, 0);
  $p->Cell(22, $t, 'IPK/SKS', 1, 0, 'C');
  $p->Cell(15, $t, 'Tahun', 1, 0, 'C');
  $p->Cell(22, $t, 'Tunggakan', 1, 0, 'C');
  $p->Ln($t);
}

function HeaderLogo($jdl, $p, $orientation='P')
{	$pjg = 110;
	$logo = (file_exists("../img/logo.jpg"))? "../img/logo.jpg" : "img/logo.jpg";
    $identitas = GetFields('identitas', 'Kode', KodeID, 'Nama, Alamat1, Telepon, Fax');
	$p->Image($logo, 12, 8, 18);
	$p->SetY(5);
    $p->SetFont("Helvetica", '', 8);
    $p->Cell($pjg, 5, $identitas['Yayasan'], 0, 1, 'C');
    $p->SetFont("Helvetica", 'B', 10);
    $p->Cell($pjg, 7, $identitas['Nama'], 0, 0, 'C');
    
	//Judul
	if($orientation == 'L')
	{
		$p->SetFont("Helvetica", 'B', 16);
		$p->Cell(20, 7, '', 0, 0);
		$p->Cell($pjg, 7, $jdl, 0, 1, 'C');
	}
	else
	{	$p->SetFont("Helvetica", 'B', 12);
		$p->Cell(80, 7, $jdl, 0, 1, 'R');
	}
	
    $p->SetFont("Helvetica", 'I', 6);
	$p->Cell($pjg, 3,
      $identitas['Alamat1'], 0, 1, 'C');
    $p->Cell($pjg, 3,
      "Telp. ".$identitas['Telepon'].", Fax. ".$identitas['Fax'], 0, 1, 'C');
    $p->Ln(3);
	if($orientation == 'L') $length = 275;
	else $length = 190;
    $p->Cell($length, 0, '', 1, 1);
    $p->Ln(2);
}

?>
