<?php
// Author: Emanuel Setio Dewo, setio_dewo@telkom.net
// 2005-12-26

// *** Functions ***
function DftrKampus() {
  global $_Identitas;
  $s = "select * 
    from memo 
    order by MemoID";
  $r = _query($s);
  $cs = 4;
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=500>
    <tr><td colspan=$cs class=ul1>
        <input type=button name='TambahKampus' value='Tambah Memo'
          onClick=\"location='?mnux=$_SESSION[mnux]&gos=KampEdt&md=1'\" />
        </td></tr>
    <tr><th class=ttl colspan=2>ID</th>
    <!--<th class=ttl>Memo</th>-->
    <!--<th class=ttl>NA</th>-->
    </tr>";
  while ($w = _fetch_array($r)) {
    $c = 'class=ul' ;//($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr>
    <td $c width=35 align=center><input type=button name='Edit' value='&raquo;' onClick=\"location='?mnux=$_SESSION[mnux]&gos=KampEdt&md=0&KampID=$w[MemoID]'\" /></td>
    <td $c width=100>$w[MemoID]</td>
    <!--<td $c>$w[MemoDesc]</td>-->
    <!--<td $c align=center width=10><img src='img/book$w[NA].gif'></td>-->
    </tr>";
  }
  echo "</table></p>";
}
function KampEdt() {
  global $_Identitas;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = GetFields('memo', 'MemoID', $_REQUEST['KampID'], '*');
    $jdl = "Edit Memo";
    $strid = "<input type=hidden name='KampusID' value='$w[MemoID]'><h1>$w[MemoID]</h1>";
  }
  else {
    $w = array();
    $w['MemoID'] = '';
    $w['MemoDesc'] = '';    
    $jdl = "Tambah Memo";
    $strid = "<input type=text name='KampusID' size=15 maxlength=15>";
  }
  $snm = session_name(); $sid = session_id();
  //$na = ($w['NA'] == 'Y')? 'checked' : '';
  $c1 = 'class=inp'; $c2 = 'class=ul';
  CheckFormScript("MemoID,MemoNama");
  // Tampilkan
  echo "<table class=box cellspacing=1 cellpadding=4 width=500>
  <form action='?' method=POST onSubmit='return CheckForm(this)'>
  <input type=hidden name='mnux' value='$_SESSION[mnux]'>
  <input type=hidden name='gos' value='KampSav'>
  <input type=hidden name='md' value='$md'>
  <tr><th colspan=2 class=ttl>$jdl</th></tr>
  <tr><!--<td $c1>Kode Memo</td>--><td $c2>$strid</td></tr>  
  <tr><!--<td $c1>Memo</td>--><td $c2><textarea name='Alamat' cols=130 rows=35>$w[MemoDesc]</textarea></td>
  <tr><td colspan=2 align=center>
    <input type=submit name='Simpan' value='Simpan'>
    <input type=Reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?mnux=$_SESSION[mnux]&gos=&$snm=$sid'\"></td></tr>
  
  </form></table>";
}
function KampSav() {
  $md = $_REQUEST['md']+0;
  $KampusID = $_REQUEST['KampusID'];  
  $Alamat = sqling($_REQUEST['Alamat']);  
  // simpan
  if ($md == 0) {
    $s = "update memo set MemoDesc='$Alamat'
      where MemoID='$KampusID' ";
    $r = _query($s);
  }
  else {
    $ada = GetFields('memo', 'MemoID', $KampusID, '*');
    if (!empty($ada)) echo ErrorMsg("Gagal Simpan",
      "Memo dengan kode: <b>$KampusID</b> telah ada dengan nama <b>$ada[MemoID]</b>.<br>
      Gunakan kode lain.");
    else {
      $s = "insert into memo (MemoID, MemoDesc)
        values ('$KampusID', '$Alamat')";
      $r = _query($s);
    }
  }
  BerhasilSimpan("?mnux=$_SESSION[mnux]", 100);
}

// *** Parameters ***
$gos = (empty($_REQUEST['gos']))? 'DftrKampus' : $_REQUEST['gos'];
$KodeID = GetSetVar('KodeID', $_Identitas);

// *** Main ***
TampilkanJudul("Memo");
$gos();
?>
