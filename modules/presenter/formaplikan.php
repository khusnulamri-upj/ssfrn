<?php
include "aplikan.edt.php";

function AplikanForm() {
    global $arrID;
    
    // Jika Superuser
    $_aktif = ($_SESSION['_LevelID'] ==1)? "<input type=text name='pmbaktif' value='$_SESSION[pmbaktif]' size=10 maxlength=10>" : "<input type=hidden name='pmbaktif' value='$_SESSION[pmbaktif]'><b>$_SESSION[pmbaktif]</b>";
  
    echo "<p><table class=box cellspacing=1 cellpadding=4>
    <form action='?' method=GET>
    <input type=hidden name='mnux' value='formaplikan'>
    <tr>
	<td class=ttl colspan=4><strong>$arrID[Nama]</strong></td>
    </tr>
    <tr>
	<td class=inp>Periode Aktif</td><td class=ul colspan=3>: $_aktif</td>
    </tr>
    <tr>
	<td class=inp>Cari Aplikan</td><td class=ul>: <input type=text name='aplikancari' value='$_SESSION[pmbcari]' size=20 maxlength=20></td>
      <td class=ul colspan=2>
      <input type=submit name='Cari' value='AplikanID'>
      <input type=submit name='Cari' value='Nama'>
    </td></tr>
    <tr><td class=ul colspan=4><a href='?mnux=formaplikan&gos=edtAplikan&md=1'>Input Data Aplikan</a>
    </td></tr>
    </form></table></p>";
}

function DftrAplikan() {
    global $_defmaxrow;
    include_once "class/lister.class.php";

    $_cari2 = (!empty($_SESSION['aplikancari']))? " and p.$_SESSION[Cari] like '%$_SESSION[aplikancari]%' " : '';

    $pagefmt = "<a href='?mnux=formaplikan&gos=DftrAplikan&SR==STARTROW='>=PAGE=</a>";
    $pageoff = "<b>=PAGE=</b>";
  
    $lister = new lister;
    $lister->tables = "aplikan ap
	left outer join presenter p on p.Login = ap.PresenterID
    where 
       AplikanID like '$_SESSION[pmbaktif]%' $_cari2
    order by ap.AplikanID desc";
    $lister->fields = "ap.AplikanID, p.Nama as Presenter, ap.Nama, ap.NA, ap.Alamat, ap.TanggalDaftar";
    $lister->startrow = $_REQUEST['SR']+0;
    $lister->maxrow = $_defmaxrow;
    $lister->headerfmt = "<p><table class=box cellspacing=1 cellpadding=4>
		<tr>
		  <th class=ttl>No.</th>
		  <th class=ttl>No. Aplikan</th>
		  <th class=ttl>Presenter</th>
		  <th class=ttl>Nama</th>
		  <th class=ttl>Alamat</th>
		  <th class=ttl>Pilihan D3</th>
		  <th class=ttl>Pilihan S1</th>
		  <th class=ttl>Tanggal Daftar</th>
		</tr>";
    $lister->detailfmt = "<tr>
		<td class=inp1 width=18 align=right>=NOMER=</td>
		<td class=cna=NA=><a href=\"?mnux=formaplikan&gos=PMBEdt0&md=0&aplkan==AplikanID=\"><img src='img/edit.png' border=0>=AplikanID=</a></td>
		<td class=cna=NA=>=Presenter=&nbsp;</td>
		<td class=cna=NA=>=Nama=</a></td>
		<td class=cna=NA=>=Alamat=</td>
		<td class=cna=NA=>=PilihanD3=&nbsp;</td>
		<td class=cna=NA=>=PilihanS1=&nbsp;</td>
		<td class=cna=NA=>=TglDaftar=</td>";
    $lister->footerfmt = "</table></p>";
    $halaman = $lister->WritePages ($pagefmt, $pageoff);
    $TotalNews = $lister->MaxRowCount;
    $usrlist = $lister->ListIt () . "<br>Halaman: $halaman<br> Total: $TotalNews";
    echo $usrlist;
}

// *** Parameters ***
$pmbcarikey = GetSetVar('Cari', 'Nama');
$pmbcari = GetSetVar('pmbcari');

// Periode PMB yg Aktif hrs dimaintain dgn baik
if ($_SESSION['_LevelID'] != 1) {
  $pmbaktif = GetaField('pmbperiod', 'NA', 'N', 'PMBPeriodID');
} 
else {
  if (empty($_SESSION['pmbaktif'])) $pmbaktif = GetaField('pmbperiod', 'NA', 'N', 'PMBPeriodID');
  else $pmbaktif = GetSetVar('pmbaktif');
}
$_SESSION['pmbaktif'] = $pmbaktif;

// *** Main ***
TampilkanJudul("Form Aplikan");

if (!empty($pmbaktif)) {
  $gos = (empty($_REQUEST['gos']))? 'DftrAplikan' : $_REQUEST['gos'];
  AplikanForm();
  $gos();
} else echo ErrorMsg("Gagal", "Tidak ada periode PMB yang aktif. Hubungi Kepala Admisi untuk mengaktifkan periode/gelombang PMB.");
?>