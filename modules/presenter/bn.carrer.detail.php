<?php
// Author: Emanuel Setio Dewo
// 31 May 2006
// www.sisfokampus.net

// *** functions ***
function TampilkanMenuBN() {
  $arrMenuBN = array('Data Perusahan->perusahaanEdt'
    //'Akademik->AlumniAkd',
    //'Pekerjaan->AlumniKrj'
  );
  
  echo "<p><table class=menu cellspacing=1 cellpadding=4><tr>";
  for ($i = 0; $i < sizeof($arrMenuBN); $i++) {
    $mn = explode('->', $arrMenuBN[$i]);
    $c = ($mn[1] == $_SESSION['BNsub'])? 'class=menuaktif' : 'class=menuitem';
    echo "<td $c><a href='?mnux=bn.carrer.det&bnsub=$mn[1]&PerusahaanID=$_SESSION[PerusahaanIDID]'>$mn[0]</a></td>";
  }
  echo "</tr></table></p>";
}
// Header untuk alumni
function TampilkanHeaderBN($w) {
  echo "<p><table class=box>
  <tr><td class=ul colspan=2><b>$w[KodeID]</td></tr>
  <tr><td class=inp>Perusahaan ID</td>
      <td class=ul>$w[PerusahaanID]</td>
      <td class=inp>Alamat</td>
      <td class=ul>$w[Alamat]</td>
      </tr>
  <tr><td class=inp>Kota</td>
      <td class=ul>$w[Kota] &nbsp;</td>
      <td class=inp>Telepon</td>
      <td class=ul>$w[Telepon] &nbsp;</td>
      </tr>
  </table></p>";
}
// Edit data pribadi Alumni
function BNEdt($w) {
  $a = GetFields('perusahaan', 'PerusahaanID', $w['PerusahaanID'], '*');
  if (empty($a)) {
    $s = "insert into alumni (MhswID, Gelar,
      Alamat, Kota, KodePos, RT, RW,
      Propinsi, Negara,
      Telepon, Handphone, Email,
      TanggalBuat, LoginBuat)
      values ('$w[MhswID]', '',
      '$w[Alamat]', '$w[Kota]', '$w[KodePos]', '$w[RT]', '$w[RW]',
      '$w[Propinsi]', '$w[Negara]',
      '$w[Telepon]', '$w[Handphone]', '$w[Email]',
      now(), '$_SESSION[_Login]')";
    $r = _query($s);
    $a = GetFields('alumni', 'MhswID', $w['MhswID'], '*');
  }
  // Tampilkan data
  echo "<p><table class=bsc>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='alumni.det'>
  <input type=hidden name='slnt' value='alumni.sav'>
  <input type=hidden name='slntx' value='AlumniEdtSav'>
  <input type=hidden name='alumnisub' value='AlumniEdt'>
  <input type=hidden name='AlumniID' value='$w[MhswID]'>
  <tr><td class=ul colspan=2><b>Alamat Sekarang</b></td></tr>
  <tr><td class=inp>Alamat</td>
      <td class=ul><textarea name='Alamat' cols=30 rows=2>$a[Alamat]</textarea></td>
      </tr>
  <tr><td class=inp>RT/RW</td>
      <td class=ul><input type=text name='RT' value='$a[RT]' size=5 maxlength=5>/
      <input type=text name='RW' value='$a[RW]' size=5 maxlength=5></td>
      </tr>
  <tr><td class=inp>Kota</td>
      <td class=ul><input type=text name='Kota' value='$a[Kota]' size=30 maxlength=30></td>
      </tr>
  <tr><td class=inp>Propinsi</td>
      <td class=ul><input type=text name='Propinsi' value='$a[Propinsi]' size=30 maxlength=30></td>
      </tr>
  <tr><td class=inp>Negara</td>
      <td class=ul><input type=text name='Negara' value='$a[Negara]' size=30 maxlength=30></td>
      </tr>
  
  <tr><td class=ul colspan=2><b>Contact</b></td></tr>
  <tr><td class=inp>Email Pribadi</td>
      <td class=ul><input type=text name='Email' value='$a[Email]' size=50 maxlength=100></td>
      </tr>
  <tr><td class=inp># Telepon</td>
      <td class=ul><input type=text name='Telepon' value='$a[Telepon]' size=30 maxlength=50></td>
      </tr>
  <tr><td class=inp># Handphone</td>
      <td class=ul><input type=text name='Handphone' value='$a[Handphone]' size=30 maxlength=50></td>
      </tr>
  <tr><td class=ul colspan=2><input type=submit name='Simpan' value='Simpan'>
      <input type=reset name='Reset' value='Reset'></td></tr>
  </table></p>";
}


// *** Parameters ***
$PerusahaanID = GetSetVar('PerusahaanID');
$AlumniSUB = GetSetVar('alumnisub', 'AlumniEdt');

// *** Main ***
TampilkanJudul("Detail Perusahaan");
$alumni = GetFields("mhsw m
  left outer join program prg on m.ProgramID=prg.ProgramID
  left outer join prodi prd on m.ProdiID=prd.ProdiID", 
  "m.MhswID", $AlumniID, 
  "m.*, prg.Nama as PRG, prd.Nama as PRD");
if (!empty($alumni)) {
  TampilkanMenuAlumni();
  TampilkanHeaderAlumni($alumni);
  $AlumniSUB($alumni);
}
else echo ErrorMsg("Data Tidak Ditemukan",
  "Data alumni tidak ditemukan. Hubungi bagian BAA atau SIM untuk informasi lebih lanjut");
?>
