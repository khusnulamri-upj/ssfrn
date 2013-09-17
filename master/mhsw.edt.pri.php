<?php
// Author: Emanuel Setio Dewo
// 28 Feb 2006

// *** Main ***
$sub = (empty($_REQUEST['sub']))? 'frmPribadi' : $_REQUEST['sub'];
$sub();

// *** Functions ***
function frmPribadi() {
  global $datamhsw, $mnux, $pref;
  $TanggalLahir = GetDateOption($datamhsw['TanggalLahir'], 'TanggalLahir');
  //GetRadio($_sql, $_name, $_disp, $_key, $_default='', $_pisah='<br>') {
  $Kelamin = GetRadio("select Kelamin, Nama from kelamin order by Kelamin",
    'Kelamin', 'Nama', 'Kelamin', $datamhsw['Kelamin']);
  $WargaNegara = GetRadio("select WargaNegara, Nama from warganegara order by WargaNegara",
    'WargaNegara', 'Nama', 'WargaNegara', $datamhsw['WargaNegara']);
  $Agama = GetOption2('agama', "concat(Agama, ' - ', Nama)", "Agama", $datamhsw['Agama'], '', 'Agama');
  $StatusSipil = GetOption2('statussipil', "concat(StatusSipil, ' - ', Nama)", "StatusSipil", $datamhsw['StatusSipil'], '', 'StatusSipil');
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=600>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]'>
  <input type=hidden name='BypassMenu' value='1' />
  <input type=hidden name='submodul' value='pri' />
  <input type=hidden name='sub' value='PribadiSav' />
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]'>

  <tr><td class=ul colspan=2><b>Sesuai dengan KTP atau identitas resmi lain</b></td></tr>
  <tr><td class=inp width=100>Nama</td>
      <td class=ul><input type=text name='Nama' value='$datamhsw[Nama]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Tempat Lahir</td>
      <td class=ul><input type=text name='TempatLahir' value='$datamhsw[TempatLahir]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Tanggal Lahir</td>
      <td class=ul>$TanggalLahir</td></tr>
  <tr><td class=inp>Jenis Kelamin</td>
      <td class=ul>$Kelamin</td></tr>
  <tr><td class=inp rowspan=2>Warga Negara</td>
      <td class=ul>$WargaNegara</td></tr>
  <tr><td class=ul>Jika asing, sebutkan: <input type=text name='Kebangsaan' value='$datamhsw[Kebangsaan]' size=20 maxlength=50></td></tr>
  <tr><td class=inp>Agama</td>
      <td class=ul><select name='Agama'>$Agama</select></td></tr>
  <tr><td class=inp>Status Sipil</td>
      <td class=ul><select name='StatusSipil'>$StatusSipil</select></td></tr>

  <tr><td class=inp>Alamat</td>
      <td class=ul><input type=text name='Alamat' value='$datamhsw[Alamat]' size=50 maxlength=200></td></tr>
  <tr><td class=inp>RT</td><td class=ul><input type=text name='RT' value='$datamhsw[RT]' size=10 maxlength=5>
      RW <input type=text name='RW' value='$datamhsw[RW]' size=10 maxlength=5></td></tr>
  <tr><td class=inp>Kota/Kabupaten</td>
      <td class=ul><input type=text name='Kota' value='$datamhsw[Kota]' size=20 maxlength=50></td>
      </tr>
  <tr><td class=inp>Kode Pos</td>
      <td class=ul><input type=text name='KodePos' value='$datamhsw[KodePos]' size=20 maxlength=50></td></tr>
  <tr><td class=inp>Propinsi</td>
      <td class=ul><input type=text name='Propinsi' value='$datamhsw[Propinsi]' size=40 maxlength=50></td></tr>
  <tr><td class=inp>Negara</td>
      <td class=ul><input type=text name='Negara' value='$datamhsw[Negara]' size=40 maxlength=50></td></tr>

  <tr><td class=inp>Telepon</td><td class=ul><input type=text name='Telepon' value='$datamhsw[Telepon]' size=20 maxlength=50>
      Handphone <input type=text name='Handphone' value='$datamhsw[Handphone]' size=20 maxlength=50></td></tr>
  <tr><td class=inp>E-mail</td>
      <td class=ul><input type=text name='Email' value='$datamhsw[Email]' size=50 maxlength=50></td></tr>
  <tr><td colspan=2 align=center><input type=submit name='Simpan' value='Simpan'>
    <input type=reset value='Reset'></td></tr>
  </form></table></p>";
}
function PribadiSav() {
  $Nama = sqling($_REQUEST['Nama']);
  $TempatLahir = sqling($_REQUEST['TempatLahir']);
  $TanggalLahir = "$_REQUEST[TanggalLahir_y]-$_REQUEST[TanggalLahir_m]-$_REQUEST[TanggalLahir_d]";
  $Kebangsaan = sqling($_REQUEST['Kebangsaan']);
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $KodePos = sqling($_REQUEST['KodePos']);
  $RT = sqling($_REQUEST['RT']);
  $RW = sqling($_REQUEST['RW']);
  $Propinsi = sqling($_REQUEST['Propinsi']);
  $Negara = sqling($_REQUEST['Negara']);
  $Telepon = sqling($_REQUEST['Telepon']);
  $Handphone = sqling($_REQUEST['Handphone']);
  $Email = sqling($_REQUEST['Email']);
  // Simpan
  $s = "update mhsw set Nama=upper('$Nama'),
    TempatLahir='$TempatLahir', TanggalLahir='$TanggalLahir',
    Agama='$_REQUEST[Agama]', StatusSipil='$_REQUEST[StatusSipil]',
    Kelamin='$_REQUEST[Kelamin]', WargaNegara='$_REQUEST[WargaNegara]', Kebangsaan='$Kebangsaan',
    Alamat='$Alamat', RT='$RT', RW='$RW',
    Kota='$Kota', KodePos='$KodePos', Propinsi='$Propinsi', Negara='$Negara',
    Telepon='$Telepon', Handphone='$Handphone', Email='$Email'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = _query($s);
  BerhasilSimpan("?mnux=$_SESSION[mnux]&submodul=pri", 100);
}

?>
