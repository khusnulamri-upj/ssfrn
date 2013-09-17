<?php
// Author: Irvandy Goutama
// Email: irvandygoutama@gmail.com
// Start: 29/12/2009

session_start();
include_once "../sisfokampus1.php";
include_once "../util.lib.php";
HeaderSisfoKampus("Upload Data KRS Migrasi (dari format excel)");
//$TahunIDup = GetSetVar('TahunIDup');
//$ProdiIDup = GetSetVar('ProdiIDup');
//$JenisTAIDup = GetSetVar('JenisTAIDup');

// Parameter (that can be changed to fulfill the system's requirements
$BatasWaktuLebihAwal = "00:15:00" ;

// Code starts here:

$gos = (empty($_REQUEST['gos']))? "Migration" : $_REQUEST['gos'];
$gel = $_REQUEST['gel'];
$Sync = $_REQUEST['Sync'];
// *** Main ***
if ($Sync == 1) {
	TampilkanJudul("Sinkronisasi Migrasi Data - KRS Mahasiswa");
} else {
	TampilkanJudul("Migrasi Data - KRS Mahasiswa");
}
$gos($gel, $Sync);

// *** Functions ***

function Migration($gel, $Sync) {	
	//$opttahunup = GetOptionTahun($_SESSION['TahunIDup']);
	//$optprodiup = GetProdiUser($_SESSION['_Login'], $_SESSION['ProdiIDup']);
  //$optjenistaup = GetOption2("tajenis", "Nama", "Urutan", $_SESSION['JenisTAIDup'], "KodeID='".KodeID."' and ProdiID='$_SESSION[FilterProdiID]'", 'JenisTAID');
	if ($Sync == 1) {
		$KetUpload = "1. File harus memiliki <b><u>tipe .xls</u></b> (file Microsoft Excel).<br>
									2. <b><u>Baris pertama dari file tidak akan diimpor</u></b> ke dalam database. Baris ini dapat digunakan sebagai Nama Kolom.<br>
									3. <b><u>Kolom pertama dari file tidak akan diimpor</u></b> ke dalam database. Kolom ini dapat digunakan sebagai penomoran data.<br>
									4. Data yang disinkronisasi adalah: Tanggal Daftar, Tanggal Seminar 1 dan 2, dan Tanggal Ujian (data sebelumnya akan diperbarui).<br>
									<!--4. Urutan Kolom diwajibkan seperti ini: <u><b>No. |  |  |  |  |  .</b></u><br>
									5. Tanggal dan Jam Rencana Pertemuan adalah waktu di mana pertemuan ini sudah dijadwalkan dan akan terjadi, bukan waktu sebenarnya pertemuan itu terjadi. <b><u>Peringatan: data waktu HARUS dalam bentuk teks. Jangan diubah menjadi tipe date</u></b>
									6. * berarti data untuk kolom ini hanya digunakan untuk validasi data. Tidak dipaksakan harus ada (dengan resiko data mungkin tidak tepat masuk ke kelas yang diharuskan) 
									7. Data <b><u>akan divalidasi terlebih dahulu</u></b> sebelum bisa diimpor ke dalam database.-->";
	} else {
		$KetUpload = "1. File harus memiliki <b><u>tipe .xls</u></b> (file Microsoft Excel).<br>
									2. <b><u>Baris pertama dari file tidak akan diimpor</u></b> ke dalam database. Baris ini dapat digunakan sebagai Nama Kolom.<br>
									3. <b><u>Kolom pertama dari file tidak akan diimpor</u></b> ke dalam database. Kolom ini dapat digunakan sebagai penomoran data.<br>
									<!--4. Urutan Kolom diwajibkan seperti ini: <u><b>No. |  |  |  |  |  .</b></u><br>
									5. Tanggal dan Jam Rencana Pertemuan adalah waktu di mana pertemuan ini sudah dijadwalkan dan akan terjadi, bukan waktu sebenarnya pertemuan itu terjadi. <b><u>Peringatan: data waktu HARUS dalam bentuk teks. Jangan diubah menjadi tipe date</u></b>
									6. * berarti data untuk kolom ini hanya digunakan untuk validasi data. Tidak dipaksakan harus ada (dengan resiko data mungkin tidak tepat masuk ke kelas yang diharuskan) 
									7. Data <b><u>akan divalidasi terlebih dahulu</u></b> sebelum bisa diimpor ke dalam database.-->";
	}
	
	echo "<script>window.resizeTo(500, 400)</script>";
  echo "<p><table class=box align=center>
    <form enctype='multipart/form-data' action='?' method=POST>
    <input type=hidden name='gos' value='ABSSAV'>
		<input type=hidden name='gel' value='$gel'>
		<input type=hidden name='Sync' value='$Sync'>";
  echo "<tr><th class=ttl colspan=2>Transfer Data: </th></tr>
    <tr><td class=inp nowrap>Searching File</td><td class=ul nowrap><INPUT type='file' name='inFile'/></td></tr>
		<!--<tr><td class=inp nowrap>Tahun Akd:</td><td class=ul nowrap><select name='TahunIDup' onChange='//this.form.submit()'\">$opttahunup</select></td></tr>
		<tr><td class=inp nowrap>Program Studi:</td><td class=ul nowrap><select name='ProdiIDup' onChange='//this.form.submit()'\">$optprodiup</select></td></tr>
		<tr><td class=inp nowrap>Jenis TA:</td><td class=ul nowrap><select name='JenisTAIDup' onChange='//this.form.submit()'\">$optjenistaup</select></td></tr>-->
    <tr><td class=ul colspan=2 align=center>
        <input type=submit name='Transfer' value='Transfer'>
        <input type=button name='Batal' value='Batal' onClick=\"window.close()\"></td></tr>
    <tr><td class=wrn colspan=2>
		Keterangan Upload:<br>
		$KetUpload
		</td></tr>
	</form></table></p>";
}

function ABSSAV($gel, $Sync) {	
  global $BatasWaktuLebihAwal;
  echo "<script>window.resizeTo(1000, 600)</script>";
  
  $lokasiFile = $_FILES['inFile']['tmp_name'];
  $namaFile = $_FILES['inFile']['name'];
  $ukuranFile = $_FILES['inFile']['size'];
  
  $direktoriTarget = "../upload/$namaFile";
  
  if(move_uploaded_file($lokasiFile, $direktoriTarget))
  {		
		echo "
			<table class=box cellspacing=1 align=center>
			<tr>
				<td class=inp>Nama File:</td>
				<td class=ul1>$namaFile</td>
			</tr>
			<tr>
				<td class=inp>Ukuran File:</td>
				<td class=ul1>$ukuranFile bytes.</td>
			</tr>
			<tr>
				<td colspan=2 class=ul1 align=center>
					<input type=button name='Kembali' value='Kembali ke Layar Upload' onClick=\"window.location='?gel=$gel&Sync=$Sync&gos=Migration'\">
					<input type=button name='Tutup' value='Tutup' onClick=\"window.close();\">
				</td></tr>
			</table>";
		
			$ErrorList = array();
		  require_once '../Excel/reader.php';
		  $data = new Spreadsheet_Excel_Reader();
		  $data->setOutputEncoding('CP1251');
		  $data->read($direktoriTarget);
		  error_reporting(E_ALL ^ E_NOTICE);
		  
		  echo "<table class=box cellspacing=1 align=center width=100%>
				  <form action='?' method=POST>
				  <input type=hidden name='gos' value='Simpan' />
					<!--<input type=hidden name='TahunIDup' value=$_REQUEST[TahunIDup] />
					<input type=hidden name='ProdiIDup' value=$_REQUEST[ProdiIDup] />		
					<input type=hidden name='JenisTAIDup' value=$_REQUEST[JenisTAIDup] />-->
					<input type=hidden name='Sync' value=$Sync />";		
		  $ro = "";
		  
		  echo "<tr>
					<th class=ttl width=10>No.</th>					
					<th class=ttl width=10></th>					
					<th class=ttl width=20>NIM</th>
					<th class=ttl width=20>TahunID</th>
					<th class=ttl width=20>MKKode</th>
					<th class=ttl width=10>GradeNilai</th>					
					<th class=ttl width=10>BobotNilai</th>					
					<th class=ttl width=10>UTS</th>
					<th class=ttl width=10>UAS</th>
					<th class=ttl width=10>NilaiAkhir</th>					
					<th class=ttl width=40>Keterangan</th>
				</tr>";
					
		  $n = 0;
		  //$StatusKehadiranDefault = GetaField('jenispresensi', "Def", "Y", "JenisPresensiID");
		  //$arrCekMultipleKRS = array();
		  //$arrCekMultiplePresensi = array();
		  
		  for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {	
				$n++;
				$w = array(); 			
				$w['MhswID'] = trim($data->sheets[0]['cells'][$i][2]);
				$w['TahunID'] = trim($data->sheets[0]['cells'][$i][3]);								
				if ($w['MhswID'] != '') {					
					$w['MKKode'] = trim($data->sheets[0]['cells'][$i][4]);
					$w['GradeNilai'] = trim($data->sheets[0]['cells'][$i][5]);
					$w['BobotNilai'] = trim($data->sheets[0]['cells'][$i][6]);
					$w['UTS'] = trim($data->sheets[0]['cells'][$i][7]);
					$w['UAS'] = trim($data->sheets[0]['cells'][$i][8]);
					$w['NilaiAkhir'] = trim($data->sheets[0]['cells'][$i][9]);					
					if ($w['MhswID'] == '') {
						$TidakMemenuhiSyarat = true;
						$w['Keterangan'] = "NIM Kosong'";
					} else {
						$TidakMemenuhiSyarat = false;
						$w['Keterangan'] = '';
					}									
					// Cek apakah sudah dimigrasi?					
					$Data_exist = '';//GetaField('krs', "MhswID='$w[MhswID]' and NA='N' and KodeID", KodeID, "MhswID"); 
										
					// Pilihan untuk Proses Sinkronisasi/Upload
					if ($Sync == 1) { // untuk sinkronisasi
						if ($Data_exist) { // sudah pernah dimigrasi								
							$TidakMemenuhiSyarat = false;
							$TampilkanBaris = true;
							$w['Keterangan'] = 'Sudah Dimigrasikan (Disinkronisasi)';								
						} else { // belum di migrasi
							$TidakMemenuhiSyarat = true;
							$TampilkanBaris = false;
							$w['Keterangan'] = '';
						}	
					} else { // untuk upload
						if ($Data_exist) { // sudah pernah dimigrasi								
							$TidakMemenuhiSyarat = true;
							$TampilkanBaris = true;
							$w['Keterangan'] = 'Sudah Dimigrasikan';								
						} else { // belum di migrasi
							$TidakMemenuhiSyarat = false;
							$TampilkanBaris = true;
							$w['Keterangan'] = '';
						}		
					}									
																		
					if($TidakMemenuhiSyarat) $checkbox = "&times";
					else $checkbox = "<input type=checkbox name='CheckBox$n' value='Y' checked=true>";			
					$class = "cna".(($TidakMemenuhiSyarat)? 'Y' : 'N');						
					
					if ($TampilkanBaris) {
						echo "<tr>
								<td class=inp>$n</td>
								<td class=$class align=center>$checkbox</td>
								<td class=$class align=center>$w[MhswID]<input type=hidden name='MhswID$n' value='$w[MhswID]'></td>
								<td class=$class align=center>$w[TahunID]<input type=hidden name='TahunID$n' value='$w[TahunID]'></td>
								<td class=$class align=left>$w[MKKode]<input type=hidden name='MKKode$n' value='$w[MKKode]'></td>
								<td class=$class align=center>$w[GradeNilai]<input type=hidden name='GradeNilai$n' value='$w[GradeNilai]'></td>			
								<td class=$class align=center>$w[BobotNilai]<input type=hidden name='BobotNilai$n' value='$w[BobotNilai]'></td>											
								<td class=$class align=center>$w[UTS]<input type=hidden name='UTS$n' value='$w[UTS]'></td>		
								<td class=$class align=center>$w[UAS]<input type=hidden name='UAS$n' value='$w[UAS]'></td>
								<td class=$class align=center>$w[NilaiAkhir]<input type=hidden name='NilaiAkhir$n' value='$w[NilaiAkhir]'></td>								
								<td class=$class>$w[Keterangan]</td>
							 </tr>";
					} else {
						echo "";
					}
				} // endif ada data
		  } // endfor 
		  echo "<input type=hidden name='JumlahData' value='$n'>";
		  echo "<tr><td class=ul1 align=center colspan=16><input type=submit name='Simpan' value='Simpan'></td></tr>
			</form>
			</table>";
  }
  else
  {
    die(ErrorMsg('Error',
        "File data Upload belum terisi.<br />
        Masukan File dengan format .xls untuk upload data<br/>
        Hubungi Sysadmin untuk informasi lebih lanjut.
        <hr size=1 color=silver />
         <input type=button name='Tutup' value='Kembali' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\" />"));
  }
}

function Simpan() { 
	$JumlahData = $_REQUEST['JumlahData']+0;
	//$TahunIDup = $_REQUEST['TahunIDup'];
	//$ProdiIDup = $_REQUEST['ProdiIDup'];
	//$JenisTAIDup = $_REQUEST['JenisTAIDup'];
	$Sync = $_REQUEST['Sync'];	
  
	// Insert Data
	// ===========	
  for($i = 1; $i <= $JumlahData; $i++) {	
		$CheckBox = $_REQUEST['CheckBox'.$i];  
		if(!empty($CheckBox)) {	
			$MhswID = $_REQUEST['MhswID'.$i];
			$TahunID = $_REQUEST['TahunID'.$i];
			$MKKode = $_REQUEST['MKKode'.$i];						
			$GradeNilai = $_REQUEST['GradeNilai'.$i];						
			$BobotNilai = $_REQUEST['BobotNilai'.$i];	
			$UTS = $_REQUEST['UTS'.$i];						
			$UAS = $_REQUEST['UAS'.$i];						
			$NilaiAkhir = $_REQUEST['NilaiAkhir'.$i];									
			// get ProdiID
			$ProdiID = GetaField('mhsw', "MhswID='$MhswID' and NA='N' and KodeID", KodeID, "ProdiID"); 
			// get MKID, Nama, SKS from mk
			$mk = GetFields('mk', "MKKode='$MKKode' and KodeID", KodeID, "MKID, Nama, SKS"); // and ProdiID='$ProdiID' and NA='N'			
			// get KHSID
			$KHSID = GetaField('khs', "MhswID='$MhswID' and TahunID='$TahunID' and KodeID", KodeID, "KHSID"); // and NA='N'
			// get JadwalID, *
			//$Jdwl = GetFields('jadwal', "ProdiID='$ProdiID' and TahunID='$TahunID' and NA='N' and KodeID", KodeID, "*"); 
										
			if ($Sync == 1) { // untuk proses sinkronisasi				
				// update table
				// ------------
				$s = "update mhsw set  where KodeID='".KodeID."'";
				$r = _query($s);																																					
			} else { // untuk proses upload				
				// update table krs
				// ----------------				
				/*$s = "select *
					from jadwal
					where ProdiID='$ProdiID' and TahunID='$TahunID' and NA='N' and KodeID = '".KodeID."'				
					order by JadwalID";
				$r = _query($s);
				while ($w = _fetch_array($r)) 
				{*/
				if (!empty($KHSID) and !empty($GradeNilai) and !empty($mk['MKID'])) {
					$s = "insert into krs
								(MhswID, KHSID, TahunID, MKID, MKKode, GradeNilai, BobotNilai, UTS, UAS, NilaiAkhir, Nama, SKS, Tinggi, Final, LoginBuat, TanggalBuat, KodeID)
								values
								('$MhswID', '$KHSID', '$TahunID', '$mk[MKID]', '$MKKode', '$GradeNilai', '$BobotNilai', '$UTS', '$UAS', '$NilaiAkhir', '$mk[Nama]', '$mk[SKS]', '', 'Y', '$_SESSION[_Login]', now(), '".KodeID."')";
					$r = _query($s);	// , UTS, UAS, NilaiAkhir		, '$UTS', '$UAS', '$NilaiAkhir'
				}
			} // end if $Sync == 1										
		} // end if !empty($CheckBox) 
  } // end for $i <= $JumlahData => insert data	
	
	// marking NilaiTertinggi (Tinggi = '*')
	  // Ambil semuanya dulu
		$s = "select MhswID, KRSID, MKKode, BobotNilai, GradeNilai, SKS, Tinggi
			from krs						
			order by MKKode";//where KodeID = '".KodeID."'	
		$r = _query($s);
		while ($w = _fetch_array($r)) 
		{
			$MhswID = $w['MhswID'];
			$ada = GetFields('krs', "Tinggi='*' and KRSID<>'$w[KRSID]' and MhswID='$MhswID' and MKKode", $w['MKKode'], '*');
			// Jika nilai sekarang lebih tinggi
			if ($w['BobotNilai'] > $ada['BobotNilai']) {
				$s1 = "update krs set Tinggi='*' where KRSID='$w[KRSID]' ";
				$r1 = _query($s1);
				// Cek yg lalu, kalau tinggi, maka reset
				if ($ada['Tinggi'] == '*') {
					$s1a = "update krs set Tinggi='' where KRSID='$ada[KRSID]' ";
					$r1a = _query($s1a);
				}
			}
			// Jika yg lama lebih tinggi, maka ga usah diapa2in
			//else {			
			//}
		}

	// delete yang tidak NilaiTertinggi (double data)
	//$s = "delete from krs where Tinggi=''";
	//$r = _query($s);

	// Menghitung ulang jumlah SKS yang diambil oleh Mhs
  /*$s = "select MhswID, KHSID, TahunID
		from khs
		where KodeID = '".KodeID."'				
		order by MhswID";
	$r = _query($s);
	while ($w = _fetch_array($r)) 
	{		    
		$khsid = $w['KHSID'];
    $sks = GetaField('krs', "TahunID = '$w[TahunID]' and NA = 'N' and MhswID", $w['MhswID'], "sum(SKS)")+0;
    $totalsks = $sks; //GetaField('khs', "MhswID='$w[MhswID]' and KodeID", KodeID, "sum(SKS)")+$sks; // and Sesi < $w[Sesi]
  
    $s1 = "update khs set SKS = '$sks', TotalSKS = '$totalsks' where KHSID = '$khsid' ";
    //$r1 = _query($s1);
	}*/
	
  echo "<script>window.close()</script>";
}

function GetDateOptionReadOnly($dt, $nm='dt') {
  $ro = "readonly=true";
  $arr = Explode('-', $dt);
  $_dy = GetNumberOption(1, 31, $arr[2]);
  $_mo = GetMonthOption($arr[1]);
  $_yr = GetNumberOption(1930, Date('Y')+2, $arr[0]);
  return "<select name='".$nm."_d' $ro>$_dy</select>
    <select name='".$nm."_m' $ro>$_mo</select>
    <select name='".$nm."_y' $ro>$_yr</select>";
}

?>
