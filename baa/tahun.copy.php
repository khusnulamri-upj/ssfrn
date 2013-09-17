<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// start  : 16 Sept 2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Copy Tahun Akademik dari Prodi Lain");

// *** Parameters ***
$ProdiID = GetSetVar('ProdiID');
$ProgramID = GetSetVar('ProgramID');

// *** Main ***
TampilkanJudul("Salin Dari Prodi Lain");
$gos = (empty($_REQUEST['gos']))? 'Salin' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function Salin() {
  
  $optprd = GetOption2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_REQUEST['ProdiID'], "KodeID='".KodeID."' and ProdiID!='".$_SESSION[prodi]."'", 'ProdiID');
  if (!empty($_REQUEST['ProdiID'])) {
    $optbpt = GetOption333('tahun', "concat(TahunID, ' - ', Nama)", "TahunID Desc", '', "KodeID='".KodeID."' and ProdiID = '$_REQUEST[ProdiID]'",'TahunID');
  }
  else $optbpt = "<option value=''>{ Tidak ada Skema Tahun Akademik }</option>";
  $NamaProdi = GetaField('prodi','ProdiID',$_SESSION[prodi],'Nama');  
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  <form name='frm' action='../$_SESSION[mnux].copy.php' method=POST onSubmit="return CheckForm(this)">
  <input type=hidden name='gos' value='Simpan' />
  <input type=hidden name='bipotid' value='$bipotid' />
  
  <tr><td class=ul colspan=2 align=center>
      Hasil Salinan Skema Tahun Akademik akan sama persis dengan sumber salinan, yang membedakan hanyalah nama prodi.
      </td></tr>
  <tr><td class=inp>Prodi Tahun Akademik Tujuan:</td>
      <td class=ul>$NamaProdi</td>
      </tr>
  <tr><th class=ttl colspan=2>Ambil Dari Prodi:</td></tr>
  <tr><td class=inp>Prodi:</td>
      <td class=ul>
      <select name='ProdiID' onChange="javascript:window.location='../$_SESSION[mnux].copy.php?ProdiID='+frm.ProdiID.value">$optprd</select>
      </td></tr>
  <tr><td class=inp>Skema Tahun:</td>
      <td class=ul>
      <select name='TahunID'>$optbpt</select>
      </td></tr>
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal' onClick='window.close()' />
      </td></tr>
  </form>
  </table>
ESD;
}
function Simpan() {
  $ProdiID = sqling($_REQUEST['ProdiID']);
  $TahunID = sqling($_REQUEST['TahunID']);
  // Ambil data dari sumber tahun akademik
  $data = GetFields('tahun',"TahunID='$TahunID' and ProgramID='$_SESSION[prid]' and ProdiID", $ProdiID, '*');	
  if ($data['NA'] == 'N') { 
		// Set all NA to Y
		$s = "update tahun set NA='Y' where ProgramID='$_SESSION[prid]' and ProdiID='$_SESSION[prodi]'";
		$r = _query($s);
	} else {		
	}
  $s = "insert into tahun
	(TahunID, KodeID, ProdiID, ProgramID, Nama, SP,
	TglKuliahMulai, TglKuliahSelesai,
	TglKRSMulai, TglKRSSelesai,
	TglKRSOnlineMulai, TglKRSOnlineSelesai,
	TglBayarMulai, TglBayarSelesai,
	TglAutodebetSelesai, TglAutodebetSelesai2,
	TglKembaliUangKuliah, 
	TglUbahKRSMulai, TglUbahKRSSelesai,
	TglCetakKSS1, TglCetakKSS2, 
	TglUTSMulai, TglUTSSelesai,	
	TglUASMulai, TglUASSelesai,	
	TglCuti, TglMundur, TglNilai, TglAkhirKSS, HanyaAngkatan, 
	ProsesBuka, ProsesIPK, ProsesTutup, Catatan,
	LoginBuat, TglBuat, NA)
	values ('$data[TahunID]', '".KodeID."', '$_SESSION[prodi]', '$_SESSION[prid]', '$data[Nama]', '$data[SP]',
	'$data[TglKuliahMulai]', '$data[TglKuliahSelesai]',
	'$data[TglKRSMulai]', '$data[TglKRSSelesai]',
	'$data[TglKRSOnlineMulai]', '$data[TglKRSOnlineSelesai]',
	'$data[TglBayarMulai]', '$data[TglBayarSelesai]',
	'$data[TglAutodebetSelesai]', '$data[TglAutodebetSelesai2]',
	'$data[TglKembaliUangKuliah]',
	'$data[TglUbahKRSMulai]', '$data[TglUbahKRSSelesai]',
	'$data[TglCetakKSS1]', '$data[TglCetakKSS2]',
	'$data[TglUTSMulai]', '$data[TglUTSSelesai]',	
	'$data[TglUASMulai]', '$data[TglUASSelesai]',	
	'$data[TglCuti]', '$data[TglMundur]', '$data[TglNilai]', '$data[TglAkhirKSS]', '$data[HanyaAngkatan]', 
	'$data[ProsesBuka]', '$data[ProsesIPK]', '$data[ProsesTutup]', '$data[Catatan]',
	'$_SESSION[_Login]', now(), '$data[NA]')";
  $r = _query($s);
  TutupScript();
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
