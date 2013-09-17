<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 28 Agustus 2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Presensi Mahasiswa", 1);

// *** Parameters ***
$pid = $_REQUEST['pid'];

// *** Main ***
TampilkanJudul("Presensi Mahasiswa");
$gos = (empty($_REQUEST['gos']))? 'DftrSiswa' : $_REQUEST['gos'];
$gos($pid);

// *** Functions ***
function DftrSiswa($pid) {
  $p = GetFields("presensi p
    left outer join jadwal j on p.JadwalID = j.JadwalID
    left outer join dosen d on d.Login = j.DosenID and d.KodeID='".KodeID."'
    left outer join hari h on h.HariID = date_format(p.Tanggal, '%w')
	left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID",
    "p.PresensiID", $pid,
    "p.*, j.MKKode, j.Nama, h.Nama as _HR,
    concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
    date_format(p.Tanggal, '%d-%m-%Y') as _Tanggal,
    left(p.JamMulai, 5) as _JM, left(p.JamSelesai, 5) as _JS,
	jj.Nama as _NamaJenisJadwal, jj.Tambahan");
  TampilkanHeader($p);
  CekKRSMhsw($p);
  TampilkanPresensiMhsw($p);
}
function TampilkanHeader($p) {
  $TagTambahan = ($p['Tambahan'] == 'Y')? "<b>( $p[_NamaJenisJadwal] )</b>" : "";
  echo "<table class=box cellspacing=1 width=100%>
  <tr><td class=inp>Matakuliah:</td>
      <td class=ul>$p[Nama] $TagTambahan<sup>$p[MKKode]</sup></td>
      <td class=inp>Dosen:</td>
      <td class=ul>$p[DSN]</td>
      </tr>
  <tr>
      <td class=inp>Pertemuan:</td>
      <td class=ul>#$p[Pertemuan] &#8594; $p[_HR] $p[_Tanggal]
        </td>
      <td class=inp>Jam:</td>
      <td class=ul><sup>$p[_JM]</sup> &#8594; <sub>$p[_JS]</sub></td>
      </tr>
  </table>";
}
function CekKRSMhsw($p) {
  $def = GetFields('jenispresensi', 'Def', 'Y', '*');
  $s = "select KRSID, MhswID, JadwalID
    from krs
    where JadwalID = '$p[JadwalID]'
    order by MhswID";
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    $ada = GetFields('presensimhsw', "PresensiID = '$p[PresensiID]' and KRSID", $w['KRSID'], '*');
    if (empty($ada)) {
      $sp = "insert into presensimhsw
        (JadwalID, KRSID, PresensiID, 
        MhswID, JenisPresensiID, Nilai, NA)
        values
        ($p[JadwalID], $w[KRSID], $p[PresensiID],
        '$w[MhswID]', '$def[JenisPresensiID]', '$def[Nilai]', 'N')";
      $rp = _query($sp);
      // Hitung KRS
      $jml = GetaField('presensimhsw', 'KRSID', $w['KRSID'], "sum(Nilai)")+0;
      $sk = "update krs
        set _Presensi = $jml
        where KRSID = $w[KRSID]";
      $rk = _query($sk);
    }
  }
}
function TampilkanPresensiMhsw($p) {
  $s = "select pm.*, mhsw.Nama
    from presensimhsw pm
      left outer join mhsw on mhsw.MhswID = pm.MhswID and mhsw.KodeID = '".KodeID."'
    where pm.PresensiID = '$p[PresensiID]'
    order by pm.MhswID";
  $r = _query($s);
  $def = GetFields('jenispresensi', 'Def', 'Y', '*');
  $opt0 = GetOption2('jenispresensi', "Nama", 'JenisPresensiID', $def['JenisPresensiID'], '', 'JenisPresensiID');
  
  echo "<table class=box cellspacing=1 width=100%>";
  echo "<script>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]&gos=Edit&JadwalID=$p[JadwalID]';
    self.close();
    return false;
  }
  </script>";
  echo "<tr>
    <form action='../$_SESSION[mnux].mhswedit.php' method=POST>
    <input type=hidden name='gos' value='SimpanSemua' />
    <input type=hidden name='pid' value='$p[PresensiID]' />
    
    <td class=ul colspan=5>Set semua ke:
    <select name='Stt'>$opt0</select>
    <input type=submit name='SetStt' value='Set Status' />
    <input type=button name='Refresh' value='Refresh' 
      onClick=\"location='../$_SESSION[mnux].mhswedit.php?pid=$p[PresensiID]'\" />
    <input type=button name='Tutup' value='Tutup' onClick=\"ttutup()\" />
    </td>
    
    </form>
    </tr>";
  $n = 0;
  $arr = GetArrPre();
  while ($w = _fetch_array($r)) {
    $n++;
    $optpre = GetOptPre($arr, $w['JenisPresensiID']);
    echo "
      <tr><td class=inp width=10>$n</td>
          <td class=inp1 width=94><b>$w[MhswID]</b></td>
          <td class=ul1 width=260>$w[Nama]</td>
          <td class=ul><select id='PresensiMhsw_$w[PresensiMhswID]'
            onChange='javascript:SetPresensiMhsw($w[PresensiMhswID])'>$optpre</select></td>
      </tr>";
  }
  echo <<<SCR
  </table>
  <script>
  function SetPresensiMhsw(id) {
    var status = document.getElementById("PresensiMhsw_"+id).value;
    lnk = "../$_SESSION[mnux].mhswedit.save.php?id="+id+"&st="+status;
    win2 = window.open(lnk, "", "width=0, height=0, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
function SimpanSemua($pid) {
  $Stt = sqling($_REQUEST['Stt']);
  $Nilai = GetaField('jenispresensi', 'JenisPresensiID', $Stt, 'Nilai');
  $s = "select *
    from presensimhsw
    where PresensiID = '$pid' ";
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    // update
    $s0 = "update presensimhsw set JenisPresensiID = '$Stt', Nilai = '$Nilai'
      where PresensiMhswID = $w[PresensiMhswID]";
    $r0 = _query($s0);
    // Hitung & update ke KRS
    $jml = GetaField('presensimhsw', 'KRSID', $w['KRSID'], "sum(Nilai)")+0;
    // Update KRS
    $s1 = "update krs
      set _Presensi = $jml
      where KRSID = $w[KRSID]";
    $r1 = _query($s1);
  }
  BerhasilSimpan("../$_SESSION[mnux].mhswedit.php?pid=$pid", 1);
}
function GetOptPre($arr, $id) {
  $opt = '';
  foreach($arr as $a) {
    $_a = explode('~', $a);
    $sel = ($id == $_a[0])? 'selected' : '';
    $opt .= "<option value='$_a[0]' $sel>$_a[1]</option>";
  }
  return $opt;
}
function GetArrPre() {
  $s = "select * from jenispresensi where NA='N' order by JenisPresensiID";
  $r = _query($s);
  $arr = array();
  $arr[] = '';
  while ($w = _fetch_array($r)) {
    $arr[] = "$w[JenisPresensiID]~$w[Nama]";
  }
  return $arr;
}
?>
