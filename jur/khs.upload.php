<?php
// Author: Irvandy Goutama
// Email: irvandygoutama@gmail.com
// Start: 29/12/2009

session_start();
include_once "../sisfokampus1.php";
include_once "../util.lib.php";
HeaderSisfoKampus("Upload Data KHS Migrasi (dari format excel)");
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
	TampilkanJudul("Sinkronisasi Migrasi Data - KHS Mahasiswa");
} else {
	TampilkanJudul("Migrasi Data - KHS Mahasiswa");
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
					<th class=ttl width=20>SKS</th>
					<th class=ttl width=20>IPS</th>
					<th class=ttl width=20>IPK</th>
					<th class=ttl width=20>SKS Total</th>					
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
				$w['SKS'] = trim($data->sheets[0]['cells'][$i][4]);
				$w['IPS'] = trim($data->sheets[0]['cells'][$i][5]);
				$w['IP'] = trim($data->sheets[0]['cells'][$i][6]);
				$w['TotalSKS'] = trim($data->sheets[0]['cells'][$i][7]);
				$w['Sesi'] = trim($data->sheets[0]['cells'][$i][8]);
				$w['ProdiID'] = trim($data->sheets[0]['cells'][$i][9]);
				if ($w['MhswID'] != '') {					
					//$w['Nama'] = trim($data->sheets[0]['cells'][$i][4]);					
					if ($w['MhswID'] == '') {
						$TidakMemenuhiSyarat = true;
						$w['Keterangan'] = "NIM Kosong'";
					} else {
						$TidakMemenuhiSyarat = false;
						$w['Keterangan'] = '';
					}					
					/*if ($w['Nama'] == '') {
						$TidakMemenuhiSyarat = true;
						$w['Keterangan'] = "Nama Kosong'";
					} else {
						$TidakMemenuhiSyarat = false;
						$w['Keterangan'] = '';
					}*/											
					// Cek apakah sudah dimigrasi?					
					$Data_exist = '';//GetaField('khs', "MhswID='$w[MhswID]' and NA='N' and KodeID", KodeID, "MhswID"); 
										
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
								<td class=$class align=center>$w[SKS]<input type=hidden name='SKS$n' value='$w[SKS]'></td>		
								<td class=$class align=center>$w[IPS]<input type=hidden name='IPS$n' value='$w[IPS]'></td>			
								<td class=$class align=center>$w[IP]<input type=hidden name='IP$n' value='$w[IP]'></td>			
								<td class=$class align=center>$w[TotalSKS]<input type=hidden name='TotalSKS$n' value='$w[TotalSKS]'>
									<input type=hidden name='Sesi$n' value='$w[Sesi]'>
									<input type=hidden name='ProdiID$n' value='$w[ProdiID]'>
								</td>								
								
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
				$SKS = $_REQUEST['SKS'.$i];						
				$IPS = $_REQUEST['IPS'.$i];						
				$IP = $_REQUEST['IP'.$i];																
				$TotalSKS = $_REQUEST['TotalSKS'.$i];	
				$Sesi = $_REQUEST['Sesi'.$i];	
				$ProdiID = $_REQUEST['ProdiID'.$i];					
				$ProgramID = GetaField('mhsw', "MhswID", $w['MhswID'], "ProgramID"); 
				if ($Sync == 1) { // untuk proses sinkronisasi				
					// update table
					// ------------
					$s = "update mhsw set  where KodeID='".KodeID."'";
					$r = _query($s);																																					
				} else { // untuk proses upload				
					// update table
					// ------------				
					//if ($MhswID != '20108100311' and $MhswID != '20108300033' and $MhswID != '20108200895') {
						$s = "insert into khs	(MhswID, TahunID, SKS, IPS, IP, TotalSKS, Sesi, ProdiID, ProgramID, StatusMhswID, LoginBuat, TanggalBuat, KodeID)
									values ('$MhswID', '$TahunID', '$SKS', '$IPS', '$IP', '$TotalSKS', '$Sesi', '$ProdiID', '$ProgramID', '$StatusMhswID', '$_SESSION[_Login]', now(), '".KodeID."')";
						$r = _query($s);			
					//}
				} 										
			} // end if !empty($CheckBox) 
	  } // end for $i <= $JumlahData => insert data				
	// Menghitung Sesi
	/*$s = "select MhswID, TahunID, StatusMhswID, ProgramID, ProdiID from mhsw where MhswID != ''	order by MhswID";
	$r = _query($s);
	while ($w = _fetch_array($r)) {
		$MhswID_exist = GetaField('khs', "MhswID", $w['MhswID'], "MhswID"); 
		if ($MhswID_exist) { // hanya yg sedang dimigrasikan		
			$TahunID_max = GetaField('khs', "MhswID", $w['MhswID'], "max(TahunID)"); 
			$sk = "select TahunID from khs where MhswID = '$w[MhswID]' order by TahunID";
			$rk = _query($sk);
			$Sesi = 1;
			while ($wk = _fetch_array($rk)) {
				// Mengurutkan Sesi dan menentukan StatusMhswID
				$StatusMhswID = ($wk['TahunID'] == $TahunID_max) ? $w['StatusMhswID'] : 'A';				
				$sku = "update khs set Sesi = '$Sesi', 
						StatusMhswID = '$StatusMhswID', 
						ProgramID = '$w[ProgramID]', 	
						ProdiID = '$w[ProdiID]'	
					where MhswID = '$w[MhswID]' and TahunID = '$wk[TahunID]'";
				$rku = _query($sku);
				$Sesi++;				
			} // while
		} // if $MhswID_exist
	} // while*/
		
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
