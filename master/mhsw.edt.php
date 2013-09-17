<?php
// Author: Emanuel Setio Dewo
// 28 Feb 2006

$mhswbck = GetSetVar('mhswbck');

// *** Functions ***
function TampilkanHeaderMhsw($w) {
  $foto = FileFotoMhsw($w['MhswID'], $w['Foto']);
  // Tampilkan
  if ($_SESSION['_LevelID'] == 120) { // Mahasiswa
    $btn1 = "";
  } else {
    $btn1 = "<input type=button name='GantiFoto' value='Ganti Foto' onClick=\"location='?mhswbck=$_SESSION[mnux]&mnux=master/mhsw.foto&mhswid=$w[MhswID]'\" />";
  }
  echo "<p><table class=box cellspacing=2 cellpadding=4 width=600>
  <tr><td class=inp width=100>NPM</td>
     <td class=ul><b>$w[MhswID]</b></td>
     <td class=box rowspan=6 style='padding: 2pt' align=center width=124>
     <img src='$foto' height=120></td>
     </tr>

  <tr><td class=inp>Nama</td>
      <td class=ul><b>$w[Nama]</b></td></tr>
  <tr><td class=inp>Program</td>
      <td class=ul>$w[ProgramID] - <b>$w[PRG]</b></td></tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul>$w[ProdiID] - <b>$w[PRD]</b></td></tr>
  <tr><td class=inp>Pilihan</td>
      <td class=ul>
      <input type=button name='Kembali' value='Kembali ke Daftar'
        onClick=\"location='?mnux=master/mhsw'\" />
      $btn1
      <input type=button name='CetakMhsw' value='Cetak Data'
	    onClick=\"CetakData('$w[MhswID]')\" />
	  </td></tr>
  </table>
  <script>
	function CetakData(id)
	{	lnk = \"$_SESSION[mnux].cetak.php?MhswID=\"+id;
		  win2 = window.open(lnk, \"\", \"width=600, height=400, scrollbars, status\");
		  if (win2.opener == null) childWindow.opener = self;
	}
  </script>";
}
/*
function pri() {
  include_once "mhsw.edt.pri.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmPribadi';
  $sub();
}
function almt() {
  include_once "mhsw.edt.almt.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmAlamat';
  $sub();
}
function akd() {
  include_once "mhsw.edt.akd.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmAkademik';
  $sub();
}
function sek() {
  include_once "mhsw.edt.sek.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmSekolah';
  $sub();
}
function ortu() {
  include_once "mhsw.edt.ortu.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmOrtu';
  $sub();
}
function bank() {
  include_once "mhsw.edt.bank.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmBank';
  $sub();
}
function pt() {
  include_once "mhsw.edt.pt.php";
  $sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'frmPT';
  $sub();
}


function mb(){
	include_once "mhsw.edt.masterbipot.php";
	$sub = (!empty($_REQUEST['sub']))? $_REQUEST['sub'] : 'BPTEDT'; 
	$sub();
}
*/

// *** Parameters ***
$arrmhswpg = array('Pribadi~pri',
  'Alamat<br />Tetap~almt',
  'Akademik~akd',
  'Orang<br />Tua~ortu',
  'Asal<br />Sekolah~sek',
  'Asal Perguruan<br />Tinggi~pt',
  'Bank~bank');
  //'Master Bipot->mb');

$mhswid = GetSetVar('mhswid');
$mhswpg = GetSetVar('mhswpg', 'pri');
$submodul = GetSetVar('submodul', 'pri');

// *** Main ***
TampilkanJudul("Data Mahasiswa");
if (!empty($_SESSION['mhswid'])) {
  $datamhsw = GetFields("mhsw m
    left outer join prodi prd on m.ProdiID=prd.ProdiID
    left outer join program prg on m.ProgramID=prg.ProgramID",
    'm.MhswID', $mhswid,
    "m.*, prd.Nama as PRD, prg.Nama as PRG");
  if (!empty($datamhsw)) {
    TampilkanHeaderMhsw($datamhsw);
    TampilkanSubModul($_SESSION['mnux'], $arrmhswpg, $submodul);
    include_once($_SESSION['mnux'].'.'.$submodul.'.php');
    //TampilkanSubMenu($mnux, $arrmhswpg, $pref, $token);
  }
  else echo ErrorMsg("Kesalahan",
    "Terjadi kesalahan. Mahasiswa dengan NPM: <b>$mhswid</b> tidak ditemukan.");
}

// *** Functions ***
function TampilkanSubModul($mnux, $arr, $act) {
  echo "<p><table class=bsc>";
  foreach ($arr as $a) {
    $i = explode('~', $a);
    $c = ($i[1] == $act)? "class=menuaktif" : "class=menuitem";
    echo "<td $c align=center><a href='?mnux=$mnux&submodul=$i[1]'>$i[0]</a></td>";
  }
  echo "</table></p>";
}
?>
