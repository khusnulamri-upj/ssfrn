<?php
// Clustering Kelas
function TampilkanFilterMhsw() {
  global $arrID;
  $optprodi = GetOption2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID', $_SESSION['prodi'], '', 'ProdiID');
  $optprid = GetOption2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['prid'], '', 'ProgramID');
  echo "<p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='cluster'>
  <input type=hidden name='gos' value=''>
  <tr><td colspan=2 class=ul><b>$arrID[Nama]</b></td></tr>
  <tr><td class=inp1>Periode PMB</td><td class=ul><input type=text name='pmbperiod' value='$_SESSION[pmbperiod]' size=10 maxlength=10></td></tr>
  <tr><td class=inp1>Program</td><td class=ul><select name='prid'>$optprid</select></td></tr>
  <tr><td class=inp1>Program Studi</td><td class=ul><select name='prodi'>$optprodi</select></td></tr>
  <tr><td colspan=2><input type=submit name='Filter' value='Filter'></td></tr>
  </form></table></p>";
}

function setupKelas(){
    $NamaKelas = GetaField('prodi', 'ProdiID', $_SESSION['prodi'], "NamaKelas");
    $jml = GetFields('pmb', "PMBPeriodID = '$_SESSION[pmbperiod]' and ProdiID='$_SESSION[prodi]' and ProgramID='$_SESSION[prid]' and LulusUjian", 'Y', 'count(PMBID) as JUM');
    $jml_n = GetaField('pmb', "PMBPeriodID = '$_SESSION[pmbperiod]' and ProdiID='$_SESSION[prodi]' and NIM is NULL and ProgramID='$_SESSION[prid]' and LulusUjian", 'Y', 'count(PMBID)');
    $format_nim = GetaField('prodi', 'ProdiID', $_SESSION['prodi'], 'FormatNIM');
    $lastNIM = GetaField('mhsw', 'ProdiID', $_SESSION['prodi'], 'max(MhswID)');
    
    if ($jml_n == 0) $ss = "readonly=true";
    echo "<p>
    <form method=POST action=?>
    <input type=hidden name=mnux value=cluster>
    <input type=hidden name=gos value=setupKelas>
    <input type=hidden name=subg value=bagiKelas>
    <table class=box cellpadding=4 cellspacing=1>
    <tr><th class=ttl colspan=4>Setup Kelas</th></tr>
    <tr><td class=inp>Jumlah Calon Mahasiswa</td><td class=ul>$jml[JUM]</td><td class=inp>NIM Terakhir</td><td class=ul><b>$lastNIM</b></td></tr>
    <tr><td class=inp>Nama Kelas</td><td class=ul><input type=hidden name=nama_kelas value=$NamaKelas><b>$NamaKelas</b></td><td class=inp>Format NIM</td><td class=ul>$format_nim</td></tr>
    <tr><td class=inp>Kapasitas Kelas</td><td class=ul><input type=text size=5 style='text-align:right' $ss name=kapasitas_kelas value=$_SESSION[kapasitas_kelas]></td></tr>
    <tr><td class=ul colspan=4><input type=submit name=submit value=Proses></td></tr>
    </table></form></p>";
}

function GenerateNIM(){
  echo "<p>
    <form method=POST action=?>
    <input type=hidden name=mnux value=cluster>
    <input type=hidden name=gos value=NIMPrc>
    <input type=hidden name=subg value=bagiKelas>
    <table class=box cellpadding=4 cellspacing=1>
    <tr><th class=ttl colspan=2>Generate NPM</th></tr>
    <tr><td class=inp>Tahun dan Periode Angkatan:</td><td class=ul><input type=text size=5 style='text-align:right' name=tahun_angkatan value=$_SESSION[tahun_angkatan]></td></tr>
    <tr><td class=ul colspan=4><input type=submit name=submit value=Proses></td></tr>
    </table></form></p>";
}

function updateKelas($pmbid, $nama_kelas, $kelas) {
  $s = "UPDATE pmb set NamaKelas='$nama_kelas', Kelas='$kelas' WHERE PMBID='$pmbid'";
  _query($s);
}

function bagiKelas(){
    $pmbperiod 		= $_SESSION['pmbperiod'];
    $prodiid   		= $_SESSION['prodi'];
    $programid 		= $_SESSION['prid'];
    $kapasitas_kelas 	= $_SESSION['kapasitas_kelas'];
    $_nama_kelas	= $_REQUEST['nama_kelas'];
    
    $s =   "SELECT PMBID, Nama, NIM
	    FROM pmb
	    WHERE PMBPeriodID='$pmbperiod'
	    AND ProdiID='$prodiid'
	    AND ProgramID='$programid'
	    AND LulusUjian = 'Y'
	    ORDER BY Nama, ProdiID, NilaiUjian";

    $r = _query($s);
    $alfabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $alf = str_split($alfabet);
    $i = 0;
    $n = 0;
    $nama_kelas = $_nama_kelas . '.' . $alf[$i];
    $first = 1;
    $pos = 1;
    echo "<p><table class=bsc width=100% cellspacing=1 cellpadding=4>";
    
    while ($w = _fetch_array($r)) {
	
	if ($first == 1) {
	    echo headerKelas($nama_kelas);
	    $first = 0;
	}
	
	$n++;
	if ($n > $kapasitas_kelas) {
	    $i++;
	    $nama_kelas = $_nama_kelas .'.'. $alf[$i];
	    echo "</table></p>";
	    echo headerKelas($nama_kelas);
	    $n = 1;
	    $pos = 0;
	}
	$w = GetFields('pmb', 'PMBID', $w['PMBID'], '*');
	
	$TahunID = trim($pmbperiod);
	$TahunID = (strlen($TahunID) <= 4) ? $TahunID . '1' : $TahunID;
	$untukNim = substr($TahunID, 0, 4);
	
	if (!empty($w['NIM'])) {
	  $cls = "style=background:yellow;";
	}
	updateKelas($w['PMBID'], $_nama_kelas, $alf[$i]);
	echo "<tr $cls><td class=ul width=5px>$n</td>
	      <td class=ul width=20px>$w[PMBID]</td>
	      <td class=ul width=400px>$w[Nama]</td>
	      <td class=ul width=20px>$w[NIM]</td></tr>";
	      
    }
    echo "</table></p>";
    echo "</table></p>";
}

function NIMPrc() {
  
  $pmbperiod 		= $_SESSION['pmbperiod'];
  $prodiid   		= $_SESSION['prodi'];
  $programid 		= $_SESSION['prid'];
  $tahun_angkatan	= trim($_SESSION['tahun_angkatan']);
  
  if (empty($tahun_angkatan)) die(ErrorMsg1("Peringatan", "Tahun Angkatan Mahasiswa belum di isi. Tahun Angkatan akan digunakan untuk proses pembuatan NIM mahasiswa.<br />
					Isi dengan format : Tahun Angkatan denga Periode masuk Mahasiswa. Misalnya 20071"));
    
  
  // hitung jumlah proses
  $s =   "SELECT PMBID, Nama, NIM
	  FROM pmb
	  WHERE PMBPeriodID='$pmbperiod'
	  AND ProdiID='$prodiid'
	  AND ProgramID='$programid'
	  AND LulusUjian = 'Y'
	  AND NIM IS NULL
	  ORDER BY Nama";
	  
  $r = _query($s);
  $jml = _num_rows($r);
  $n=0;
  while ($w = _fetch_array($r)) {
      $_SESSION['NIM'.$n] = $w['PMBID'];
      $n++;
  }
  $_SESSION['NIMCOUNT'] = $n;
  $_SESSION['NIMPOS'] = 0;
    
  echo "<p>Sistem akan memproses <font size=+2>$jml</font> data</p>
    <p><IFRAME src='cluster.nim.prc.php?gos=PRCNIM&prodi=$prodiid&thnangkatan=$tahun_angkatan' frameborder=0>
    </IFRAME></p>";
}

function headerKelas($namaKelas) {
    $t  = "<p><table class=box cellpadding=4 cellspacing=1>";
    $t .= "<tr><th class=ttl colspan=4>Kelas : $namaKelas</th>";
    $t .= "<tr><td class=ttl>No.</td><td class=ttl>PMBID</td><td class=ttl>Nama</td><td class=ttl>NIM</td></tr>";
    
    return $t;
}

$prodi = GetSetVar('prodi');
$prid = GetSetVar('prid');
$pmbperiod = GetSetVar("pmbperiod");

$Kaps = GetFields('prodi', 'ProdiID', $prodi, "KapasitasKelas");

$kapasitas_kelas = GetSetVar('kapasitas_kelas', $Kaps['KapasitasKelas']);
$tahun_angkatan = GetSetVar('tahun_angkatan');


if (empty($pmbperiod)) {
  $pmbperiod = GetaField("pmbperiod", "NA", 'N', "PMBPeriodID");
  $_SESSION['pmbperiod'] = $pmbperiod;
}

$gos = (empty($_REQUEST['gos']))? 'setupKelas' : $_REQUEST['gos'];
$subg = $_REQUEST['subg'];

// *** Main ***
TampilkanJudul("Pembagian Kelas dan NIM");
TampilkanFilterMhsw();
if (!empty($_SESSION['prodi']) && !empty($_SESSION['prid']) && !empty($_SESSION['KodeID'])) {
  $gos();
  if (!empty($subg)) {
    $jml = GetaField('pmb', "PMBPeriodID = '$_SESSION[pmbperiod]' and ProdiID='$_SESSION[prodi]' and ProgramID='$_SESSION[prid]' and NIM IS NULL and LulusUjian", 'Y', 'count(PMBID)');
    if ($jml > 0) GenerateNIM();
    bagiKelas();
  }
}
?>