<?php
// Author: Irvandy Goutama
// Email: irvandygoutama@gmail.com
// Start: 29/12/2009

session_start();
include_once "../sisfokampus1.php";
include_once "../util.lib.php";
HeaderSisfoKampus("Upload Data Migrasi (dari format excel)");
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
	TampilkanJudul("Sinkronisasi Migrasi Data");
} else {
	TampilkanJudul("Migrasi Data");
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
					<input type=hidden name='TahunIDup' value=$_REQUEST[TahunIDup] />
					<input type=hidden name='ProdiIDup' value=$_REQUEST[ProdiIDup] />		
					<input type=hidden name='JenisTAIDup' value=$_REQUEST[JenisTAIDup] />
					<input type=hidden name='Sync' value=$Sync />";		
		  $ro = "";
		  
		  echo "<tr>
					<th class=ttl width=10>No.</th>					
					<th class=ttl width=10></th>					
					<th class=ttl width=20>NIM</th>					
					<!--<th class=ttl width=70>Nama Mahasiswa</th>-->
					<!--<th class=ttl width=50>Tgl. Lulus</th>-->
					<th class=ttl width=50>No. Seri Ijazah</th>					
					<th class=ttl width=40>Keterangan</th>
				</tr>";
					
		  $n = 0;
		  //$StatusKehadiranDefault = GetaField('jenispresensi', "Def", "Y", "JenisPresensiID");
		  //$arrCekMultipleKRS = array();
		  //$arrCekMultiplePresensi = array();
		  
		  for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {	
				$n++;
				$w = array(); 			
				$w['MhswID'] = trim($data->sheets[0]['cells'][$i][1]);
				//$w['TahunID'] = trim($data->sheets[0]['cells'][$i][3]);								
				if ($w['MhswID'] != '') {					
					//$w['Nama'] = trim($data->sheets[0]['cells'][$i][2]);					
					$w['NoIjazah'] = trim($data->sheets[0]['cells'][$i][3]);
					$w['SKKeluar'] = trim($data->sheets[0]['cells'][$i][4]);
					$w['TglSKKeluar'] = trim($data->sheets[0]['cells'][$i][5]);
					$w['IPK'] = trim($data->sheets[0]['cells'][$i][6]);
					$w['TotalSKS'] = trim($data->sheets[0]['cells'][$i][7]);
					/*$w['NoTranskrip1'] = trim($data->sheets[0]['cells'][$i][4]);					
					$w['NoTranskrip2'] = trim($data->sheets[0]['cells'][$i][5]);					
					$w['NoTranskrip3'] = trim($data->sheets[0]['cells'][$i][6]);			
					$w['NoTranskrip'] = $w['NoTranskrip1'].'/'.$w['NoTranskrip2'].'/'.$w['NoTranskrip3'];	*/		
					$w['TanggalLulus'] = trim($data->sheets[0]['cells'][$i][11]);
					if ($w['MhswID'] == '') {
						$TidakMemenuhiSyarat = true;
						$w['Keterangan'] = "NIM Kosong'";
					} else {
						$TidakMemenuhiSyarat = false;
						$w['Keterangan'] = '';
					}					
					if ($w['Nama'] == '') {
						$TidakMemenuhiSyarat = true;
						$w['Keterangan'] = "Nama Kosong'";
					} else {
						$TidakMemenuhiSyarat = false;
						$w['Keterangan'] = '';
					}											
					// Cek apakah sudah dimigrasi?					
					$Data_exist = '';//GetaField('alumni', "MhswID='$w[MhswID]' and NA='N' and KodeID", KodeID, "MhswID"); 
										
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
								<td class=$class align=left>$w[MhswID]<input type=hidden name='MhswID$n' value='$w[MhswID]'></td>								
								<!--<td class=$class align=center>$w[Nama]<input type=hidden name='Nama$n' value='$w[Nama]'></td>-->
								<td class=$class align=left>$w[NoIjazah]<input type=hidden name='NoIjazah$n' value='$w[NoIjazah]'>
									<input type=hidden name='SKKeluar$n' value='$w[SKKeluar]'>
									<input type=hidden name='TglSKKeluar$n' value='$w[TglSKKeluar]'>
									<input type=hidden name='IPK$n' value='$w[IPK]'>
									<input type=hidden name='TotalSKS$n' value='$w[TotalSKS]'>
									<input type=hidden name='TanggalLulus$n' value='$w[TanggalLulus]'>
								</td>		
								<!--<td class=$class align=left>$w[NoTranskrip]<input type=hidden name='NoTranskrip$n' value='$w[NoTranskrip]'></td>		
								<td class=$class align=left>$w[TanggalLulus]<input type=hidden name='TanggalLulus$n' value='$w[TanggalLulus]'></td>-->			
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
			$MhswID_Exist = GetaField('alumni', "MhswID", $MhswID, "MhswID");
			if (!$MhswID_Exist) {
				//$Nama = $_REQUEST['Nama'.$i];						
				$NoIjazah = $_REQUEST['NoIjazah'.$i];						
				$SKKeluar = $_REQUEST['SKKeluar'.$i];						
				$TglSKKeluar = $_REQUEST['TglSKKeluar'.$i];						
				$IPK = $_REQUEST['IPK'.$i];						
				$TotalSKS = $_REQUEST['TotalSKS'.$i];						
				$NoTranskrip = '';//$_REQUEST['NoTranskrip'.$i];						
				$TanggalLulus = $_REQUEST['TanggalLulus'.$i];						
											
				if ($Sync == 1) { // untuk proses sinkronisasi				
					// update table
					// ------------
					$s = "update mhsw set  where KodeID='".KodeID."'";
					$r = _query($s);																																					
				} else { // untuk proses upload				
					// update table
					// ------------
					// alumni
					$mhsw = GetFields('mhsw', "MhswID='$MhswID' and KodeID", KodeID, "*");
					$Gelar = GetaField('prodi', "ProdiID='$mhsw[ProdiID]' and KodeID", KodeID, 'Gelar');
					$s = "insert into alumni
								(MhswID, Gelar, 
									Alamat, Kota, KodePos, RT, RW,
									Propinsi, Negara, Telepon, Handphone, Email, LoginBuat, TanggalBuat, KodeID)
								values
								('$MhswID', '$Gelar',
									'$mhsw[Alamat]', '$mhsw[Kota]', '$mhsw[KodePos]', '$mhsw[RT]', '$mhsw[RW]',
									'$mhsw[Propinsi]', '$mhsw[Negara]', '$mhsw[Telepon]', '$mhsw[Handphone]', '$mhsw[Email]', '$_SESSION[_Login]', now(), '".KodeID."')";
					$r = _query($s);					
					// mhsw
					$maxSesi = GetaField('khs', "MhswID='$MhswID' and KodeID", KodeID, "max(Sesi)");
					$TahunKeluar = GetaField('khs', "MhswID='$MhswID' and Sesi='$maxSesi' and KodeID", KodeID, "TahunID"); //and SemesterPendek = 'N'
					$_TanggalLulus = GetaField('mhsw', "MhswID='$MhswID' and KodeID", KodeID, "TanggalLulus");
					$TanggalLulus = (empty($_TanggalLulus)) ? $TanggalLulus : $_TanggalLulus;
					// 2. Set data mhsw, StatusMhswID = 'L', Keluar = 'Y'
					$s2 = "update mhsw
						set StatusMhswID = 'L', 
								NoIjazah = '$NoIjazah', 												
								TanggalLulus = '$TanggalLulus',
								Keluar = 'Y',
								TahunKeluar = '$TahunKeluar',
								SKKeluar = '$SKKeluar',
								TglSKKeluar = '$TglSKKeluar',
								IPK = '$IPK',
								TotalSKS = '$TotalSKS',
								LoginEdit = '$_SESSION[_Login]', TanggalEdit = now()
						where MhswID = '$MhswID' and KodeID='".KodeID."'"; //TahunLulusMhsw = '$TahunKeluar',
					$r2 = _query($s2); //NoTranskrip = '$NoTranskrip', 
				} 							
			} // if ($MhswID_Exist)
		} // end if !empty($CheckBox) 
  } // end for $i <= $JumlahData => insert data	
	// Update Keluar
	$s = "update mhsw set Keluar = 'Y' where StatusMhswID = 'L' or StatusMhswID = 'K' or StatusMhswID = 'D'";
	$r = _query($s);
	// Update IPK
	$s = "select MhswID from mhsw where MhswID != ''	order by MhswID";
	$r = _query($s);
	while ($w = _fetch_array($r)) {
		$maxSesi = GetaField('khs', "MhswID='$MhswID' and KodeID", KodeID, "max(Sesi)");		
		$IPTerakhir = GetaField('khs', "MhswID='$MhswID' and Sesi='$maxSesi' and KodeID", KodeID, "IP");
		$TotalSKS = GetaField('khs', "MhswID='$MhswID' and Sesi='$maxSesi' and KodeID", KodeID, "TotalSKS")+0;
		$s2 = "update mhsw set IPK = '$IPTerakhir' where IPK = 0";
		$r2 = _query($s2);
		$s2 = "update mhsw set TotalSKS='$TotalSKS' where TotalSKS = 0";
		$r2 = _query($s2);
	}
	
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
