<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 04 Desember 2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Daftar Dosen Pengasuh", 1);

// *** Parameters ***
$_honProdi = GetSetVar('_honProdi');
$_honBulan = GetSetVar('_honBulan');
$_honTahun = GetSetVar('_honTahun');
$_honTahunID = GetSetVar('_honTahunID');
$_honDosen = GetSetVar('_honDosen');
$_honMinggu = GetSetVar('_honMinggu', 'M1');

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'DftrDosenPengasuh' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function DftrDosenPengasuh() {
  TampilkanJudul("Dosen Pengasuh");
  _headerDosenPengasuh();
  if (empty($_SESSION['_honTahunID']) || empty($_SESSION['_honBulan'])){
    echo ErrorMsg("Tahun Akademik & Bulan",
      "Masukkan Tahun Akademik & Bulan dimana dosen mengajar.<br />
      Sistem akan mendaftar dosen-dosen yang mengajar.");
  }
  else DaftarDosenPengasuh();
}
function _headerDosenPengasuh() {
  //$optbulan = GetMonthOption($_SESSION['_honBulan']);
  $Bulan = UbahKeBulanIndonesia($_SESSION['_honBulan']);
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmHeaderDosenPengasuh' action='?' method=POST>
  <tr>
      <td class=inp>Thn Akd:</td>
      <td class=ul1><input type=text name='_honTahunID' value='$_SESSION[_honTahunID]' size=5 maxlength=6 /></td>
      <td class=inp>Bulan:</td>
      <td class=ul1>$Bulan</td>
	  <td class=inp>Tahun:</td>
      <td class=ul1>$_SESSION[_honTahun]</td>
      <td class=inp>Cari Dosen:</td>
      <td class=ul1><input type=text name='_honDosen' value='$_SESSION[_honDosen]' size=10 maxlength=50 /></td>
      <td class=ul1 align=right>
        <input type=submit name='btnSubmit' value='Kirim' />
        <input type=button name='btnClose' value='Tutup' onClick="javascript:Tutup()" />
        </td>
      </tr>
  </form>
  </table>
ESD;
}
function DaftarDosenPengasuh() {
  $whr_dosen = ($_SESSION['_honDosen'] == '')? '' : "and d.Nama like '$_SESSION[_honDosen]%' ";
  $s = "select p.DosenID, p.TahunID, count(DISTINCT(p.Tanggal), p.JamMulai, p.JamSelesai) as JML, 
      d.Nama, d.Gelar, d.StatusDosenID,
      if (sd.Nama is null, '&times;', sd.Nama) as ST_DSN
    from presensi p
      left outer join dosen d on p.DosenID = d.Login and d.KodeID = '".KodeID."'
      left outer join statusdosen sd on d.StatusDosenID = sd.StatusDosenID
    where p.TahunID = '$_SESSION[_honTahunID]'
      and MONTH(p.Tanggal) = '$_SESSION[_honBulan]'
	  and YEAR(p.Tanggal) = '$_SESSION[_honTahun]'
      and p.HonorDosenID = 0
      $whr_dosen
    group by p.DosenID";
  $r = _query($s); $n = 0;
  
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  <tr><th class=ttl width=30>Nmr</th>
      <th class=ttl width=100>No. Dosen</th>
      <th class=ttl>Nama Dosen</th>
      <th class=ttl width=50>Status</th>
      <th class=ttl width=40>Jml Hadir</th>
      <th class=ttl width=100>Hondok</th>
      </tr>
ESD;

  while ($w = _fetch_array($r)) {
    $n++;
    echo "<tr>
      <td class=inp>$n</td>
      <td class=ul align=center>$w[DosenID]</td>
      <td class=ul>$w[Nama] <sup>$w[Gelar]</sup></td>
      <td class=ul align=center>$w[ST_DSN]</td>
      <td class=ul align=right>$w[JML]&times;</td>
      <td class=ul align=right>
        <input type=button name='btnTambah' value='Buat Hondo'
          onClick=\"location='?_honDosenID=$w[DosenID]&gos=fnEditHondok&md=1&_honID=0&_honTahunID=$w[TahunID]'\" />
      </tr>";
  }
  echo "</table>";
}
function fnEditHondok() {
  global $arrBulan;
  $_honDosenID = $_REQUEST['_honDosenID'];
  $md = $_REQUEST['md']+0;
  $_honID = $_REQUEST['_honID']+0;
  $_honTahunID = $_REQUEST['_honTahunID'];
  
  $dsn = GetFields('dosen', "Login='$_honDosenID' and KodeID", KodeID, "*");
  
  if ($md == 0) {
    $w = GetFields('honordosen', 'HonorDosenID', $_honID, '*');
    $jdl = "Edit Honor Dosen <sup>$_honTahunID</sup>";
    $_SESSION['_honMinggu']=$w['Minggu'];
  }
  elseif ($md == 1) {
  	
    $w = array();
    $w['TahunID'] = $_honTahunID;
    $w['Minggu'] = $_SESSION['_honMinggu'];//GetaField('minggu', 'Def', 'Y', 'MingguID');
    $w['Tahun'] = $_SESSION['_honTahun'];
    $w['Bulan'] = $_SESSION['_honBulan'];
    $w['DosenID'] = $_honDosenID;
    $w['ProdiID'] = $dsn['ProdiID'];
	$pajak = GetaField("prodi","ProdiID = '$dsn[Homebase]' and KodeID",KodeID,"PajakHonorDosen");
    $w['Pajak'] = $pajak;
    $jdl = "Tambah Honor Dosen <sup>$_honTahunID</sup>";
  }
  else die(ErrorMsg("Error",
    "Mode edit <b>$md</b> tidak dikenali oleh sistem.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='btnClose' value='Tutup' onClick='window.close()' />"));
  
  $homebase = GetaField('prodi', "ProdiID='$dsn[Homebase]' and KodeID", KodeID, 'Nama');
  $optminggu = GetOption2('minggu', "concat(MingguID, ' - ', Nama)", 'MingguID',
    $_SESSION['_honMinggu'], '', 'MingguID');
  $stt = GetaField('statusdosen', 'StatusDosenID', $dsn['StatusDosenID'], 'Nama');
  $stt = (empty($stt))? '&times; (Belum diset)' : $dsn['StatusDosenID'] . ' &minus; ' . $stt;
  // Tampilan
  TampilkanJudul($jdl);
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmMenuHondok' action='?' method=POST>
  <input type=hidden name='_honTahunID' value='$_honTahunID' />
  <input type=hidden name='_honTahun' value='$_SESSION[_honTahun]' />
  <input type=hidden name='_honBulan' value='$w[Bulan]' />
  <input type=hidden name='_honDosenID' value='$w[DosenID]' />
  <input type=hidden name='_honID' value='$_honID' />
  <input type=hidden name='gos' value='fnEditHondok' />
  <input type=hidden name='md' value='$md' />
  
  <tr><td class=inp>Dosen:</td>
      <td class=ul1>$dsn[Nama] <sup>$dsn[Gelar]</sup></td>
      <td class=inp>Homebase:</td>
      <td class=ul1>$homebase <sup>$dsn[Homebase]</sup></td>
      </tr>
  <tr><td class=inp>Bulan:</td>
      <td class=ul1>$_SESSION[_honBulan]&minus;$_SESSION[_honTahun]</td>
      <td class=inp>Minggu:</td>
      <td class=ul1><select name='_honMinggu' onChange='this.form.submit()'>$optminggu</select></td>
      </tr>
  <tr><td class=inp>Status:</td>
      <td class=ul1 colspan=3>$stt</td>
      </tr>
  </form>
  </table>
  
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmEditHondok' action='?' method=POST>
  <input type=hidden name='_honTahunID' value='$_honTahunID' />
  <input type=hidden name='_honTahun' value='$_SESSION[_honTahun]' />
  <input type=hidden name='_honBulan' value='$w[Bulan]' />
  <input type=hidden name='_honDosenID' value='$w[DosenID]' />
  <input type=hidden name='_honID' value='$_honID' />
  <input type=hidden name='_honMinggu' value='$_SESSION[_honMinggu]' />
  <input type=hidden name='gos' value='fnSimpanHondok' />
  <input type=hidden name='md' value='$md' />
  <tr><td class=inp nowrap>&nbsp; &nbsp; &nbsp; &nbsp; Pajak :</td>
      <td class=ul1 colspan=6><input type=text name='_honPajak' value='$w[Pajak]' size=4 maxlength=4 /> %</td>
      </tr>
  <tr>
    <th class=ttl width=25>Nmr</th>
    <th class=ttl width=80>Presensi</th>
    <th class=ttl>Matakuliah</th>
    <th class=ttl width=40>SKS</th>
    <th class=ttl width=40>Tunjangan<br />SKS(per SKS)</th>
    <th class=ttl width=40>Tunjangan<br />Transport</th>
    <th class=ttl width=40>Tunjangan<br />Tetap</th>
    </tr>
ESD;
  // Ambil detailnya
  AmbilDetail($_honID, $_honDosenID, $_honTahunID);
  echo <<<ESD
  
  <tr><td class=ul1 colspan=10 align=center>
      <input type=submit name='btnSimpan' value='Simpan' />
      <input type=button name='btnBatal' value='Batal' onClick='Tutup()' />
      </td></tr>
  </form>
  </table>
ESD;
}
function AmbilDetail($_honID, $_honDosenID, $_honTahunID) {
 
  $s = "select p.*, j.SKSHonor as SKSHon, j.SKS, GROUP_CONCAT(DISTINCT prd.Nama ORDER BY prd.Nama ASC SEPARATOR '/') as _allProd,
      GROUP_CONCAT(DISTINCT j.MKKode ORDER BY j.MKKode ASC SEPARATOR '/') as _allMKKode,
	  p.SKSHonor,
	  p.JamMulai, p.JamSelesai,
      j.Nama, j.MKKode,
      date_format(p.Tanggal, '%d-%m-%Y') as TGL,
      h.Nama as _HR,
      prd.Nama as _PRD,
	  DAYOFMONTH(p.Tanggal) as _DayOfMonth
    from presensi p
      left outer join jadwal j on j.JadwalID = p.JadwalID
      left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
      left outer join hari h on h.HariID = date_format(p.Tanggal, '%w')
    where p.TahunID = '$_honTahunID'
      and p.DosenID = '$_honDosenID'
	    and MONTH(p.Tanggal) = '$_SESSION[_honBulan]'
      and YEAR(p.Tanggal) = '$_SESSION[_honTahun]'
	  and (p.HonorDosenID = $_honID or p.HonorDosenID = 0)
    group by j.Nama, p.Tanggal, p.JamMulai, p.JamSelesai
	order by p.Tanggal, j.Nama, j.MKKode";
  $r = _query($s); $n = 0;
 
  $TanggalSkrg = '46hte5q34qa3'; $JamMulaiSekarang = '2o4nlqnvq3ov'; $JamSelesaiSkrg = '24nrlgavjnsfv'; $MKNamaSkrg = 'a35nae5naddrs';
  while ($w = _fetch_array($r)) {
    if($TanggalSkrg == $w['Tanggal'] and $JamMulaiSkrg == $w['JamMulai'] and $JamSelesaiSkrg == $w['JamSelesai'] and $MKNamaSkrg == $w['Nama'])
	{	
	}
	else
	{
		$TanggalSkrg = $w['Tanggal'];
		$JamMulaiSkrg = $w['JamMulai'];
		$JamSelesaiSkrg = $w['JamSelesai'];
		$MKNamaSkrg = $w['Nama'];
		
		$haripertama = date('w', strtotime(substr($w['Tanggal'], 0, 8).'01'));
		$endoffirstweek =  7 - $haripertama;
		$mingguke = ceil(($w['_DayOfMonth'] - $endoffirstweek) / 7)+1;
		//echo $mingguke;
		///$haripertama/$w[_DayOfMonth]/$mingguke
		
		if($mingguke == substr($_SESSION['_honMinggu'], 1, 1)+0)
		{
			$n++;
			if ($w['HonorDosenID'] == 0) {
			  $_honSKS = ($w['SKSHonor'] == 0)? $w['SKS'] : $w['SKSHonor'];
			  $_honCek = '';
			}
			else {
			  $_honSKS = $w['SKSHonor'];
			  $_honCek = 'checked';
			}
			$_JamMulai = substr($w[JamMulai], 0, 5);
			$_JamSelesai = substr($w[JamSelesai], 0, 5);
			echo "<tr>
			  <td class=inp width=25>$n</td>
			  <td class=ul width=80 align=center><sup>$w[_HR]</sup>$w[TGL]
				<div align=center><sup>$_JamMulai - $_JamSelesai</sup></div></td>
			  <td class=ul>
				<sup>$w[_allMKKode]</sup><br />
				$w[Nama]
				<div align=right><sup>$w[_allProd]</sup></div>
				</td>
				<input type=hidden name='_hon_$n' value='$w[PresensiID]' />
			  <td class=ul>
				<input type=text name='_honSKS_$n' style='text-align:right' value='$_honSKS' size=3 maxlength=3 />
				</td>
			  <td class=ul>
				<input type=text name='_honTunjanganSKS_$n' style='text-align:right'
				  value='$w[TunjanganSKS]' size=6 maxlength=15 />
				</td>
			  <td class=ul>
				<input type=text name='_honTunjanganTransport_$n' style='text-align:right'
				  value='$w[TunjanganTransport]' size=6 maxlength=15 />
				</td>
			  <td class=ul>
				<input type=text name='_honTunjanganTetap_$n' style='text-align:right'
				  value='$w[TunjanganTetap]' size=6 maxlength=15 />
				</td>
			  </tr>";
		  }
	  }
  }
  echo "<input type=hidden name='_honJml' value='$n' />";
}
function fnSimpanHondok() {
  $_honTahunID = sqling($_REQUEST['_honTahunID']);
  $_honTahun = sqling($_REQUEST['_honTahun']);
  $_honBulan = sqling($_REQUEST['_honBulan']);
  $_honDosenID = sqling($_REQUEST['_honDosenID']);
  $_honID = $_REQUEST['_honID'];
  $_honPajak = $_REQUEST['_honPajak']+0;
 
  $_honMinggu = $_REQUEST['_honMinggu'];
  $_honJml = $_REQUEST['_honJml']+0;
  
  $md = $_REQUEST['md']+0;
  $dsn = GetFields('dosen', "Login='$_honDosenID' and KodeID", KodeID, '*');
  
  // Simpan
  if ($md == 0) {
    $s = "update honordosen
      set Pajak = '$_honPajak',
          Minggu = '$_honMinggu'
      where HonorDosenID = '$_honID' ";
    $r = _query($s);
  }
  elseif ($md == 1) {
    // Buat Header
    $s = "insert into honordosen
      (TahunID, Minggu, Bulan, Tahun, Tanggal,
      DosenID, ProdiID, Pajak,
      LoginBuat, TanggalBuat)
      values
      ('$_honTahunID', '$_honMinggu', '$_honBulan', '$_honTahun', now(),
      '$_honDosenID', '$dsn[Homebase]', $_honPajak,
      '$_SESSION[_Login]', now()) ";
    $r = _query($s);
    $_honID = GetLastID();
  }
  else die(ErrorMsg('Error',
    "Terjadi kesalahan, mode edit: <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih detail.
    <hr size=1 color=silver />
    <input type=button name='btnTutup' value='Tutup' onClick='window.close()' />"));
  
  // Simpan detailnya
  $_honJml = $_REQUEST['_honJml'];
  for ($i = 1; $i <= $_honJml; $i++) {
    $_id = $_REQUEST['_hon_'.$i]+0;
    $_SKS = $_REQUEST['_honSKS_'.$i]+0;
    $_TunjanganSKS = $_REQUEST['_honTunjanganSKS_'.$i]+0;
    $_TunjanganTransport = $_REQUEST['_honTunjanganTransport_'.$i]+0;
    $_TunjanganTetap = $_REQUEST['_honTunjanganTetap_'.$i]+0;
    
      // Tambahkan presensi ke honor dosen
      $s_add = "update presensi
        set HonorDosenID = '$_honID',
            SKSHonor = '$_SKS',
            TunjanganSKS = '$_TunjanganSKS',
            TunjanganTransport = '$_TunjanganTransport',
            TunjanganTetap = '$_TunjanganTetap',
            LoginEdit = '$_SESSION[_Login]', TanggalEdit = now()
        where PresensiID = '$_id' ";
      $r_add = _query($s_add);
    
  }
  HitungUlangHondok($_honID);
  RefreshTutup();
}
function RefreshTutup() {
  echo <<<ESD
  <script>
  opener.location = "../index.php?mnux=$_SESSION[mnux]&gos=";
  window.close();
  </script>
ESD;
}
function HitungUlangHondok($id) {
  $hon = GetFields('presensi',
    "HonorDosenID", $id,
    "sum(SKSHonor * TunjanganSKS) as _TunjanganSKS,
    sum(TunjanganTransport) as _TunjanganTransport,
    sum(TunjanganTetap) as _TunjanganTetap");
  $s = "update honordosen
    set TunjanganSKS = '$hon[_TunjanganSKS]',
        TunjanganTransport = '$hon[_TunjanganTransport]',
        TunjanganTetap = '$hon[_TunjanganTetap]',
        LoginEdit = '$_SESSION[_Login]', TanggalEdit = now()
    where HonorDosenID = '$id' ";
  $r = _query($s);
  return $hon;
}

function UbahKeBulanIndonesia($integer)
{	$arrBulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	return $arrBulan[$integer-1];
}
?>

  <script>
  function Tutup() {
    opener.location = "../index.php?$_SESSION[mnux]&gos=";
    window.close();
  }
  </script>
  
</BODY>
</HTML>
