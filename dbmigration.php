<?php
// Author: Emanuel Setio Dewo
// 16 March 2006

// *** Functions ***
function DftrData() {
  /*$s = "select *
    from rekening
    where KodeID='$_SESSION[KodeID]' ";
  $r = _query($s);
  $nomer = 0;*/
  $link = "<tr><td class=ul colspan=5>
    <input type=button name='InitDB' value='InitDB' onClick=\"location='?mnux=$_SESSION[mnux]&gos=DataEdt&md=1'\" /> ||
		<input type=button name='MhswUploadExcel' value='Mhswa' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:MhswUpload()\" /> ||
		<input type=button name='DosenUploadExcel' value='Dosen' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:DosenUpload()\" /> ||
		<input type=button name='MatakuliahUploadExcel' value='MK' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:MatakuliahUpload()\" /> ||	
		<input type=button name='JadwalUploadExcel' value='Jadwalx' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:JadwalUpload()\" /> ||
		<input type=button name='KHSUploadExcel' value='KHS' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:KHSUpload()\" /> ||
		<input type=button name='KRSUploadExcel' value='KRS' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:KRSUpload()\" /> ||		
		<input type=button name='AlumniUploadExcel' value='Alumni' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:AlumniUpload()\" /> ||
		<input type=button name='EvaluasiUploadExcel' value='Evaluasi' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:EvaluasiUpload()\" /> ||
		<input type=button name='RuangUploadExcel' value='Ruang' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:RuangUpload()\" /> ||
		<input type=button name='KRSXLSUploadExcel' value='KRSXLS' title='Upload Data Excel untuk Memigrasikan Data' onClick=\"javascript:KRSXLSUpload()\" /> ||
    <!--<input type=button name='Refresh' value='Refresh Data' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\" />-->
    </td></tr>";
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=800>
    $link
    <!--<tr><th class=ttl>No.</th>
    <th class=ttl>No. Rekening</th>
    <th class=ttl>Nama</th>    
    <th class=ttl>NA</th>
    </tr>-->";
  /*while ($w = _fetch_array($r)) {
    $nomer++;
    $c = ($w['NA'] == 'Y')? 'class=nac' : 'class=ul';
    echo "<tr><td class=inp width=30>$nomer</td>
      <td $c nowrap>
        <input type=button name='Edit' value='&raquo;'
          onClick=\"location='?mnux=$_SESSION[mnux]&gos=RekEdt&md=0&rekid=$w[RekeningID]'\" />
        $w[RekeningID]</a></td>
      <td $c nowrap>$w[Nama]</td>
      <td $c nowrap>$w[Bank]</td>
	  <td $c nowrap>$w[Cabang]</td>
      <td $c align=center width=20><img src='img/book$w[NA].gif'></td>
      </tr>";
  }*/
  echo "</table></p>
		<script>		
		function MhswUpload() {
			//lnk = 'master/mhsw.upload.gradenilai.php?Sync=0';
			lnk = 'pmb/pmb.mhswid.upload.php?Sync=0';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}
		function DosenUpload() {
			lnk = 'master/dosen.upload2.php?Sync=1';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}		
		function MatakuliahUpload() {
			lnk = 'master/matakuliah.upload.php?Sync=0';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}	
		function RuangUpload() {
			lnk = 'master/Ruang.upload.php?Sync=1';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}
		function JadwalUpload() {
			lnk = 'jur/jadwal.upload.nama.php?Sync=1';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}	
		function KHSUpload() {
			lnk = 'jur/khs.upload.php?Sync=0';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}	
		function KRSUpload() {
			lnk = 'jur/krsvirtu.upload.php?Sync=1';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}	
		function AlumniUpload() {
			lnk = 'baa/alumni.upload.php?Sync=0';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}
		function EvaluasiUpload() {
			lnk = 'jur/evaluasi.upload.php?Sync=1';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}
		function KRSXLSUpload() {
			lnk = 'jur/krsxls.upload.php?Sync=1';
			win2 = window.open(lnk, 0, 'width=600, height=600, scrollbars, status, resizable');
			if (win2.opener == null) childWindow.opener = self;
		}	
		</script>";
}

function CariPTDiktiScript() {
  echo <<<EOF
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function caript(frm){
    lnk = "cari/cariptdikti.php?PerguruanTinggiID="+frm.KodeHukum.value+"&Cari="+frm.Nama.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  -->
  </script>
EOF;
}

function DataEdt() {
	CariPTDiktiScript();
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $Kode = $_REQUEST['Kode'];
    $w = GetFields('identitas', 'Kode', $Kode, '*');
    $jdl = "Migration";
    $Kode_obj = "<input type=text name='Kode' value='$w[Kode]'>";
  }
  else {
    //$w = array();
		$w = GetFields('identitas', 'Kode', $Kode, '*');    
    //$w['NA'] = 'N';	
    $jdl = "Migration";
    $Kode_obj = "<input type=text name='Kode' value='$w[Kode]' size=15 maxlength=50>";
  }
  //$na = ($w['NA'] == 'Y')? 'checked' : '';
	$c1 = 'class=inp'; 
	$c2 = 'class=ull';
  CheckFormScript("Kode,KodeHukum,Nama");
  // Tampilkan
  echo "<p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST name='data' onSubmit='return CheckForm(this)' />
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='gos' value='DataSav' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='BypassMenu' value='1' />  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td $c1 nowrap>KodeID:</td><td $c2>$Kode_obj</td></tr>
	<tr><td $c1>Kode Perg. Tinggi</td><td $c2><input type=text name='KodeHukum' value='$w[KodeHukum]' size=15 maxlength=10></td></tr>
  <tr><td $c1>Nama Perg. Tinggi</td><td $c2><input type=text name='Nama' value='$w[Nama]' size=70 maxlength=100> <a href='javascript:caript(data)'>Cari</a></td></tr>
  <tr><td $c1>Yayasan / Departemen</td><td $c2><input type=text name='Yayasan' value='$w[Yayasan]' size=70 maxlength=100></td></tr>
  <!--<tr><td class=inp>Tidak aktif?</td><td class=ul><input type=checkbox name='NA' value='Y' $na></td></tr>-->
  <tr><td class=ul colspan=2 align=center>
      <input type=submit name='Simpan' value='Proceed Now' />
      <input type=reset name='Reset' value='Reset' />
      <input type=button name='Batal' value='Batal' onClick=\"location='?mnux=$_SESSION[mnux]'\" />
      </td></tr>
  </form>
  </table></p>";
}

function DataSav() {
  $md = $_REQUEST['md']+0;
  $Kode = $_REQUEST['Kode'];
  $KodeHukum = sqling($_REQUEST['KodeHukum']);  
	$Nama = sqling($_REQUEST['Nama']);  
	$Yayasan = sqling($_REQUEST['Yayasan']);  
  //$NA = empty($_REQUEST['NA'])? 'N' : $_REQUEST['NA'];
  if ($md == 0) {
    /*$s = "update rekening set Nama='$Nama', Bank='$Bank', Cabang='$Cabang', NA='$NA'
      where RekeningID='$RekeningID' ";
    $r = _query($s);*/
  }
  else { // Migrate
    $ada = '';//GetFields('rekening', 'RekeningID', $RekeningID, '*');
    if (empty($ada)) {
			// Initializing database => first thing to do
      InitDBSav($Kode);
    } // Parameters invalid
    else { 
			/*echo ErrorMsg('Rekening Tidak Dapat Disimpan',
      "<p>Nomer rekening sudah ada. Berikut adalah data rekening tersebut:</p>
      <p><table class=box cellspacing=1 cellpadding=4>
      <tr><td class=inp1>Nomer Rekening</td><td class=ul>$ada[RekeningID]</td></tr>
      <tr><td class=inp1>Kode Institusi</td><td class=ul>$ada[KodeID]</td></tr>
      <tr><td class=inp1>Nama Pemilik</td><td class=ul>$ada[Nama]</td></tr>
      <tr><td class=inp1>Nama Bank</td><td class=ul>$ada[Bank]</td></tr>
      <tr><td class=inp1>Tidak aktif?</td><td class=ul><img src='img/book$ada[NA].gif'></td></tr>
      </table></p>");*/
		}
  }
  BerhasilSimpan("?mnux=$_SESSION[mnux]", 10);
}

function InitDBSav($Kode) {  
	// Truncate database 
	$s = "TRUNCATE TABLE mhsw;
				TRUNCATE TABLE khs;
				TRUNCATE TABLE krs;
				TRUNCATE TABLE alumni;
				TRUNCATE TABLE alumnikerja;
				TRUNCATE TABLE aplikan;
				TRUNCATE TABLE bayarmhsw;
				TRUNCATE TABLE bayarmhsw2;
				TRUNCATE TABLE bipot;
				TRUNCATE TABLE bipot2;
				TRUNCATE TABLE bipotmhsw;
				TRUNCATE TABLE bipotnama;
				TRUNCATE TABLE dosen;
				TRUNCATE TABLE dosenpekerjaan;
				TRUNCATE TABLE dosenpendidikan;
				TRUNCATE TABLE dosenpenelitian;
				TRUNCATE TABLE fakultas;
				TRUNCATE TABLE golongan;
				TRUNCATE TABLE gradeipk;
				TRUNCATE TABLE hadirsks;
				TRUNCATE TABLE harilibur;
				TRUNCATE TABLE honordosen;
				TRUNCATE TABLE jadwal;
				TRUNCATE TABLE jadwaldosen;
				TRUNCATE TABLE jadwaluas;
				TRUNCATE TABLE jadwaluts;
				TRUNCATE TABLE jeniskurikulum;
				TRUNCATE TABLE kampus;						
				TRUNCATE TABLE kelas;
				TRUNCATE TABLE kompre;
				TRUNCATE TABLE kompredosen;
				TRUNCATE TABLE komprematauji;
				TRUNCATE TABLE konsentrasi;
				TRUNCATE TABLE koreksinilai;
				TRUNCATE TABLE kurikulum;
				TRUNCATE TABLE matrikulasi;
				TRUNCATE TABLE matrimatauji;
				TRUNCATE TABLE maxsks;
				TRUNCATE TABLE mk;
				TRUNCATE TABLE mkpaket;
				TRUNCATE TABLE mkpaketisi;
				TRUNCATE TABLE mkpra;
				TRUNCATE TABLE nilai;
				TRUNCATE TABLE pejabat;
				TRUNCATE TABLE pmb;
				TRUNCATE TABLE pmbformjual;
				TRUNCATE TABLE pmbformulir;
				TRUNCATE TABLE pmbgrade;
				TRUNCATE TABLE pmbperiod;
				TRUNCATE TABLE pmbusm;
				TRUNCATE TABLE praktekkerja;
				TRUNCATE TABLE predikat;
				TRUNCATE TABLE presensi;
				TRUNCATE TABLE presensimhsw;
				TRUNCATE TABLE presenter;
				TRUNCATE TABLE prodi;
				TRUNCATE TABLE prodiusm;
				TRUNCATE TABLE prosesstatusmhsw;
				TRUNCATE TABLE rekening;
				TRUNCATE TABLE ruang;
				TRUNCATE TABLE ruangusm;
				TRUNCATE TABLE session;
				TRUNCATE TABLE statusaplikanmhsw;
				TRUNCATE TABLE ta;
				TRUNCATE TABLE tabimbingan;
				TRUNCATE TABLE tadosen;
				TRUNCATE TABLE tahun;
				TRUNCATE TABLE uasmhsw;
				TRUNCATE TABLE utsmhsw;
				TRUNCATE TABLE wawancara;
				TRUNCATE TABLE wawancarausm;
				TRUNCATE TABLE wisuda;
				TRUNCATE TABLE wisudaprasyarat;
				TRUNCATE TABLE wisudawan";
	$r = mysql_query($s);
	
	// KodeID alone => to be replaced
	// ==============================
	// => Table identitas => replace KodeID with new Identitas ID and other necessary fields
	$KodeHukum = $_REQUEST['KodeHukum'];
	$Nama = $_REQUEST['Nama'];
	$Yayasan = $_REQUEST['Yayasan'];
	$s = "update identitas set Kode = '$Kode', KodeHukum = '$KodeHukum', Nama = '$Nama', Yayasan = '$Yayasan' where NA = 'N'";
	$r = _query($s);
	// => Predefined tables those KodeID 'SISFO' must be replaced by new Identitas ID
	$s = "update pmbformsyarat set KodeID = '$Kode'";
	$r = _query($s);	
	$s = "update program set KodeID = '$Kode'";
	$r = _query($s);
	$s = "update statusaplikan set KodeID = '$Kode'";
	$r = _query($s);
	$s = "update statusmhsw set KodeID = '$Kode'";
	$r = _query($s);
	$s = "update sumberinfo set KodeID = '$Kode'";
	$r = _query($s);
	
	// Login		
	// =====
	$s = "delete from karyawan where LevelID!=1";
	$r = _query($s);			
	$s = "update karyawan set KodeID = '$Kode', ProdiID = ''";
	$r = _query($s);
}

// *** Parameters ***
$gos = (empty($_REQUEST['gos']))? 'DftrData' : $_REQUEST['gos'];

// *** Main ***
TampilkanJudul("Database Migration $arrID[Nama]");
$gos();
?>
