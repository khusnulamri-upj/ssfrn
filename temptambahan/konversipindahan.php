<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 14 Nov 2008

// *** Parameters ***
$MhswID = GetSetVar('MhswID');
$mhsw = GetFields('mhsw', "MhswID = '$MhswID' and KodeID", KodeID, "*");

// *** Main ***
TampilkanJudul("Konversi Matakuliah Mahasiswa Pindahan");
TampilkanAmbilMhswID($MhswID, $mhsw);

if ($MhswID == '') {
  echo Konfirmasi("Masukkan Parameter",
    "Masukkan NIM/NPM dari Mahasiswa pindahan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
}
// Cek apakah mahasiswanya ketemu atau tidak
elseif (empty($mhsw)) {
  echo ErrorMsg("Error",
    "Mahasiswa dengan NIM/NPM: <b>$MhswID</b> tidak ditemukan.<br />
    Masukkan NIM/NPM yang sebenarnya.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
}
/* sementara ditutup utk proses clustering di BinaInsani
elseif ($mhsw['Keluar'] == 'Y') {
  echo ErrorMsg("Error",
    "Mahasiswa dengan NIM/NPM: <b>$MhswID</b> telah keluar/lulus.<br />
    Anda sudah tidak dapat mengubah konversi.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut.");
}
*/
else {
  // Cek apakah punya hak akses terhadap mhsw dari prodi ini?
  if (strpos($_SESSION['_ProdiID'], $mhsw['ProdiID']) === false) {
    echo ErrorMsg("Error",
      "Anda tidak memiliki hak akses terhadap mahasiswa ini.<br />
      Mahasiswa: <b>$MhswID</b>, Prodi: <b>$mhsw[ProdiID]</b>.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.");
  }
  // hak akses oke
  else {
    // Cek apakah mahasiswa pindahan atau bukan
    if ($mhsw['StatusAwalID'] == 'P' || $mhsw['StatusAwalID'] == 'D') {
      $gos = (empty($_REQUEST['gos']))? 'DftrMK' : $_REQUEST['gos'];
      $gos($MhswID, $mhsw);
    }
    // Jika bukan, maka tampilkan pesan error
    else {
      echo ErrorMsg("Error",
        "Mahasiswa ini bukan mahasiswa pindahan/drop-in.<br />
        Anda tidak bisa melakukan konversi pindaha.
        <hr size=1 color=silver />
        Hubungi Sysadmin untuk informasi lebih lanjut.");
    }
  }
}

// *** Functions ***
function TampilkanAmbilMhswID($MhswID, $mhsw) {
  $stawal = GetaField('statusawal', 'StatusAwalID', $mhsw['StatusAwalID'], 'Nama');
  $status = GetaField('statusmhsw', 'StatusMhswID', $mhsw['StatusMhswID'], 'Nama');
  if (empty($mhsw['PenasehatAkademik'])) {
    $pa = '<sup>Belum diset</sup>';
  }
  else {
    $dosenpa = GetFields('dosen', "Login='$mhsw[PenasehatAkademik]' and KodeID", KodeID, "Nama, Gelar");
    $pa = "$dosenpa[Nama] <sup>$dosenpa[Gelar]</sup>";
  } 
    
  echo <<<ESD
  <table class=box cellspacing=1 align=center width=600>
  <form name='frmMhsw' action='?' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='gos' value='' />
  
  <tr><td class=wrn width=2 rowspan=4></td>
      <td class=inp width=80>NIM/NPM:</td>
      <td class=ul width=200>
        <input type=text name='MhswID' value='$MhswID' size=20 maxlength=50 />
        <input type=submit name='btnCari' value='Cari' />
        </td>
      <td class=inp width=80>Mahasiswa:</td>
      <td class=ul>$mhsw[Nama]&nbsp;</td>
      </tr>
  <tr><td class=inp>Status Mhsw:</td>
      <td class=ul>$status <sup>$stawal</sup></td>
      <td class=inp>Dosen PA:</td>
      <td class=ul>$pa</td>
  </form>
  </table>
ESD;
}
function DftrMK($MhswID, $mhsw) {
  $s = "select k.*
    from krs k
      left outer join khs h on h.KHSID = k.KHSID and h.KodeID = '".KodeID."'
    where k.MhswID = '$MhswID'
    order by k.TahunID, k.MKKode";
  $r = _query($s); $_tahun = 'alksdjfasdf-asdf';
  echo <<<ESD
  <table class=box cellspacing=1 width=600 align=center>
ESD;

  echo "<tr>
        <td class=ul1 colspan=10>
          <input type=button name='btnTambah' value='+ Tambah MK' onClick=\"javascript:fnEditKonversi(1, '$mhsw[MhswID]', '', 0)\" />
        </td></tr>";
  $hdr = "<tr><th class=ttl width=20>Nmr</th>
    <th class=ttl width=90>Kode</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl width=30>SKS</th>
    <th class=ttl width=30>Nilai</th>
    <th class=ttl width=30>Edit</th>
    </tr>";
  while ($w = _fetch_array($r)) {
    if ($_tahun != $w['TahunID']) {
      $_tahun = $w['TahunID'];
      echo "<tr>
        <td class=ul1 colspan=10>
          <font size=+1>$_tahun</font>
          <!--<input type=button name='btnTambah' value='+ Tambah MK' onClick=\"javascript:fnEditKonversi(1, '$w[MhswID]', '$_tahun', 0)\" />-->
        </td></tr>";
      echo $hdr;
      $n = 0;
    }
    $n++;
    if ($w['Setara'] == 'Y') {
      $btnEdit = "<input type=button name='btnEdit' value='»'
        onClick=\"fnEditKonversi(0, '$w[MhswID]', '$w[TahunID]', $w[KRSID])\" />";
    }
    else {
      $btnEdit = "<abbr title='Bukan Konversi'><img src='img/flag2.gif' /></abbr>";
    }
    echo <<<ESD
    <tr>
      <td class=inp>$n</td>
      <td class=ul>$w[MKKode]</td>
      <td class=ul>$w[Nama]</td>
      <td class=ul align=right>$w[SKS]</td>
      <td class=ul align=center>$w[GradeNilai]</td>
      <td class=ul align=center>
        $btnEdit
        </td>
    </tr>
ESD;
  }
  RandomStringScript();
  echo <<<ESD
  </form>
  </table>
  
  <script>
  function fnEditKonversi(md, mhsw, thn, id) {
      var _rnd = randomString();
      lnk = "$_SESSION[mnux].edit.php?mhsw="+mhsw+"&md="+md+"&id="+id+"&thn="+thn+"&_rnd="+_rnd+"&ProdiID=$mhsw[ProdiID]";
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
?>
