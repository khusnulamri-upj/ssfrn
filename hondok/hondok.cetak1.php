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
$_DosenID = $_REQUEST['_detDosenID'];
$_Tahun = $_REQUEST['_detTahun'];
$_Bulan = $_REQUEST['_detBulan'];
$_id = $_REQUEST['_detid'];


// *** Init PDF
$pdf = new FPDF();
$pdf->SetTitle("Honor Dosen");
$pdf->SetAutoPageBreak(true, 5);
$pdf->AddPage();
HeaderLogo("Kwitansi Pembayaran Honor Dosen", $pdf, 'P',  
	((empty($_id))? "" : "Minggu Ke-".substr(GetaField('honordosen', 'HonorDosenID', $_id, 'Minggu'), 1, 1))." Bulan ".UbahKeBulanIndonesia($_Bulan)." $_Tahun");
BuatHeaderTable($_DosenID, $_Tahun, $_Bulan, $_id, $pdf);
$lbr = 190;

BuatIsinya($_DosenID, $_Tahun, $_Bulan, $_id, $pdf);
BuatPrintedOn('', $pdf);

$pdf->Output();

// *** Functions ***
function BuatIsinya($_DosenID, $_Tahun, $_Bulan, $_id, $p) {
  $whr_id = (empty($_id))? "" : "and p.HonorDosenID = '$_id'";
  $s = "select h.*, m.Nama as Mgg 
        from honordosen h
        left outer join minggu m on h.Minggu=m.MingguID
        where h.DosenID = '$_DosenID'
	      and h.Bulan = '$_Bulan'
        and h.Tahun = '$_Tahun'";
  
  /*
  $s = "select DISTINCT(j.MKKode), j.SKS,
      p.SKSHonor,
      j.Nama, 
	  sum(p.TunjanganSKS) as _TunjanganSKS,
	  sum(p.TunjanganTetap) as _TunjanganTetap,
	  sum(p.TunjanganTransport) as _TunjanganTransport,
	  count(p.PresensiID) as _Pertemuan
    from presensi p
      left outer join jadwal j on j.JadwalID = p.JadwalID
      left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
    where 
      p.DosenID = '$_DosenID'
	  and MONTH(p.Tanggal) = '$_Bulan'
      and YEAR(p.Tanggal) = '$_Tahun'
	  $whr_id
	group by p.HonorDosenID
    order by p.Pertemuan, j.MKKode, p.Tanggal";
    */
  $r = _query($s); $n = 0;
  $t = 5; $ttl = 0; $_mhsw = ';alskdjfa;lsdhguairgsofjhjg9e8rgjpsofjg';
  $ttlsks = 0; $ttlhonor =0;
  if(_num_rows($r) > 0)
  {
	  while ($w = _fetch_array($r)) {
    $n++;
		$honor   = $w['TunjanganSKS']+$w['TunjanganTransport']+$w['TunjanganTetap']+0;
		$pajak   = $honor*$w['Pajak']/100;
		$total   = $honor-$pajak;
		$ttlhonor +=$honor;
		$ttlpajak +=$pajak;
		$ttltotal +=$total;
		
		$p->SetFont('Helvetica', '', 10);
		$p->Cell(10, $t, $n, 'LB', 0, 'R');
		$p->Cell(85, $t, $w['Mgg'], 'B', 0);
		$p->Cell(30, $t, number_format($honor, 0, ',', '.'), 'B', 0, 'R');
		$p->Cell(30, $t, number_format($pajak, 0, ',', '.'), 'B', 0, 'R');
		$p->Cell(35, $t, number_format($total, 0, ',', '.'), 'B', 0, 'R');
    $p->Ln($t); 
	  }
	  
	  $_ttl = number_format($ttl+0);
	  $p->SetFont('Helvetica', 'B', 11);
	  $p->Cell($lbr, 1,  ' ', 1, 1);
	  $p->Cell(95, $t, 'TOTAL :', 0, 0, 'R');
	  $p->Cell(30, $t, number_format($ttlhonor, 0, ',', '.'), 0, 0, 'R');
	  $p->Cell(30, $t, number_format($ttlpajak, 0, ',', '.'), 0, 0, 'R'); 
	  $p->Cell(35 ,$t, number_format($ttltotal, 0, ',', '.'), 0, 0, 'R');
	  $p->Ln($t);
	 
  }
}
function BuatHeadertable($_DosenID, $_Tahun, $_Bulan, $_id, $p) {
  global $lbr;
  $t = 5;
  
  $p->SetFont('Helvetica', '', 10);
  $dosen = GetFields('dosen', "Login='$_DosenID' and KodeID", KodeID, 'Nama, Login');
  $p->Cell(25, $t, 'NIP', 0, 0);
  $p->Cell(3, $t, ':', 0, 0);
  $p->Cell(100, $t, $dosen['Login'], 0, 0);
  $p->Ln($t);
  $p->Cell(25, $t, 'Nama Dosen', 0, 0);
  $p->Cell(3, $t, ':', 0, 0);
  $p->Cell(100, $t, $dosen['Nama'], 0, 0);
  $p->Ln($t);
  $p->Ln($t);
  
  $p->SetFont('Helvetica', 'BI', 10);
  $p->Cell(10, $t, 'No', 1, 0, 'R');
  $p->Cell(85, $t, 'Minggu', 1, 0,'C');
  $p->Cell(30, $t, 'Total Honor', 1,0, 'C');
  $p->Cell(30, $t, 'Pajak', 1, 0, 'C');
  $p->Cell(35, $t, 'Total', 1, 0, 'C');
  $p->Ln($t);
}

function HeaderLogo($jdl, $p, $orientation='P', $jdltambahan='')
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
		$p->Cell($pjg, 7, $jdl, 0, 0, 'C');
	}
	else
	{	$p->SetFont("Helvetica", 'B', 12);
		$p->Cell(80, 7, $jdl, 0, 0, 'R');
	}
	
	$p->Ln($t);
    $p->SetFont("Helvetica", 'I', 6);
	$p->Cell($pjg, 3, $identitas['Alamat1'], 0, 0, 'C');
	
	
	// Judul 2
	if($orientation == 'L')
	{
		$p->SetFont("Helvetica", 'B', 16);
		$p->Cell(20, 7, '', 0, 0);
		$p->Cell($pjg, 7, $jdltambahan, 0, 0, 'C');
	}
	else
	{	$p->SetFont("Helvetica", 'B', 12);
		//$p->Cell($pjg, 7, '', 0, 0);
		$p->Cell(80, 7, $jdltambahan, 0, 0, 'R');
	}	 
	$p->Ln(3);
	
	$p->SetFont("Helvetica", 'I', 6);  
    $p->Cell($pjg, 3,
      "Telp. ".$identitas['Telepon'].", Fax. ".$identitas['Fax'], 0, 1, 'C');
    $p->Ln(3);
	if($orientation == 'L') $length = 275;
	else $length = 190;
    $p->Cell($length, 0, '', 1, 1);
    $p->Ln(2);
}

function BuatPrintedOn($text, $p)
{	$t = 5;
	$p->Cell(0, $t, '' , 'B', 1);
	
	$p->SetFont('Helvetica', 'I', 7);
	$p->Cell(0, $t, "Dicetak Tanggal: ".date('d/m/Y'), 0, 0);
}

function UbahKeBulanIndonesia($integer)
{	$arrBulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	return $arrBulan[$integer-1];
}

?>
