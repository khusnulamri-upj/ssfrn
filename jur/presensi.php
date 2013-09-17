<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 27 Agustus 2008

// *** Parameters ***
$TahunID = GetSetVar('TahunID');
$ProdiID = GetSetVar('ProdiID');
$ProgramID = GetSetVar('ProgramID');
$HariID = GetSetVar('HariID');
// *** Main ***
TampilkanJudul("Presensi Dosen & Mahasiswa");
$gos = (empty($_REQUEST['gos']))? 'DftrJadwal' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function TampilkanHeaderPresensi() {
  //$optprodi = GetOption2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['ProdiID'], "KodeID='".KodeID."'", 'ProdiID');
  // Edit: Ilham
  $s = "select DISTINCT(TahunID) from tahun where KodeID='".KodeID."' order by TahunID DESC";
  $r = _query($s);
  $opttahun = "<option value=''></option>";
  while($w = _fetch_array($r)) {  
	  $ck = ($w['TahunID'] == $_SESSION['TahunID'])? "selected" : '';
      $opttahun .=  "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
  }

  $optprodi = ($_SESSION['_LevelID'] == 100)? 
     GetOption3("prodi", "ProdiID", "concat(ProdiID, ' - ', Nama) as NM", "NM",  $w['ProdiID'], '.') : 
	 GetProdiUser($_SESSION['_Login'], $_SESSION['ProdiID']);
  $optprg = GetOption2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['ProgramID'], "KodeID='".KodeID."'", 'ProgramID');
  $opthari = GetOption2('hari', 'Nama', 'HariID', $_SESSION['HariID'], '', 'HariID');
  $buttons = ($_SESSION['_LevelID'] == 100)? "" : 
	 "<input type=button name='CetakRekap' value='Cetak Rekap' onClick='javascript:CetakRekap()' />
      <input type=button name='CetakDetail' value='Cetak Detail Presensi' onClick=\"javascript:CetakDetail()\" />
      <input type=button name='CetakPresMhsw' value='Cetak Presensi Mhsw' onClick=\"javascript:CetakDetailMhsw()\" />"; 
  echo "<table class=box cellspacing=0 align=center width=600>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='gos' value='' />
  <tr><td class=wrn width=2 rowspan=4></td>
      <td class=inp>Thn Akd.:</td>
      <td class=ul1><select name='TahunID'/>$opttahun</select></td>
      <td class=inp>Program Studi:</td>
      <td class=ul1><select name='ProdiID'>$optprodi</select></td>
      </tr>
  <tr><td class=inp>Hari:</td>
      <td class=ul1><select name='HariID'>$opthari</select></td>
      <td class=inp>Prg Pendidikan:</td>
      <td class=ul1><select name='ProgramID'>$optprg</select>
        <input type=submit name='Tampilkan' value='Tampilkan Jadwal' align=right />
        </td>
      </tr>
  </form>
  <tr><td class=ul colspan=5>
      $buttons
      </td></tr>
  </table>";
echo <<<SCR
  <script>
  <!--
  function CetakRekap() {
    lnk = "$_SESSION[mnux].rekap.php";
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDetail() {
    lnk = "$_SESSION[mnux].detail.php";
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDetailMhsw() {
    lnk = "$_SESSION[mnux].mhsw.php";
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  //-->
  </script>
SCR;
}
function DftrJadwal() {
  TampilkanHeaderPresensi();
  if (empty($_SESSION['TahunID']) || empty($_SESSION['ProdiID']))
    echo Konfirmasi("Tahun Akademik & Program Studi",
      "Masukkan Tahun Akademik & Program Studi terlebih dahulu untuk dapat menampilkan jadwal kuliah.");
  else DftrJadwal1();
}
function DftrJadwal1() {
  // Filtering
  $whr_hari = ($_SESSION['HariID'] == '')? '' : "and j.HariID = '$_SESSION[HariID]' ";
  $whr_prg  = ($_SESSION['ProgramID'] == '')? '' : "and j.ProgramID = '$_SESSION[ProgramID]' ";
  $whr_dosen = ($_SESSION['_LevelID'] == 100) ? " and j.DosenID = '$_SESSION[_Login]' " : "";

    $s = "select j.*,
      left(j.JamMulai, 5) as _JM, left(j.JamSelesai, 5) as _JS,
      concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
      jj.Nama as _NamaJenisJadwal, jj.Tambahan,
	  mk.TugasAkhir, mk.PraktekKerja, k.Nama AS namaKelas
    from jadwal j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
	  left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID
	  left outer join mk mk on mk.MKID=j.MKID and mk.KodeID='".KodeID."' 
	  LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    where j.TahunID = '$_SESSION[TahunID]'
      and j.ProdiID = '$_SESSION[ProdiID]'
      and j.KodeID = '".KodeID."'
	  $whr_hari
      $whr_prg
	  $whr_dosen
      and j.NA = 'N'
    order by j.HariID , j.JamMulai, j.JamSelesai";

  $r = _query($s);
  $n = 0; $_hr = 'lasdjfalsjh';
  echo "<table class=box cellspacing=1 align=center width=900>";
  $PrintDaftar = ($_SESSION['_LevelID'] == 100)? '' : 
	"<th class=ttl title='Daftar Presensi Dosen'>DPD</th>
	<th class=ttl title='Daftar Presensi Mahasiswa'>DPM</th>";
  $hdr = "<tr>
    <th class=ttl>#</th>
    <th class=ttl>Jam</th>
    <th class=ttl>Kode MK</th>
    <th class=ttl>Mata Kuliah</th>
    <th class=ttl>SKS</th>
    <th class=ttl>Kelas</th>
    <th class=ttl>Dosen</th>
    <th class=ttl>Mhsw</th>
	$PrintDaftar
    <th class=ttl colspan=2>Presensi</th>
    </tr>";
  while ($w = _fetch_array($r)) {
    if ($_hr != $w['HariID']) {
      $_hr = $w['HariID'];
      $hari = GetaField('hari', 'HariID', $_hr, 'Nama');
      echo "<tr><td class=ul colspan=11><font size=+1>$hari</font> <sup>$_hr</sup></td></tr>";
      echo $hdr;
    }
    $n++;
    if ($w['Final'] == 'Y') {
      $c = 'class=nac';
      $edt = "<img src='img/lock.jpg' width=25 title='Sudah difinalisasi. Tidak dapat diubah.' />";
    }
    else {
      $c = 'class=ul';
      $edt = "<a href='#' onClick=\"location='?mnux=$_SESSION[mnux]&gos=Edit&JadwalID=$w[JadwalID]'\"><img src='img/edit.png' /></a>";
    }
	$PrintDaftar2 = ($_SESSION['_LevelID'] == 100)? '' : 
	    "<td $c align=center>
        <a href='#' onClick='javascript:CetakDAD($w[JadwalID], $w[SKS])' title='Daftar Presensi Dosen'><img src='img/printer2.gif' /></a>
        </td>
		<td $c align=center>
        <a href='#' onClick='javascript:CetakDHK($w[JadwalID], $w[SKS])' title='Daftar Presensi Mahasiswa'><img src='img/printer2.gif' /></a>
        </td>";
    $TagTambahan = ($w['Tambahan'] == 'Y')? "<b>( $w[_NamaJenisJadwal] )</b>" : "";
    echo "<tr>
      <td class=inp width=15>$n</td>
      <td $c><sup>$w[_JM]</sup>&#8594;<sub>$w[_JS]</sub></td>
      <td $c>$w[MKKode] <sup>$w[Sesi]</sup></td>
      <td $c>$w[Nama] $TagTambahan</td>
      <td $c align=right>$w[SKS]</td>
      <td $c>$w[namaKelas] <sup>$w[ProgramID]</sup></td>
      <td $c>$w[DSN]</td>
      <td $c align=right>$w[JumlahMhsw] <sup>&#2000;</sup></td>
	  $PrintDaftar2
      <td class=ul1 align=right>$w[Kehadiran]<sub>&times;</sub></td>
      <td class=ul align=center>
        $edt
        </td>
      </tr>";
  }
  echo <<<ESD
  </table>
  <p></p>
  
  <script>
  function CetakDHK(JadwalID, SKS) {
    lnk = "$_SESSION[mnux].dhk.php?JadwalID="+JadwalID+"&SKS="+SKS;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakDAD(JadwalID, SKS) {
    lnk = "$_SESSION[mnux].dad.php?JadwalID="+JadwalID+"&SKS="+SKS;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}
function Edit() {
  $JadwalID = GetSetVar('JadwalID');
  $jdwl = GetFields("jadwal j
    left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
    left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
    left outer join hari hr on j.HariID = hr.HariID
    left outer join hari hruas on hruas.HariID = date_format(j.UASTanggal, '%w')
    left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID 
	LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
	", 
    "j.JadwalID", $JadwalID,
    "j.*, concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
    prd.Nama as _PRD, hr.Nama as _HR, hruas.Nama as _HRUAS,
    LEFT(j.JamMulai, 5) as _JM, LEFT(j.JamSelesai, 5) as _JS,
    LEFT(j.UASJamMulai, 5) as _JMUAS, LEFT(j.UASJamSelesai, 5) as _JSUAS,
    date_format(j.UASTanggal, '%d-%m-%Y') as _UASTanggal,
	jj.Nama as _NamaJenisJadwal, jj.Tambahan, k.Nama AS namaKelas
    ");
  // Cek apakah jadwal valid?
  if (empty($jdwl)) 
    die(ErrorMsg('Error',
      "Jadwal tidak ditemukan.<br />
      Mungkin jadwal sudah dihapus.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\" >"));
  // Cek apakah sudah di-finalisasi?
  if ($jdwl['Final'] == 'Y')
    die(ErrorMsg('Error',
      "Jadwal sudah difinalisasi.<br />
      Anda sudah tidak dapat mengubah data ini lagi.
      <hr size=1 color=silver />
      <input type=button name='Kembali' value='Kembali' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\" >"));
  // Jika sudah valid semua, maka tampilkan menu edit yg sebenarnya
  Edits($jdwl);
}
function Edits($jdwl) {
  PresensiScript();
  TampilkanHeader($jdwl);
  TampilkanPresensi($jdwl);
}
function TampilkanPresensi($jdwl) {
  if($_SESSION['_LevelID'] == 100)
	if($jdwl['DosenID'] != $_SESSION['_Login'])
	   die(ErrorMsg("Anda tidak berhak mengakses data presensi dari Mata Kuliah: <b>$jdwl[Nama], Hari: $jdwl[_HRUAS], Jam: $jdwl[_JM] - $jdwl[_JS]</b>. 
					<br>Bila anda seharusnya berhak mengakses data ini, harap menghubungi Kepala Prodi."));
  
  $s = "select p.*,
    date_format(p.Tanggal, '%d-%m-%Y') as _Tanggal,
    date_format(p.Tanggal, '%w') as _Hari,
    d.Nama as DSN, d.Gelar,
    h.Nama as _HR,
    left(p.JamMulai, 5) as _JM, left(p.JamSelesai, 5) as _JS,
      (select sum(Nilai)
      from presensimhsw 
      where PresensiID=p.PresensiID) as JmlHadir
    from presensi p
      left outer join hari h on h.HariID = date_format(p.Tanggal, '%w')
      left outer join dosen d on d.Login = p.DosenID and d.KodeID = '".KodeID."'
    where p.JadwalID = '$jdwl[JadwalID]'
    order by p.Pertemuan";
  $r = _query($s);

  echo "<table class=box cellspacing=1 align=center width=800>";
  echo "<tr>
    <td class=ul1 colspan=6>
    <input type=button name='TambahPresensi' value='Tambah Presensi' 
      onClick=\"javascript:PrsnEdit(1, $jdwl[JadwalID], 0)\" />
    <input type=button name='Refresh' value='Refresh'
      onClick=\"location='?mnux=$_SESSION[mnux]&gos=Edit&JadwalID=$jdwl[JadwalID]'\" />
    <input type=button name='Kembali' value='Kembali ke Daftar'
      onClick=\"location='?mnux=$_SESSION[mnux]&gos='\" />
    </td></tr>";
  echo "<tr>
    <th class=ttl width=40 colspan=2>#</th>
    <th class=ttl width=120>Tanggal</th>
    <th class=ttl width=60>Jam</th>
    <th class=ttl>Dosen Pemberi Kuliah</th>
    <th class=ttl>Catatan</th>
    <th class=ttl width=50>Siswa<br />Hadir</th>
    </tr>";
  
  $n = 0;
  while ($w = _fetch_array($r)) {
    $n++;
    $Jumlah = $w['JmlHadir']+0;
    echo "<tr>
      <td class=inp width=20>$w[Pertemuan]</td>
      <td class=ul width=10 align=center><a href='#' onClick='javascript:PrsnEdit(0, $w[JadwalID], $w[PresensiID])'><img src='img/edit.png' /></a></td>
      <td class=ul>$w[_HR] <sup>$w[_Tanggal]</sup></td>
      <td class=ul align=center><sup>$w[_JM]</sup>&#8594;<sub>$w[_JS]</sub></td>
      <td class=ul>$w[DSN] <sup>$w[Gelar]</sup></td>
      <td class=ul>$w[Catatan]&nbsp;</td>
      <td class=ul align=right>
        $Jumlah
        <a href='#' onClick='javascript:PrsnMhswEdit($w[PresensiID])'><img src='img/edit.png' /></a>
        </td>
      
      </tr>";
  }
  
  echo "</table>";
}
function TampilkanHeader($jdwl) {
  $TagTambahan = ($jdwl['Tambahan'] == 'Y')? "<b>( $jdwl[_NamaJenisJadwal] )</b>" : "";
  echo "<table class=box cellspacing=0 align=center width=800>
  <tr><td class=inp width=100>Thn Akademik:</td>
      <td class=ul>$jdwl[TahunID]</td>
      <td class=inp width=100>Program Studi:</td>
      <td class=ul>$jdwl[_PRD] <sup>$jdwl[ProdiID]</sup></td>
      </tr>
  <tr><td class=inp>Matakuliah:</td>
      <td class=ul>$jdwl[Nama] $TagTambahan<sup>$jdwl[MKKode]</sup></td>
      <td class=inp>Dosen:</td>
      <td class=ul>$jdwl[DSN]</td>
      </tr>
  <tr><td class=inp>SKS:</td>
      <td class=ul>$jdwl[SKS], Peserta: $jdwl[JumlahMhsw] <sup title='Jumlah Mahasiswa'>&#2000;</sup></td>
      <td class=inp>Kelas:</td>
      <td class=ul>$jdwl[namaKelas] <sup>$jdwl[ProgramID]</sup></td>
      </tr>
  <tr><td class=inp>Jdwl Kuliah:</td>
      <td class=ul>$jdwl[_HR] <sup>$jdwl[_JM]</sup>&#8594;<sub>$jdwl[_JS]</sub></td>
      <td class=inp>Jdwl Ujian:</td>
      <td class=ul>$jdwl[_UASTanggal], $jdwl[_HRUAS], <sup>$jdwl[_JMUAS]</sup>&#8594;<sub>$jdwl[_JSUAS]</sub></td>
  </table>";
}

function PresensiScript() {
  echo <<<SCR
  <script>
  function PrsnEdit(md, jid, pid) {
    lnk = "$_SESSION[mnux].edit.php?md="+md+"&jid="+jid+"&pid="+pid;
    win2 = window.open(lnk, "", "width=500, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function PrsnMhswEdit(pid) {
    lnk = "$_SESSION[mnux].mhswedit.php?pid="+pid;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

function GetOption3($table, $key, $Fields, $Label, $Nilai='', $Separator=',', $whr = '', $antar='<br />') {
  $_whr = (empty($whr))? '' : "and $whr";
  $s = "select $key, $Fields
    from $table
    where NA='N' $_whr order by $key";
  $r = _query($s);
  $_arrNilai = explode($Separator, $Nilai);
  $str = '';
  while ($w = _fetch_array($r)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'selected';
    $str .= "<option value='$w[$key]'>$w[$Label]</option>";
	//$str .= "<input type=checkbox name='".$key."[]' value='$w[$key]' $_ck> $w[$Label]$antar";
  }
  return $str;
}
?>