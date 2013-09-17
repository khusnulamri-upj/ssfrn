<?php
  // Author: Emanuel Setio Dewo
  // Start: 2005-12-04
  
function TampilkanLogin() {
  $col = 4;
  
  $s = "select * from level where Accounting = 'N' and NA='N' order by LevelID";
  $r = mysql_query($s) or die("Gagal: "+mysql_error());
  echo "<table class=bsc cellspacing=1 cellpadding=4 align=center>";
  echo "<tr>";
  $cnt = 0;
  while ($w = mysql_fetch_array($r)) {
    if ($cnt >= $col) {
      echo "</tr><tr>";
      $cnt = 0;
    }
    $cnt++;
    $simbol = (empty($w['Simbol']))? 'img/login.png' : $w['Simbol'];
    echo "<td class=bsc align=center valign=top width=100>
    <a href='?mnux=login&lgn=frm&lid=$w[LevelID]&nme=$w[Nama]' title='Login sebagai $w[Nama]'>
    <img src='$simbol' border=0><br>
    $w[Nama]</a></td>";
  }
  echo "</tr></table>";
}
function frm(){
  ResetLogin();
  global $arrID;
  $LabelLogin = ($_REQUEST['lid'] == 120)? "N.P.M" : "Kode Login";
  $CatatanCama = ($_REQUEST['lid'] == 33)? "Password default adalah tanggal lahir anda dengan format TTTT-BB-HH.<br>Contoh: Masukkan '1999-12-31' untuk tanggal lahir 31 Desember 1999" : "";
  //$institusi = GetOption2('identitas', 'Nama', 'Kode', $arrID['Kode'], '', 'Kode');
  $institusi = KodeID;
  $NamaInstitusi = GetaField('identitas', 'Kode', KodeID, 'Nama');
  $isifrm = "<table class=bsc cellspacing=0 cellpadding=4 width=100%>
  <form name='frmLogin' action='?' method=POST>
  <input type=hidden name='lid' value='$_REQUEST[lid]' />
  <input type=hidden name='mnux' value='loginprc' />
  <input type=hidden name='gos' value='cek' />
  <input type=hidden name='nme' value='$_REQUEST[nme]' />
  <input type=hidden name='institusi' value='$institusi' />
  <input type=hidden name='KodeID' value='".KodeID."' />
  <input type=hidden name='BypassMenu' value='1' />
  
  <tr><td class=inp>Institusi:</td>
      <td class=ul1 nowrap>$NamaInstitusi &nbsp;</td></tr>
  <tr><td class=inp>$LabelLogin:</td>
      <td class=ul1>
      <input type=text name='Login' value='' size=10 maxlength=20>
      </td></tr>
  <tr><td class=inp>Password:</td>
      <td class=ul1><input type=password name='Password' value='' size=10 maxlength=10>
					<br>$CatatanCama</td></tr>
  <tr><td class=ul1 colspan=3>
      <input type=submit name='Submit' value='Login' />
      <input type=reset name='Reset' value='Reset' />
      <input type=button name='Batal' value='Batal' onClick=\"location='?mnux='\" />
  </td></tr>
  </form></table>

  
  ";
  //<tr><td class=ul>Institusi</td><td class=ul><select name=institusi>$institusi</select></td></tr>
  echo Konfirmasi("Login: $_REQUEST[nme]", $isifrm);
}


// *** Main ***
//CHG KHUSNUL 20130911
//$lgn = (!empty($_REQUEST['lgn']))? $_REQUEST['lgn'] : 'TampilkanLogin';
$lgn = 'frm';
//CHG KHUSNUL 20130911 --END
$lgn();

?>
