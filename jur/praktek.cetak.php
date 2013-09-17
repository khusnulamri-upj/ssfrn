<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 08 Sept 2008

session_start();

  include_once "../dwo.lib.php";
  include_once "../db.mysql.php";
  include_once "../connectdb.php";
  include_once "../parameter.php";
  include_once "../cekparam.php";
  include_once "../fpdf.php";

// *** Parameters ***
$TahunID = GetSetVar('TahunID');
if (empty($TahunID))
  die(ErrorMsg("Error",
    "Tentukan tahun akademik-nya dulu.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup'
      onClick='window.close()' />"));

// *** Main
$thn = NamaTahun($TahunID);
$pdf = new FPDF('L');
$pdf->SetTitle("Daftar Mahasiswa Praktek Kerja - $thn");
$pdf->AddPage('L');
HeaderLogo("Daftar Mahasiswa Praktek Kerja - $thn", $pdf, 'L');
$pdf->SetFont('Helvetica', 'B', 14);

Isinya($pdf);

$pdf->Output();

// *** Functions ***
function Isinya($p) {
  $lbr = 190; $t = 5;
  JudulKolomnya($p);
  $s = "select p.*,
      left(m.Nama, 28) as Mhsw,
      m.TahunID, date_format(p.TglMulai, '%d-%m-%y') as _TglMulai,
	  date_format(p.TglSelesai, '%d-%m-%y') as _TglSelesai,
      left(d.Nama, 28) as DSN, d.Gelar
    from praktekkerja p
      left outer join mhsw m on m.MhswID = p.MhswID and m.KodeID = '".KodeID."'
      left outer join dosen d on d.Login = p.Pembimbing and d.KodeID = '".KodeID."'
    where p.KodeID = '".KodeID."'
      and p.TahunID = '$_SESSION[TahunID]'
    order by m.ProdiID, m.MhswID";
  $r = _query($s);
  $n = 0;
  
  while ($w = _fetch_array($r)) {
    $n++;
	$p->SetFont('Helvetica', '', 8);
	$p->Cell(70, $t, '', 0, 0);
    $p->Cell(120, $t, $w['NamaPekerjaan'], 0, 0);
    $p->Ln($t/2);
	
	$p->Cell(5, $t, $n, 0, 0);
    $p->Cell(20, $t, $w['MhswID'], 0, 0);
    $p->Cell(45, $t, $w['Mhsw'], 0, 0);
    $p->Cell(120, $t, '', 0, 0);
    $p->Cell(50, $t, $w['DSN'].', '.$w['Gelar'], 0, 0);
    $p->Cell(18, $t, $w['_TglMulai'], 0, 0, 'C');
	$p->Cell(18, $t, $w['_TglSelesai'], 0, 0, 'C');
	$p->Ln($t/2);
	
	$NamaPerusahaan = $w['NamaPerusahaan'].' - '.$w['AlamatPerusahaan'].', '.$w['KotaPerusahaan'];
	$p->SetFont('Helvetica', 'I', 8);
	$p->Cell(75, $t, '', 'B', 0);
    $p->Cell(115, $t, $NamaPerusahaan, 'B', 0);
    $p->Cell(86, $t, '', 'B', 0);
    $p->Ln($t);
  }
}
function JudulKolomnya($p) {
  $t = 6;
  $p->SetFont('Helvetica', 'B', 8);
  $p->Cell(5, $t, 'No.', 'BT', 0);
  $p->Cell(20, $t, 'N I M', 'BT', 0);
  $p->Cell(45, $t, 'Mahasiswa', 'BT', 0);
  $p->Cell(120, $t, 'Nama Pekerjaan dan Perusahaan', 'BT', 0);
  $p->Cell(50, $t, 'Dosen Pembimbing', 'BT', 0);
  $p->Cell(18, $t, 'Tgl Mulai', 'BT', 0);
  $p->Cell(18, $t, 'Tgl Selesai', 'BT', 0);
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
	$p->SetFont("Helvetica", 'B', 16);
	$p->Cell(20, 7, '', 0, 0);
    $p->Cell($pjg, 7, $jdl, 0, 1, 'C');
	
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
