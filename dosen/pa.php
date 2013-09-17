<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 14 November 2008

// *** Parameters ***
$DosenID = $_SESSION['_Login'];
$dsn = GetFields('dosen', "Login='$DosenID' and KodeID", KodeID, "*");

// *** Main ***
TampilkanJudul("Penasehat Akademik: $dsn[Nama] <sup>$dsn[Gelar]</sup>");
if (empty($dsn))
  die(ErrorMsg("Error",
    "Anda tidak berhak mengakses menu ini.<br />
    Modul ini khusus untuk dosen.
    <hr size=1 color=silver />
    Hubungi Sysadmin untuk informasi lebih lanjut."));

$gos = (empty($_REQUEST['gos']))? 'DftrMhsw' : $_REQUEST['gos'];
$gos($dsn);

// *** Functions ***
function DftrMhsw($dsn) {
  $s = "select m.MhswID, m.Nama as NamaMhsw, m.TahunID,
      m.ProdiID
    from mhsw m
    where m.KodeID = '".KodeID."'
      and m.PenasehatAkademik = '$dsn[Login]'
    order by m.TahunID, m.MhswID";
  $r = _query($s); $n = 0;
  
  echo <<<ESD
  <p>
  <table class=box cellspacing=1 align=center width=600>
  <tr><td class=ul colspan=5>
      <input type=button name='btnCetakDaftar' value='Cetak Daftar Mahasiswa'
        onClick="javascript:fnCetakDaftar('$dsn[Login]')" />
      </td></tr>
  <tr><th class=ttl>Nmr</th>
      <th class=ttl>NIM/NPM</th>
      <th class=ttl>Nama Mahasiswa</th>
      <th class=ttl>Prodi</th>
      </tr>
ESD;
  while ($w = _fetch_array($r)) {
    $n++;
    echo "<tr>
      <td class=inp width=30>$n</td>
      <td class=ul1 width=100>$w[MhswID]</td>
      <td class=ul1>$w[NamaMhsw]</td>
      <td calss=ul1 width=100>$w[ProdiID]</td>
      </tr>";
  }
  echo "</table></p>";
  RandomStringScript();
  echo <<<ESD
    <script>
    <!--
    function fnCetakDaftar(dsn) {
      var _rnd = randomString();
      lnk = "$_SESSION[mnux].daftar.php?DosenID="+dsn+"&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
    }
    //-->
    </script>
ESD;
}
?>
