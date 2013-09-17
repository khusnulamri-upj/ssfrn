<?php
// *** Functions ***
function TampilkanCariData($mnux='bn.carrer', $gos='DftrPerusahaan') {
  echo "<p><table class=box>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='$mnux'>
  <input type=hidden name='gos' value='$gos'>
  <tr><td class=ul colspan=2><b>$_SESSION[KodeID]</b></td></tr>
  <tr><td class=inp>Cari Perusahaan</td>
      <td class=ul><input type=text name='_dataval' value='$_SESSION[_dataval]' size=20 maxlength=50>
      <input type=submit name='_datakey' value='Nama'>
      <input type=button name='Reset' value='Reset' onClick=\"location='?mnux=$mnux&gos=&_dataval=&_datakey='\">
      </td></tr>
  </form></table></p>";
}
function DftrPerusahaan() {
  $order = (empty($_SESSION['_datakey']))? 'MhswID' : ($_SESSION['_datakey'] == 'NPM')? 'MhswID' : $_SESSION['_datakey'];
  $_whr = (empty($_SESSION['_dataval']))? '' : "and m.$order like '%$_SESSION[_dataval]%' "; 
  // tampilkan
  include_once "class/dwolister.class.php";
  $lst = new dwolister;
  $lst->maxrow = 20;
  $lst->page = $_SESSION['_datapage']+0;
  $lst->pageactive = "=PAGE=";
  $lst->pages = "<a href='?mnux=bn.carrer&_datapage==PAGE='>=PAGE=</a>";

  $lst->tables = "perusahaan p
      $_whr
    order by p.$order";
  $lst->fields = "p.PerusahaanID, p.Nama, p.Alamat, 
    p.Telepon, p.Handphone, p.Email, p.Kota";
  $lst->headerfmt = "<p><table class=box cellspacing=1 cellpadding=4>
    <tr><th class=ttl>No.</th>
    <th class=ttl>Perusahaan ID</th>
    <th class=ttl>Nama</th>
    <th class=ttl>Alamat</th>
    <th class=ttl>Kota</th>
    <th class=ttl>Telp, HP</th>
    <th class=ttl>Email</th>
    </tr>";
  $lst->footerfmt = "</table></p>";
  $lst->detailfmt = "<tr>
    <td class=inp1>=NOMER=</td>
    <td class=ul><a href='?mnux=bn.carrer.det&PerusahaanID==PerusahaanID='><img src='img/edit.png'>
    =PerusahaanID=</a></td>
    <td class=ul nowrap>=Nama=</td>
    <td class=ul>=Alamat=</td>
    <td class=ul>=Kota=</td>
    <td class=ul>=Telepon=, =Handphone=</td>
    <td class=ul>=Email=</td>
    </tr>";
  echo $lst->TampilkanData();
  echo $ttl;
  echo "<p>Hal.: ". $lst->TampilkanHalaman() . "<br />".
    "Total: " . number_format($lst->MaxRowCount). "</p>";  
}

// *** Parameters ***
$_alumnikey = GetSetVar('_datakey', 'Nama');
$_alumnival = GetSetVar('_dataval');
$_alumnipage = GetSetVar('_datapage', 1);
$gos = (empty($_REQUEST['gos']))? "DftrPerusahaan" : $_REQUEST['gos'];

// *** Main ***
TampilkanJudul("BINA INSNI CARRER");
TampilkanCariData();
$gos();
?>
