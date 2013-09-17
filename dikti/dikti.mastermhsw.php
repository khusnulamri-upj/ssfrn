<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 08/10/2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Master Mahasiswa");

// *** Parameters ***
$TahunID = GetSetVar('TahunID');
$ProdiID = GetSetVar('ProdiID');

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'Satu' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function Satu() {
  echo <<<ESD
  <font size=+1>Master Mahasiswa</font> <sup>MSMHS</sup><br />
  <table class=box cellspacing=1 width=100%>
  <tr>
      <td>Anda akan mendownload data master Mahasiswa. 
      Sistem hanya akan memproses data dosen yang aktif saja.<br />
      Tekan tombol proses untuk memulai mengekspor data master Mahasiswa. 
      </td>
      <td width=100 valign=top align=right>
      <input type=button name='Proses' value='Proses'
        onClick="location='../$_SESSION[mnux].mastermhsw.php?gos=Proses'" />
      </td>
      </tr>
  </table>
  <br />
ESD;
}
function Proses() {
  // Buat DBF
  include_once "../$_SESSION[mnux].header.dbf.php";
  include_once "../func/dbf.function.php";
  $NamaFile = "../tmp/MSMHS_$_SESSION[TahunID].DBF";
  $_SESSION['mmhsw_dbf'] = $NamaFile;
  $_SESSION['mmhsw_part'] = 0;
  $_SESSION['mmhsw_counter'] = 0;
  $_SESSION['mmhsw_total'] = HitungData();
  if (file_exists($NamaFile)) unlink($NamaFile);
  DBFCreate($NamaFile, $HeaderMasterMhsw);
  // tampilkan
  $ro = "readonly=true";
  echo <<<ESD
  <font size=+1>Proses Master Mahasiswa...</font> (<b>$_SESSION[mmhsw_total]</b> data)<br />
  <table class=box cellspacing=1 width=100%>
  <form name='frmMhsw'>
  <tr>
      <td valign=top width=10>
      Counter:<br />
      <input type=text name='Counter' size=4 $ro />
      </td>
      
      <td valign=top width=20>
      NIP:<br />
      <input type=text name='MhswID' size=10 $ro />
      </td>
      
      <td valign=top>
      Nama Dosen:<br />
      <input type=text name='NamaMhsw' size=30 $ro />
      </td>
      
      <td valign=top align=right width=30>
      <input type=button name='Batal' value='Batal'
        onClick="location='../$_SESSION[mnux].mastermhsw.php?gos='" />
      </td>
      </tr>
  </form>
  </table>
  <br />
  
  <script>
  function Kembali() {
    window.onLoad=setTimeout("window.location='../$_SESSION[mnux].mastermhsw.php?gos=Selesai'", 0);
  }
  function Prosesnya(cnt, id, nama) {
    frmMhsw.Counter.value = cnt;
    frmMhsw.MhswID.value = id;
    frmMhsw.NamaMhsw.value = nama;
  }
  </script>
  <iframe src="../$_SESSION[mnux].mastermhsw.php?gos=ProsesDetails" width=90% height=50 frameborder=0 scrolling=no>
  </iframe>

ESD;
}
function HitungData() {
  $_prodi = (empty($_SESSION['ProdiID']))? '' : "and m.ProdiID='$_SESSION[ProdiID]'";
  $jml = GetaField("mhsw m",
    "m.NA='N' $_prodi and m.KodeID", KodeID, "count(m.MhswID)")+0;
  return $jml; 
}
function ProsesDetails() {
  $max = $_SESSION['parsial'];
  $tot = $_SESSION['mmhsw_total'];
  $n = $_SESSION['mmhsw_part'];
  $_dari = $n * $max;
  $_sampai = (($n + 1) * $max) -1;
  
  // Ambil data
  $_prodi = (empty($_SESSION['ProdiID']))? '' : "and m.MhswID='$_SESSION[ProdiID]' ";
  $s = "select m.*,
      p.ProdiDiktiID, p.JenjangID,
      date_format(m.TanggalLahir, '%Y%m%d') as _TanggalLahir
    from mhsw m
      left outer join prodi p on p.ProdiID = m.ProdiID and m.KodeID='".KodeID."'
    where m.NA = 'N'
      and m.KodeID = '".KodeID."'
      $_prodi
    order by m.Login
    limit $_dari, $max";
  $r = _query($s);
  $jml = _num_rows($r);
  
  if ($jml > 0) {
    $_p = ($tot > 0)? $_SESSION['mmhsw_counter']/$tot*100 : 0;
    $__p = number_format($_p);
    $_s = 100 -$_p;
    $h = "height=20";
    echo "<img src='../img/B1.jpg' width=1 $h /><img src='../img/B2.jpg' width=$_p $h /><img src='../img/B3.jpg' width=$_s $h /><img src='../img/B1.jpg' width=1 $h /> <sup>&raquo; $__p%</sup>";

    while ($w = _fetch_array($r)) {
      $_SESSION['mmhsw_counter']++;
      echo "<script>self.parent.Prosesnya($_SESSION[mmhsw_counter], '$w[MhswID]', '$w[Nama]');</script>";
      
      // Masukkan data
      include_once "../$_SESSION[mnux].header.dbf.php";
      include_once "../func/dbf.function.php";
      $NamaFile = $_SESSION['mmhsw_dbf'];
      
      $Kelamin = ($w['KelaminID'] == 'W')? 'P' : 'L';
      $dt = array(
        $_SESSION['KodePTI'],
        $w['ProdiDiktiID'],
        $w['JenjangID'],
        $w['MhswID'],
        $w['Nama'],
        $w['TempatLahir'],
        $w['_TanggalLahir'],
        $Kelamin,
        $w['TahunID'],
        $w['TahunID'].'1',
        $w['BatasStudi'],
        $w['AsalPT'],
        $w['TglSKMasuk'],
        $w['TanggalLulus'],
        $w['StatusMhswID'],
        $StatusAwal,
        $w['TotalSKSPindah'],
        $w['MhswIDAsalPT'],
        $w['AsalPT'],
        $KodeJenjangPT,
        $w['ProdiAsalPT'],
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
        );
      InsertDataDBF($NamaFile, $dt);
    }
    $_SESSION['mmhsw_part']++;
    // reload
    echo "<script>
    window.onLoad=setTimeout(\"window.location='../$_SESSION[mnux].mastermhsw.php?gos=ProsesDetails'\", $_SESSION[Timer]);
    </script>
    ";

  }
  else { // Selesai
    echo "<script>self.parent.Kembali();</script>";
  }
}
function Selesai() {
  echo <<<ESD
  <font size=+1>Proses Master Mahasiswa Telah Selesai</font><br />
  <table class=box cellspacing=1 width=100%>
  <tr><td>
      Data mahasiswa yang berhasil diproses: <b>$_SESSION[mmhsw_counter]</b>.<br />
      Anda dapat mendownload file hasil proses dengan menekan tombol Download di bawah ini.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Download' value='Download File'
            onClick="location='$_SESSION[mmhsw_dbf]'" />
            <input type=button name='Kembali' value='Kembali'
            onClick="location='../$_SESSION[mnux].mastermhsw.php?gos='" />
      </td>
      </tr>
  </table>
ESD;
}
?>
</BODY>
</HTML>
