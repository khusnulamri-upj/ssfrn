<?php
// *** functions ***
function TampilkanFilterPresenter($mnux='presenter', $add=1) {
  global $arrID;
  
  $ck_nama = ($_SESSION['prsurt'] == 'Nama') ? 'checked' : '';
  $ck_login = ($_SESSION['prsurt'] == 'Login') ? 'checked' : '';
  $stradd = ($add == 0)? '' : "<tr><td class=ul>Pilihan:</td><td class=ul><a href='?mnux=$mnux&gos=PresenterAdd&md=1'>Tambah Presenter</td></tr>";
  echo "<p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='$mnux'>
  <input type=hidden name='presenterpage' value='1'>
  <tr><td class=ul colspan=2><b>$arrID[Nama]</b></td></tr>
  <tr><td class=ul>Cari Presenter:</td>
  <td class=ul><input type=text name='prescr' value='$_SESSION[prescr]' size=10 maxlengh=10>
    <input type=submit name='prskeycr' value='Login'>   
    <input type=submit name='prskeycr' value='Nama'>
    <input type=submit name='prskeycr' value='Reset'></td></tr>
  <tr><td class=ul>Urut berdasarkan:</td><td class=ul>
    <input type=radio name='prsurt' value='Nama' $ck_nama> Nama,
    <input type=radio name='prsurt' value='Login' $ck_login>Login/NIP
    <input type=submit name='Urutkan' value='Urutkan'></td></tr>
  $stradd
  </form></table></p>";
}
function DaftarPresenter($mnux='', $lnk='', $fields='') {
  global $_defmaxrow, $_FKartuUSM;
  include_once "class/dwolister.class.php";
  
  //$lnk = "gos=DsnEdt&md=0&dsnid==Login="; 
  // Buat Header:
  $_f = explode(',', $fields);
  $hdr = ''; $brs = '';
  for ($i = 0; $i < sizeof($_f); $i++) {
    $hdr .= "<th class=ttl>". $_f[$i] . "</th>";
    $brs .= "<td class=cna=NA=>=".$_f[$i]."=&nbsp;</td>";
  }
  $whr = array();
  if (!empty($_SESSION['prskeycr']) && !empty($_SESSION['prscr'])) {
    if ($_SESSION['prskeycr'] == 'Login') {
	$whr[] = "$_SESSION[prskeycr] like '$_SESSION[prscr]%'";
    } else $whr[] = "$_SESSION[prskeycr] like '%$_SESSION[prscr]%'";
  }
  $where = implode(' and ', $whr);
  $where = (empty($where))? '' : "and $where";
   
  $lst = new dwolister;
  $lst->maxrow = 20;
  $lst->page = $_SESSION['prspage']+0;
  $lst->pageactive = "=PAGE=";
  $lst->pages = "<a href='?mnux=$mnux&gos=&prspage==PAGE='>=PAGE=</a>";
  $lst->tables = "presenter
    where KodeID='$_SESSION[KodeID]' $where
    order by $_SESSION[prsurt]";
  $lst->fields = "* ";
  $lst->headerfmt = "<p><table class=box cellspacing=1 cellpadding=4>
    <tr>
    <th class=ttl>#</th>
    <th class=ttl>Login/NIP</th>
    $hdr
    <th class=ttl>NA</th>
    </tr>";
  $lst->detailfmt = "<tr>
	<td class=inp1 width=18 align=right>=NOMER=</td>
	<td class=cna=NA=><a href=\"?mnux=$mnux&$lnk\"><img src='img/edit.png' border=0>=Login=</a></td>
	$brs
	<td class=cna=NA= align=center><img src='img/book=NA=.gif'></td>
	</tr>";
  $lst->footerfmt = "</table></p>";
  echo $lst->TampilkanData();
  $halaman = $lst->TampilkanHalaman();
  $total = $lst->MaxRowCount;
  $total = number_format($total);
  echo "<p>Halaman : " . $halaman . "<br />" . "Total: ". $total . "</p>";
}
?>