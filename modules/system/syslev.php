<?php
// Author: Emanuel Setio Dewo
// 2005-12-27

//Function Level Baru (bisa Edit dan tambah level)

function DftrLevel(){
  $s = "SELECT * FROM level WHERE Accounting = 'N' ORDER BY LevelID";
  $r = _query($s);
  
  // Buat Grid
  echo gridTable('gridid', 350, 800);
  $tb  = "<table class=grid id=gridid cellspacing=1 cellpadding=4>";
  $tb .= "<thead>";
  $tb .= "<tr>";
  $tb .= "<td colspan=6><input type=button name=add value='Tambah Level'></td></tr>";
  $tb .= "  <th>Level ID</th></td>";
  $tb .= "  <th>Nama Level</th></td>";
  $tb .= "  <th>Tabel User</th></td>";
  $tb .= "  <th>Aktif</th></td>";
  $tb .= "<tr>";
  $tb .= "</thead>";
  $tb .= "<tbody>";
  
  // Isi Table
  $n=1;
  while ($w = _fetch_array($r)) {
    $cls = stripTable($n);
    
    $tb .= "<tr class=$cls>";
    $tb .= "<td><a href=?mnux=syslev&gos=edtLevel&md=0&lvl=$w[LevelID] title=Edit class=editlink><img src='img/edit.png'></img></a>&nbsp;$w[LevelID]</td>";
    $tb .= "<td>$w[Nama]</td>";
    $tb .= "<td>$w[TabelUser]</td>";
    $tb .= "<td><img src='img/$w[NA].gif'></td>";
    $tb .= "</tr>";
    $n++;
  }
  $tb .= "</tbody>";
  $tb .= "</table>";
  echo $tb;
}

function edtLevel(){
  $md = (int) $_REQUEST['md'];
  
  if ($md == 1) {
    // Insert data level baru
    $w = array();
    $w['LevelID'] = '';
    $w['Nama'] = '';
    $w['TabelUser'] = 'karyawan';
    $w['NA'] = 'N';
    $jdl = "Add Level";
  } else {
    // Update Data level
    $level_id = (int) $_REQUEST['lvl'];
    $w = GetFields('level', "LevelID", $level_id, "*");
    $jdl = "Edit Level";
    $dis = "readonly=true";
  }
  $opttbl = GetOption2('tblusr', "concat(TblUsr, ' - ', Nama)", 'Nama', $w['TabelUser'], "", 'TblUsr');
  $na = ($w['NA'] == 'Y')? 'checked' : '';
  echo "<p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' name='data' method=POST>
  <input type=hidden name='md' value='$md'>
  <input type=hidden name='Levelid' value='$w[LevelID]'>
  <input type=hidden name='mnux' value='syslev'>
  <input type=hidden name='gos' value='lvlSav'>

  <tr><th colspan=3 class=ttl>$jdl</th></tr>
  <tr><td class=inp1>Level ID</td><td class=ul><input $dis type=text name='LevelID' value='$w[LevelID]'></td></tr>
  <tr><td class=inp1>Nama Level</td><td class=ul><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  <tr><td class=inp1>Table User</td><td class=ul><select name='TabelUser'>$opttbl/select></td></tr>
  <tr><td class=inp1>NA (tdk aktif)</td><td class=ul><input type=checkbox name='NA' value='Y' $na></td></tr>
  <tr><td colspan=2><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?mnux=syslev'\"></td></tr>
  </form></table></p>";
}

function lvlSav(){
  $md = $_REQUEST['md'];
  $LevelID = $_REQUEST['LevelID'];
  $TableUser = $_REQUEST['TableUser'];
  $Nama = sqling($_REQUEST['Nama']);
  $NA = (!empty($_REQUEST['NA']))? $_REQUEST['NA'] : 'N';
  
  // Simpan
  if ($md == 0) {
    $s = "update level set Nama='$Nama', NA='$NA'
      where LevelID='$LevelID'";
  }
  else {
    /*$s = "insert into level (LevelID, Nama, Script, LevelID, Web, CS,
      Author, EmailAuthor, Simbol, Help, NA, Keterangan)
      values ('$MdlGrpID', '$Nama', '$Script', '$LevelID', '$Web', '$CS',
      '$Author', '$EmailAuthor', '$Simbol', '$Help', '$NA', '$Keterangan')";*/ 
  }
  _query($s);
  DftrLevel();
}

// *** Parameters ***
$gos = (empty($_REQUEST['gos']))? 'DftrLevel' : $_REQUEST['gos'];

// *** Main ***
TampilkanJudul("Level User");
$gos();
?>
