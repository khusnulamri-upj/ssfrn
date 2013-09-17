<?php

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Cari Mahasiswa");

// *** Parameters ***
$frm = GetSetVar('frm');
$div = GetSetVar('div');
$NamaMhsw = GetSetVar('NamaMhsw');
$NPM = GetSetVar('NPM');

// cek Nama Dosen dulu
if (empty($NamaMhsw) && empty($NPM))
  die(ErrorMsg('Error', 
    "Masukkan terlebih dahulu NPM & Nama Mahasiswa sebagai kata kunci pencarian.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    Opsi: <a href='#' onClick=\"javascript:toggleBox('$div', 0)\">Tutup</a>"));

// *** Main ***
TampilkanJudul("Cari Mahasiswa<br /><font size=-1><a href='#' onClick=\"toggleBox('$div', 0)\">(&times; Close &times;)</a></font>");
TampilkanDaftar();

// *** Functions ***
function TampilkanDaftar() {
  $s = "select m.MhswID, m.Nama as NamaMhsw, m.TahunID, m.NA
    from mhsw m
    where m.NA = 'N'
      and m.MhswID like '%$_SESSION[NPM]%'
      and m.Nama like '%$_SESSION[NamaMhsw]%'
    order by m.Nama";
  $r = _query($s); $i = 0;
  
  echo "<table class=bsc cellspacing=1 width=100%>";
  echo "<tr>
    <th class=ttl>#</th>
    <th class=ttl>NIM</th>
    <th class=ttl>Nama Mahasiswa</th>
    <th class=ttl>NA</th>
    </tr>";
  while ($w = _fetch_array($r)) {
    $i++;
    if ($w['NA'] == 'Y') {
      $c = "class=nac";
      $d = "$w[Nama] <sup>$w[Gelar]</sup>";
    }
    else {
      $c = "class=ul";
      $d = "<a href=\"javascript:$_SESSION[frm].NPM.value='$w[MhswID]';$_SESSION[frm].NamaMhsw.value='$w[NamaMhsw]';toggleBox('$_SESSION[div]', 0)\">
        &raquo;
        $w[NamaMhsw]</a>
        <sup>$w[Gelar]</sup>";
    }
    echo <<<SCR
      <tr>
      <td class=inp width=20>$i</td>
      <td $c width=100 align=center>$w[MhswID]</td>
      <td $c>$d</td>
      <td class=ul width=20 align=center><img src='../img/book$w[NA].gif' /></td>
      </tr>
SCR;
  }
  echo "</table>";
}

?>

</BODY>
</HTML>
