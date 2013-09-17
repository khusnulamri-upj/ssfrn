<?php 
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 22 Desember 2008

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'HeaderTranskrip' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function HeaderTranskrip() {
  $MhswID = GetSetVar('MhswID');
  TampilkanJudul("Cetak Transkrip Nilai");
  $tombols = '&nbsp;';
  if (empty($_SESSION['MhswID'])) {
    $mhsw = array();
  } else {
    $mhsw = GetFields("mhsw m 
      left outer join prodi prd on m.ProdiID=prd.ProdiID and prd.KodeID='".KodeID."'
			left outer join jenjang j on prd.JenjangID=j.JenjangID
      left outer join program prg on m.ProgramID=prg.ProgramID and prg.KodeID='".KodeID."'
      left outer join dosen d on m.PenasehatAkademik=d.Login and d.KodeID='".KodeID."'", 
      "m.MhswID='$_SESSION[MhswID]' and m.KodeID", KodeID, 
      "m.MhswID, m.Nama, m.ProgramID, m.ProdiID, m.PenasehatAkademik,
      d.Nama as NamaDosen, d.Gelar, j.Nama as _Jenjang,
      prd.Nama as _PRD, prg.Nama as _PRG");

		$JmlData = GetaField('krs k left outer join jadwal j on k.JadwalID=j.JadwalID', "k.MhswID = '$mhsw[MhswID]' and k.Tinggi = '*' and k.SKS > 0 and k.NA = 'N' and k.KodeID", KodeID, "count(k.MKKode)")+0; // jumlah data yang diload		
    if (empty($mhsw)) { 
			$mhsw = array();
		} else {
      if (empty($mhsw['NamaDosen'])) $mhsw['NamaDosen'] = "<font color=red>&times;</font> Belum diset";
      RandomStringScript();
      $tombols = <<<ESD
      <input type=button name='btnTranskrip' value='Transkrip Nilai' onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 0, '$JmlData')" /> 
			<input type=button name='btnTranskrip' value='Transkrip Nilai Tanpa Kop' onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 3, '$JmlData')" /> 
      <input type=button name='btnTranskripPerJenis' value='Transkrip Per Jenis MK' onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 1, '$JmlData')" />
      <input type=button name='btnTranskrip' value='Transkrip Nilai Sementara' onClick="javascript:fnCetakTranskrip('$mhsw[MhswID]', 2, '$JmlData')" />	  
      <script>

      function fnCetakTranskrip(MhswID, jen, JmlData) {				
        var _rnd = randomString();				
        lnk = "$_SESSION[mnux].php?gos=_CetakTranskrip&MhswID="+MhswID+"&_rnd="+_rnd+"&jen="+jen+"&JmlData="+JmlData;
        win2 = window.open(lnk, "", "width=700, height=500, scrollbars");
        if (win2.opener == null) childWindow.opener = self;
      }
			function fnSubmit() {
				gosState = frmHeader.gosx.value;
				switch (gosState) {
					case '0':
						// cari
						frmHeader.gos.value = '';						
						break;
					case '1':
						// simpan
						frmHeader.gos.value = 'Simpan';
						break;
					default:
						// cari
						frmHeader.gos.value = '';
				}
				//alert (frmHeader.gos.value);
				frmHeader.submit();
			}
      </script>
ESD;
    }
  }

  echo "<form name='frmHeader' action='?' method=POST>
	<table class=box cellspacing=1 align=center width=600>
  <input type=hidden name='gos' value='' />  
	<input type=hidden name='gosx' value='0' />  

  <tr><td class=inp width=80>NIM/NPM:</td>
      <td class=ul width=220>
        <input type=text name='MhswID' value='$_SESSION[MhswID]' size=15 maxlength=50 />
        <input type=button name='btnCari' value='Cari' onClick=\"form.gosx.value='0'; fnSubmit();\" />
      </td>
      <td class=inp width=90>Nama Mhsw:</td>
      <td class=ul>
        <b>$mhsw[Nama]</b> &nbsp;
      </td>
      </tr>
  <tr><td class=inp>Prodi:</td>
      <td class=ul>$mhsw[_PRD] <sup>$mhsw[_PRG]</sup>&nbsp;</td>
      <td class=inp>Penasehat Akd:</td>
      <td class=ul>$mhsw[NamaDosen] <sup>$mhsw[Gelar]</sup>&nbsp;</td>
      </tr>
  <tr><td class=ul colspan=4 align=center>
      $tombols
      </td></tr>
	</table>";
  
	/* Void Table */
	// ------------- 
	echo "<table class=box cellspacing=1 align=center width=800>";		

	$s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi, k.VoidOnTranskripBAA
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'
			and k.SKS > 0
			and k.NA = 'N'
    order by k.MKKode";	

	echo "<tr>
			<th class=ttl width=5>No.</th>					
			<th class=ttl width=10></th>
			<th class=ttl width=50>Kode MK</th>
			<th class=ttl width=200>Nama Mata Kuliah</th>
			<th class=ttl width=20>SKS</th>
			<th class=ttl width=20>Nilai</th>
			<!--<th class=ttl width=15>Bobot</th>	
			<th class=ttl width=15>Mutu</th>-->
			<th class=ttl width=15>Keterangan</th>
		</tr>";
		
  $r = _query($s);		
	$n = 0;		
	while ($w = _fetch_array($r)) {		
		$n++;		
		$mutu = $w['SKS'] * $w['BobotNilai'];
		$_nxk += $mutu;
		$_sks += $w['SKS'];		
		$KRSID = $w['KRSID'];
		$MKKode = $w['MKKode'];
		$Nama = $w['Nama'];
		$SKS = $w['SKS'];
		$GradeNilai = $w['GradeNilai'];
		//$BobotNilai = $w['BobotNilai'];
		//$mutu = $mutu;						
		//if ($w[''] != '') { // validasi empty			
			/*if ($w[''] == '') {
				$TidakMemenuhiSyarat = true;
				$Keterangan = "x Kosong";
			} else {
				$TidakMemenuhiSyarat = false;
				$w['Keterangan'] = '';
			}*/
			$VoidOnTranskripBAA = $w['VoidOnTranskripBAA'];	
			if ($VoidOnTranskripBAA == 'Y') { // sudah divoid
				$TidakMemenuhiSyarat = true;
				$TampilkanBaris = true;
				$Keterangan = 'Sudah Di-Void';								
			} else { // belum divoid
				$TidakMemenuhiSyarat = false;
				$TampilkanBaris = true;
				$Keterangan = '-';
			}		
												
			if($TidakMemenuhiSyarat) $checkbox = "<input type=checkbox name='CheckBox$n' value='Y' title='Kosongkan untuk Mem-Void'>"; //"&times"
			else $checkbox = "<input type=checkbox name='CheckBox$n' value='Y' checked=true title='Kosongkan untuk Mem-Void'>";			
			$class = "cna".(($TidakMemenuhiSyarat)? 'Y' : 'N');						
			
			if ($TampilkanBaris) {
				echo "<tr>
						<td class=inp>$n</td>
						<td class=$class align=center>$checkbox<input type=hidden name='KRSID$n' value='$KRSID'></td>
						<td class=$class align=center>$MKKode<input type=hidden name='MKKode$n' value='$MKKode'></td>						
						<td class=$class align=left>$Nama<input type=hidden name='Nama$n' value='$Nama'></td>
						<td class=$class align=center>$SKS<input type=hidden name='SKS$n' value='$SKS'></td>
						<td class=$class align=center>$GradeNilai<input type=hidden name='GradeNilai$n' value='$GradeNilai'></td>
						<td class=$class align=center>$Keterangan</td>
					 </tr>";
			} else {
				echo "";
			}
		//} // endif validasi empty
	} // endfor 

	// Tampilkan summary-nya
	/*$p->SetFont('Helvetica', 'B', 6);
	$p->Cell(70, $t, 'JUMLAH:', 'LB', 0, 'R');
	$p->Cell(24, $t, $_sks, 'BR', 0, 'C');
	//$p->Cell(0, $t, '', 'BR', 0);
	//$p->Cell(1, $t, $_nxk, 'BR', 0, 'C');*/
	echo "<input type=hidden name='JumlahData' value='$n'>";
	echo "<tr><td class=ul1 align=center colspan=16><input type=button name='Simpan' value='Simpan' onClick=\"form.gosx.value='1'; fnSubmit();\"></td></tr>	
	</table>";
	/* End of Void Table */
		
  echo "</form></p>";

}

function Simpan() { 
	$JumlahData = $_REQUEST['JumlahData']+0;		  	
  for($i = 1; $i <= $JumlahData; $i++) {	
		$CheckBox = $_REQUEST['CheckBox'.$i];  
		$KRSID = $_REQUEST['KRSID'.$i];
		if(empty($CheckBox)) {	// Void	= Yes																										
			$s = "update krs set VoidOnTranskripBAA='Y' where KRSID='$KRSID'";									
		} else { // // Void = No
			$s = "update krs set VoidOnTranskripBAA='N' where KRSID='$KRSID'";
		} 
		$r = _query($s);		
  } 
	BerhasilSimpan("?mnux=$_SESSION[mnux]&gos=", 100);
}

function JudulKolomnya($p) {
  // Judul tabel
  $t = 6;
  $p->SetFont('Helvetica', 'B', 6);
  $p->Cell(6, $t, '#', 1, 0, 'C');
  $p->Cell(12, $t, 'Kode MK', 1, 0, 'C');
  $p->Cell(60, $t, 'Nama Mata Kuliah', 1, 0, 'C');
  $p->Cell(8, $t, 'SKS', 1, 0, 'C');
  $p->Cell(8, $t, 'Nilai', 1, 0, 'C');
  //$p->Cell(15, $t, 'Bobot', 1, 0, 'C');
  //$p->Cell(15, $t, 'Mutu', 1, 0, 'C');
  $p->Ln($t); 
}

function JudulKolomnya1($p) {
  // Judul tabel
  $t = 6;
  $p->SetFont('Helvetica', 'B', 6);
  $p->Cell(10, $t, '#', 1, 0, 'C');
  $p->Cell(24, $t, 'Kode MK', 1, 0, 'C');
  $p->Cell(90, $t, 'Nama Mata Kuliah', 1, 0, 'C');
  $p->Cell(15, $t, 'SKS', 1, 0, 'C');
  $p->Cell(15, $t, 'Nilai', 1, 0, 'C');
  $p->Cell(15, $t, 'Bobot', 1, 0, 'C');
  $p->Cell(15, $t, 'Mutu', 1, 0, 'C');
  $p->Ln($t); 
}

function JudulKolomnya2($p) {
  // Judul tabel
  $t = 6;
  $p->SetFont('Helvetica', 'B', 6);
  $p->Cell(10, $t, '#', 1, 0, 'C');
  $p->Cell(24, $t, 'Kode MK', 1, 0, 'C');
  $p->Cell(90, $t, 'Nama Mata Kuliah', 1, 0, 'C');
  $p->Cell(15, $t, 'SKS', 1, 0, 'C');
  $p->Cell(15, $t, 'Nilai', 1, 0, 'C');
  $p->Cell(15, $t, 'Bobot', 1, 0, 'C');
  $p->Cell(15, $t, 'Mutu', 1, 0, 'C');
  $p->Ln($t); 
}

function BuatHeaderTranskrip($mhsw, $jen, $p) {
  $lbr = 190;
  $p->SetFont('Times', 'B', 14);
  
  if($jen < 2 )$p->Cell($lbr, 8, "Transkrip Nilai Akademik", 0, 1, 'C');
  if($jen == 2)$p->Cell($lbr, 8, "Kutipan Nilai Akademik", 0, 1, 'C'); //Transkrip Nilai Akademik Sementara
  
  $s = "select DISTINCT(m.KonsentrasiID) as _KonsentrasiID, COUNT(k.KRSID) as _countKID  
			from krs k left outer join mk m on m.MKID=k.MKID and m.KodeID='".KodeID."'
			where k.MhswID='$mhsw[MhswID]' and m.KonsentrasiID!=0 and k.KodeID='".KodeID."'
			group by m.KonsentrasiID
			order by _countKID DESC";
  $r = _query($s);
  $w = _fetch_array($r);
  
  $konsentrasi = (empty($w['_KonsentrasiID']))? "-" : GetaField("konsentrasi", "KonsentrasiID='$w[_KonsentrasiID]' and KodeID", KodeID, "Nama");
  
  $arr = array();
  $arr[] = array("NIM", ':', $mhsw['MhswID'], 'Jenjang', ':', $mhsw['_Jenjang']);
  $arr[] = array('Nama', ':', $mhsw['Nama'], 'Program Studi', ':', $mhsw['_PRD']);
  $arr[] = array('Tempat/Tgl Lahir', ':', $mhsw['TempatLahir'] . ', ' . $mhsw['_TanggalLahir'], 'Konsentrasi', ':', $konsentrasi);
  
  $t = 6;
  foreach ($arr as $a) {
    // Kolom 1
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(30, $t, $a[0], 0, 0);
    $p->Cell(3, $t, $a[1], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 10);
    $p->Cell(60, $t, $a[2], 0, 0);
    $p->Cell(10);
    // Kolom 2
    $p->SetFont('Helvetica', '', 10);
    $p->Cell(30, $t, $a[3], 0, 0);
    $p->Cell(3, $t, $a[4], 0, 0);
    
    $p->SetFont('Helvetica', 'B', 10);
    $p->Cell(50, $t, $a[5], 0, 0);
    
    $p->Ln($t);
  }
  $p->Ln(2);
	//Save ordinate
  $p->y0=$p->GetY();    
}

function BuatIsiTranskrip0($mhsw, $p) {
	JudulKolomnya($p);
  // Reset nilai tertinggi
  ResetNilaiTertinggi($mhsw);
  BuatNilaiTertinggi($mhsw);
  // Tampilkan isinya
  $s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi, k.VoidOnTranskripBAA
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'
			and k.SKS > 0
			and k.NA = 'N'
			and k.VoidOnTranskripBAA = 'N'
    order by k.MKKode";
  $r = _query($s); 
	$n = 0;  
  $p->SetFont('Helvetica', '', 5);
  $t = 5; 
	$_sks = 0; 
	$_nxk = 0;
  $maxDataPstart = 41; // maksimum jml data di page pertama
	$maxDataPend = 41; // maksimum jml data di page terakhir
	$maxDataPbetw = 48; // maksimum jml data di page antara
	$counterData = 1; 
	$xxx = 1; // faktor pengali duplikasi data untuk simulasi
	$jmlData = GetaField('krs k left outer join jadwal j on k.JadwalID=j.JadwalID', "k.MhswID = '$mhsw[MhswID]' and k.Tinggi = '*' and k.SKS > 0 and VoidOnTranskripBAA = 'N' and k.NA = 'N' and k.KodeID", KodeID, "count(k.MKKode)")+0; // jumlah data yang diload		
	$jmlData = $jmlData*$xxx;
	$jmlDataSelisih = $jmlData % 2;
	$jmlDataTengah = (($jmlData - $jmlDataSelisih) / 2) ;// + $jmlDataSelisih;

	$batasMinDataPend = 5; // banyaknya data di page terakhir yg apabila < 5 maka sisanya ambil dari page sebelumnya
	
	// Mengecek cases jml page satu per satu
	if ($jmlData <= (1*41)) { // 1 page
		$jmlDataPend = ($jmlData - $maxDataPstart) % $maxDataPbetw; // menghitung banyaknya data di page terakhir	
		if ($jmlDataPend > 0) { // ada >= 1 data di page terakhir => $jmlDataPend tidak pernah lebih besar dari 0 jadi tidak akan masuk ke if ini			
		} else { // cek apakah footer menggantung di page terakhir			
			if ($jmlDataPend == 0) { // page sebelum page terakhir banyak datanya ngepas mengisi page tsb, maka harus dipotong 5 data
				$faktorPenggeser = $batasMinDataPend;
				$posStartGeserPbef_end = $jmlData - $faktorPenggeser; 
			} 
			elseif ($jmlDataPend < 0) { // cek apakah footer masih menggantung
				if (abs($jmlData - $maxDataPstart) <= $batasMinDataPend+3) { // masih menggantung
					$faktorPenggeser = $batasMinDataPend+(abs($jmlDataPend)-1);
					$posStartGeserPbef_end = $jmlData - $faktorPenggeser+(abs($jmlDataPend)-1);
				} else {
					$faktorPenggeser = 0;
					$posStartGeserPbef_end = 0;
				}
			}
		}	
	} 
	elseif ($jmlData <= (2*41) && $jmlData > (1*41)) { // 2 page		
		$maxDataPbetw = 41;
		$jmlDataPend = ($jmlData - $maxDataPstart) % $maxDataPbetw; // menghitung banyaknya data di page terakhir	
		if ($jmlDataPend > 0) { // ada >= 1 data di page terakhir maka ambil sisanya dari page 1
			if ($jmlDataPend < $batasMinDataPend) { // data di page terakhir kurang dari 5, faktor penggeser diisi sisanya dari page 1
				$faktorPenggeser = $batasMinDataPend - $jmlDataPend;
			} else { // data di page terakhir normal atau >= 5
				$faktorPenggeser = 0;
			}	
			$posStartGeserPbef_end = $jmlData - $faktorPenggeser - $jmlDataPend;
		} else { // genap 2*41, $jmlDataPend akan = 0
			$faktorPenggeser = $batasMinDataPend*2+1;
			$posStartGeserPbef_end = $jmlData - ($faktorPenggeser-1)/2;
		}	
	}
	elseif ($jmlData >= (3*41)) { // >= 3 page
		$jmlDataPend = ($jmlData - $maxDataPstart) % $maxDataPbetw; // menghitung banyaknya data di page terakhir	
		if ($jmlDataPend > 0) { // ada >= 1 data di page terakhir 
			if ($jmlDataPend < $batasMinDataPend) { // data di page terakhir kurang dari 5, faktor penggeser diisi sisanya
				$faktorPenggeser = $batasMinDataPend;
				$posStartGeserPbef_end = $jmlData - $faktorPenggeser;
			} else { // data di page terakhir normal atau >= 5, cek apakah footer menggantung
				if ($maxDataPbetw - $jmlDataPend <= $batasMinDataPend) { // menggantung, geser 5
					$faktorPenggeser = $batasMinDataPend+1;
					$posStartGeserPbef_end = $jmlData - $faktorPenggeser + 1;
				} else {
					$faktorPenggeser = 0;
					$posStartGeserPbef_end = 0;
				}
			}				
		} else { // tidak ada data di page terakhir
			// cek apakah footer menggantung di page terakhir
			if ($jmlDataPend == 0) { // page sebelum page terakhir banyak datanya ngepas mengisi page tsb, maka harus dipotong 5 data
				$faktorPenggeser = $batasMinDataPend;
				$posStartGeserPbef_end = $jmlData - $faktorPenggeser; //$posStartGeserPbef_end = ($jmlData - $maxDataPend) - $faktorPenggeser;
			} 
			elseif ($jmlDataPend < 0) {
				if (abs($jmlData - $maxDataPstart) <= $batasMinDataPend+3) { // masih menggantung
					$faktorPenggeser = $batasMinDataPend+(abs($jmlDataPend)-1);
					$posStartGeserPbef_end = $jmlData - $faktorPenggeser+(abs($jmlDataPend)-1);
				}
			}
		}	
	}			
			
	$p->SetFont('Helvetica', '', 5);
	$jmlCol = 1;
	while ($w = _fetch_array($r)) {		
		for ($xx=1; $xx<=$xxx; $xx++) {
			if ($counterData == 83) { // mencapai end of page 1, (83 = 2*41 + 1)
				JudulKolomnya($p);			
				//Save ordinate
				$p->y0=$p->GetY()-$t;
			} 
			//else if ($counterData == 83) {}
			$p->SetFont('Helvetica', '', 5);

			/*if ($faktorPenggeser > 0) {
				//if ($jmlDataPend < $batasMinDataPend) { // ada pergeseran dapat diketahui dengan membandingkan jmlDataPend dengan batasMinDataPend
					if ($counterData == $posStartGeserPbef_end+1) { // +1 untuk Jumlah
						for ($kaliGeser=1; $kaliGeser<=$faktorPenggeser+1; $kaliGeser++) { // +1 faktorPenggeser untuk Jumlah
							$p->Cell(6, $t, '', 0, 0, 'C');
							$p->Cell(12, $t, '', 0, 0);
							$p->Cell(60, $t, '', 0, 0);
							$p->Cell(8, $t, '', 0, 0, 'C');
							$p->Cell(8, $t, '', 0, 0, 'C');
							//$p->Cell(15, $t, '', 0, 0, 'C');
							//$p->Cell(15, $t, '', 0, 0, 'C');
							$p->Ln($t);
						}
					} 
				//}
			}	*/		
		
			$n++;
			$mutu = $w['SKS'] * $w['BobotNilai'];
			$_nxk += $mutu;
			$_sks += $w['SKS'];

			if ($counterData <= $jmlDataTengah) {
				$p->Cell(6, $t, $n, 1, 0, 'C');
				$p->Cell(12, $t, $w['MKKode'], 1, 0, 'C');
				$p->Cell(60, $t, $w['Nama'], 1, 0);
				$p->Cell(8, $t, $w['SKS'], 1, 0, 'C');
				$p->Cell(8, $t, $w['GradeNilai'], 1, 0, 'C');
				//$p->Cell(15, $t, $w['BobotNilai'], 1, 0, 'C');
				//$p->Cell(15, $t, $mutu, 1, 0, 'C');
				$p->Ln($t);		
			} else {			
				if ($counterData == ($jmlDataTengah+1)) {
					$p->SetY(63);	
					$jmlCol++;
					// Judul tabel					
					$p->SetX(108);	
					$t = 6;
					$p->SetFont('Helvetica', 'B', 6);
					$p->Cell(6, $t, '#', 1, 0, 'C');
					$p->Cell(12, $t, 'Kode MK', 1, 0, 'C');
					$p->Cell(60, $t, 'Nama Mata Kuliah', 1, 0, 'C');
					$p->Cell(8, $t, 'SKS', 1, 0, 'C');
					$p->Cell(8, $t, 'Nilai', 1, 0, 'C');
					//$p->Cell(15, $t, 'Bobot', 1, 0, 'C');
					//$p->Cell(15, $t, 'Mutu', 1, 0, 'C');
					$p->Ln($t); 					
				}
				$p->SetX(108);				
				// Data
				$t = 5;
				//$p->SetY(63);	
				//$p->SetX(108);
				$p->SetFont('Helvetica', '', 5);
				$p->Cell(6, $t, $n, 1, 0, 'C');
				$p->Cell(12, $t, $w['MKKode'], 1, 0, 'C');
				$p->Cell(60, $t, $w['Nama'], 1, 0);
				$p->Cell(8, $t, $w['SKS'], 1, 0, 'C');
				$p->Cell(8, $t, $w['GradeNilai'], 1, 0, 'C');
				//$p->Cell(15, $t, $w['BobotNilai'], 1, 0, 'C');
				//$p->Cell(15, $t, $mutu, 1, 0, 'C');
				$p->Ln($t);		
			}
			$counterData++;		
		}		
	}	
	if ($jmlCol == 2) {
		$p->SetX(108);
	} else {
		$p->SetY($p->GetY()+6);
	} 
  // Tampilkan jumlahnya
  $p->SetFont('Helvetica', 'B', 6);
  $p->Cell(70, $t, 'JUMLAH:', '', 0, 'R');
  $p->Cell(24, $t, $_sks, '', 0, 'C');
  //$p->Cell(0, $t, '', 'BR', 0);
  //$p->Cell(1, $t, $_nxk, 'BR', 0, 'C');
  $p->Ln($t);
  $p->Ln(2);		
}

function BuatIsiTranskrip1($mhsw, $p) {
	JudulKolomnya1($p);
  // Reset nilai tertinggi
  ResetNilaiTertinggi($mhsw);
  BuatNilaiTertinggi($mhsw);
  // Tampilkan isinya
  $s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi, k.VoidOnTranskripBAA,
      j.JenisMKID, j.Urutan, j.Singkatan, j.Nama as JenisMK
    from krs k
      left outer join mk m on k.MKID=m.MKID and m.KodeID='".KodeID."'
      left outer join jenismk j on m.JenisMKID = j.JenisMKID and j.KodeID='".KodeID."'
			left outer join jadwal jd on jd.JadwalID=k.JadwalID
	where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'
			and k.VoidOnTranskripBAA = 'N'
			and k.SKS > 0
    order by j.Urutan, k.MKKode";
  $r = _query($s); $n = 0;
  
  $t = 5; $_sks = 0; $_nxk = 0;
  $lbr = 184;
  $jenismkid = '-19721222';
  
  while ($w = _fetch_array($r)) {
    if ($jenismkid != $w['JenisMKID']) {
      $jenismkid = $w['JenisMKID'];
      $p->SetFont('Helvetica', 'B', 7);
      $p->Cell($lbr, $t, $w['JenisMK'] . ' (' . $w['Singkatan']. ')', 'LBR', 1);
      $n = 0;
    }
    $p->SetFont('Helvetica', '', 5);
    $n++;
    $mutu = $w['SKS'] * $w['BobotNilai'];
    $_nxk += $mutu;
    $_sks += $w['SKS'];
    $p->Cell(10, $t, $n, 'LB', 0, 'C');
    $p->Cell(24, $t, $w['MKKode'], 'B', 0);
    $p->Cell(90, $t, $w['Nama'], 'B', 0);
    $p->Cell(15, $t, $w['SKS'], 'B', 0, 'C');
    $p->Cell(15, $t, $w['GradeNilai'], 'B', 0, 'C');
    $p->Cell(15, $t, $w['BobotNilai'], 'B', 0, 'C');
    $p->Cell(15, $t, $mutu, 'BR', 0, 'C');
    $p->Ln($t);
  }
  // Tampilkan jumlahnya
  $p->SetFont('Helvetica', 'B', 6);
  $p->Cell(124, $t, 'JUMLAH:', 'LB', 0, 'R');
  $p->Cell(15, $t, $_sks, 'B', 0, 'C');
  $p->Cell(30, $t, '', 'B', 0);
  $p->Cell(15, $t, $_nxk, 'BR', 0, 'C');
  $p->Ln($t);
  $p->Ln(2);
}

function BuatIsiTranskrip2($mhsw, $p) {
	JudulKolomnya2($p);
  // Reset nilai tertinggi
  ResetNilaiTertinggi($mhsw);
  BuatNilaiTertinggi($mhsw);
  // Tampilkan isinya
  $s = "select k.KRSID, k.MKKode, k.Nama, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi, k.JadwalID, k.VoidOnTranskripBAA
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
      and k.Tinggi = '*'			
			and k.SKS > 0
			and k.VoidOnTranskripBAA = 'N'
    order by k.MKKode"; //and k.Final = 'Y'
  $r = _query($s); $n = 0;
    
  $t = 6; $_sks = 0; $_nxk = 0;
  
	$counterData = 1;
  while ($w = _fetch_array($r)) {
		if ($counterData == 35 || ($counterData > 73 && ($counterData-35)%39 == 0)) { // masuk ke page baru => buat header kolom tabelnya
			JudulKolomnya2($p);
		}

		$p->SetFont('Helvetica', '', 5);
    $n++;
    $mutu = $w['SKS'] * $w['BobotNilai'];
    $_nxk += $mutu;
    $_sks += $w['SKS'];
    $p->Cell(10, $t, $n, 1, 0, 'C');
    $p->Cell(24, $t, $w['MKKode'], 1, 0);
    $p->Cell(90, $t, $w['Nama'], 1, 0);
    $p->Cell(15, $t, $w['SKS'], 1, 0, 'C');
    $p->Cell(15, $t, $w['GradeNilai'], 1, 0, 'C');
    $p->Cell(15, $t, $w['BobotNilai'], 1, 0, 'C');
    $p->Cell(15, $t, $mutu, 1, 0, 'C');
    $p->Ln($t);
		$counterData++;
  }
  // Tampilkan jumlahnya
  $p->SetFont('Helvetica', 'B', 6);
  $p->Cell(124, $t, 'JUMLAH:', 'LB', 0, 'R');
  $p->Cell(15, $t, $_sks, 'B', 0, 'C');
  $p->Cell(30, $t, '', 'B', 0);
  $p->Cell(15, $t, $_nxk, 'BR', 0, 'C');
  $p->Ln($t);
  $p->Ln(2);
}

function BuatFooterTranskrip($mhsw, $jen, $p) {
  $krs = array();
  if($jen < 2)
  {  $krs = GetFields('krs', "MhswID='$mhsw[MhswID]' and Tinggi='*' and VoidOnTranskripBAA = 'N' and KodeID",
		KodeID, "sum(SKS) as _SKS, sum(SKS*BobotNilai) as _NXK");
  }
  else if($jen == 2)
  {	  $krs = GetFields('krs', "MhswID='$mhsw[MhswID]' and Tinggi='*' and VoidOnTranskripBAA = 'N' and KodeID",
		KodeID, "sum(SKS) as _SKS, sum(SKS*BobotNilai) as _NXK"); // and Final='Y'
  }
  $s = "select * from nilai where ProdiID='$mhsw[ProdiID]' and Lulus='N' and KodeID='".KodeID."'";
  $r = _query($s);
  $whr_gagal = '';
  while($w = _fetch_array($r))
  {	$whr_gagal .= " and GradeNilai != '$w[Nama]' ";
  }
  
  $SKSLulus = '';
  if($jen < 2)
  {  $SKSLulus = GetaField('krs', "MhswID='$mhsw[MhswID]' and Tinggi='*' and Final='Y' $whr_gagal and GradeNilai != '-' and VoidOnTranskripBAA = 'N' and KodeID",
		KodeID, "sum(SKS)");	
  }
  else if($jen == 2)
  {	 $SKSLulus = GetaField('krs', "MhswID='$mhsw[MhswID]' and Tinggi='*' and Final='Y' $whr_gagal and GradeNilai != '-' and VoidOnTranskripBAA = 'N' and KodeID",
		KodeID, "sum(SKS)");
  }
  $_sks = $krs['_SKS']+0;
  $_nxk = $krs['_NXK']+0;
  // Buat footernya
  $ipk = ($_sks > 0)? $_nxk / $_sks : 0;
  $_ipk = number_format($ipk, 2);
  $predikat = GetaField("predikat", "ProdiID='$mhsw[ProdiID]' and IPKMin <= $_ipk and $_ipk <= IPKMax and KodeID", 
    KodeID, 'Nama');
  $identitas = GetFields('identitas', 'Kode', KodeID, '*');
  $tgl = date('d M Y');
  
  $prd = GetFields('prodi', "ProdiID='$mhsw[ProdiID]' and KodeID", KodeID, '*');
  $pjbt = GetFields('pejabat', "KodeJabatan='KETUA' and KodeID", KodeID, '*');
    
	$arr = array();
	$t = 4;
	if ($jen == 0) { // Academic Result		
		// TTD
		$fn = "../ttd/$pjbt[KodeJabatan].ttd.gif";
		if (file_exists($fn)) {	
			$img_ttd = $fn; 
			//$pdf->Image($img_ttd,173,57,11);
		} else {
			$fn = '';
			$img_ttd = $fn; 
		}			
		$arr[] = array();
		$arr[] = array('Jumlah SKS yang lulus', ':', $SKSLulus . ' SKS', $identitas['Kota'] . ', '. $tgl);
		$arr[] = array('Jumlah SKS yang diperoleh', ':', $_sks . ' SKS', $pjbt['Jabatan']);
		$arr[] = array('Jumlah SKS yang harus ditempuh', ':', $prd['TotalSKS'] . ' SKS');
		$arr[] = array('Jumlah Nilai Mutu (N x K)', ':', $_nxk);
		$arr[] = array();
		$arr[] = array('~Indeks Prestasi Kumulatif (IPK)', ':', $_ipk);
		$arr[] = array('@'.$img_ttd);	
		$arr[] = array('~Predikat Kelulusan', ':', $predikat, $pjbt['Nama']);
		$arr[] = array('', '', '', 'NIP. ' . $pjbt['NIP']);
		//$arr[] = array();
		//$arr[] = array($identitas['Kota'] . ', '. $tgl);
		//$arr[] = array($pjbt['Jabatan']);
		//$arr[] = array();
		//$arr[] = array();
		
		// TTD
		/*$fn = "../ttd/$pjbt[KodeJabatan].ttd.gif";
		if (file_exists($fn)) {	
			$img_ttd = $fn; 
			//$pdf->Image($img_ttd,173,57,11);
		} else {
			$fn = '';
			$img_ttd = $fn; 
		}
		$arr[] = array('@'.$img_ttd);		*/
		//$arr[] = array();
		//$arr[] = array();				
		//$arr[] = array('~'.$pjbt['Nama']);		
		//$arr[] = array('NIP. ' . $pjbt['NIP']);
		
		// Tampilkan				
		foreach ($arr as $a) {
			if ($a[0][0] == '@') {
				$a[0] = str_replace('@', '', $a[0]);
				$y_pos = $p->GetY()-16;
				$x_pos = $p->GetX()+130;				
				$p->Image($a[0],$x_pos,$y_pos,28);				
				$p->Cell(3, $t, $a[1], 0, 0);
				$p->Cell(60, $t, $a[2], 0, 0);
				
				$p->Cell(10);
				$p->Cell(60, $t, $a[3], 0, 0);
				$p->Ln($t);
			} else {
				$b = ($a[0][0] == '~')? 'B' : '';
				$a[0] = str_replace('~', '', $a[0]);
				$p->SetFont('Helvetica', $b, 9);
				$p->Cell(55, $t, $a[0], 0, 0);
				$p->Cell(3, $t, $a[1], 0, 0);
				$p->Cell(60, $t, $a[2], 0, 0);
				
				$p->Cell(10);
				$p->Cell(60, $t, $a[3], 0, 0);
				$p->Ln($t);
			}
		}
	} else { // Kutipan Transkrip		
		// TTD
		$fn = "../ttd/$pjbt[KodeJabatan].ttd.gif";
		if (file_exists($fn)) {	
			$img_ttd = $fn; 
			//$pdf->Image($img_ttd,173,57,11);
		} else {
			$fn = '';
			$img_ttd = $fn; 
		}			
		$arr[] = array('Jumlah SKS yang lulus', ':', $SKSLulus . ' SKS', $identitas['Kota'] . ', '. $tgl);
		$arr[] = array('Jumlah SKS yang diperoleh', ':', $_sks . ' SKS', $pjbt['Jabatan']);
		$arr[] = array('Jumlah SKS yang harus ditempuh', ':', $prd['TotalSKS'] . ' SKS');
		$arr[] = array('Jumlah Nilai Mutu (N x K)', ':', $_nxk);
		$arr[] = array();
		$arr[] = array('~Indeks Prestasi Kumulatif (IPK)', ':', $_ipk);
		$arr[] = array('@'.$img_ttd);	
		$arr[] = array('~Predikat Kelulusan', ':', $predikat, $pjbt['Nama']);		
		$arr[] = array('', '', '', 'NIP. ' . $pjbt['NIP']);
		// Tampilkan	
		foreach ($arr as $a) {
			if ($a[0][0] == '@') {
				$a[0] = str_replace('@', '', $a[0]);
				$y_pos = $p->GetY()-16;
				$x_pos = $p->GetX()+130;				
				$p->Image($a[0],$x_pos,$y_pos,30);				
				$p->Cell(3, $t, $a[1], 0, 0);
				$p->Cell(60, $t, $a[2], 0, 0);
				
				$p->Cell(10);
				$p->Cell(60, $t, $a[3], 0, 0);
				$p->Ln($t);
			} else {
				$b = ($a[0][0] == '~')? 'B' : '';
				$a[0] = str_replace('~', '', $a[0]);
				$p->SetFont('Helvetica', $b, 9);
				$p->Cell(55, $t, $a[0], 0, 0);
				$p->Cell(3, $t, $a[1], 0, 0);
				$p->Cell(60, $t, $a[2], 0, 0);
				
				$p->Cell(10);
				$p->Cell(60, $t, $a[3], 0, 0);
				$p->Ln($t);
			}
		}
	}  
}

function ResetNilaiTertinggi($mhsw) {
  $s = "update krs set Tinggi = '' where MhswID='$mhsw[MhswID]' and KodeID='".KodeID."' ";
  $r = _query($s);
}

function BuatNilaiTertinggi($mhsw) {
  // Ambil semuanya dulu
  $s = "select k.KRSID, k.MKKode, k.BobotNilai, k.GradeNilai, k.SKS, k.Tinggi
    from krs k left outer join jadwal j on k.JadwalID=j.JadwalID
    where k.KodeID = '".KodeID."'
      and k.MhswID = '$mhsw[MhswID]'
    order by k.MKKode";
  $r = _query($s);
  
  while ($w = _fetch_array($r)) {
    $ada = GetFields('krs', "Tinggi='*' and KRSID<>'$w[KRSID]' and MhswID='$mhsw[MhswID]' and MKKode", $w['MKKode'], '*');
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
    else {
    }
  }
}

function _CetakTranskrip() {
  session_start();
  include_once "../dwo.lib.php";
  include_once "../db.mysql.php";
  include_once "../connectdb.php";
  include_once "../parameter.php";
  include_once "../cekparam.php";

	$MhswID = $_REQUEST['MhswID'];
	$JmlData = $_REQUEST['JmlData'];
  $mhsw = GetFields("mhsw m 
      left outer join prodi prd on m.ProdiID=prd.ProdiID and prd.KodeID='".KodeID."'
      left outer join jenjang j on j.JenjangID=prd.JenjangID
	  left outer join program prg on m.ProgramID=prg.ProgramID and prg.KodeID='".KodeID."'
      left outer join dosen d on m.PenasehatAkademik=d.Login and d.KodeID='".KodeID."'", 
      "m.MhswID='$_SESSION[MhswID]' and m.KodeID", KodeID, 
      "m.MhswID, m.Nama, m.ProgramID, m.ProdiID, m.PenasehatAkademik,
      m.TempatLahir, m.TanggalLahir,
      date_format(m.TanggalLahir, '%d %M %Y') as _TanggalLahir,
      d.Nama as NamaDosen, d.Gelar, j.Nama as _Jenjang,
      prd.Nama as _PRD, prg.Nama as _PRG");	
	$jen = $_REQUEST['jen']; // 0=Transkrip Nilai		1=Transkrip Per Jenis MK		2=Transkrip Nilai Sementara
	switch ($jen) {
		case 0:
			include_once "../header_pdf2b.php";
			break;
		case 1:
			include_once "../header_pdfb.php";
			break;
		case 2:
			include_once "../header_pdfb.php";
			break;
		case 3:
			include_once "../header_pdf2b.php";
			break;
		default:
			include_once "../header_pdfb.php";
	}
    
  // *** Init PDF
  $pdf = new PDF();
  $pdf->SetTitle("Transkrip Nilai");	
  $pdf->AddPage();	
  $lbr = 190;     
  
  $jen = $_REQUEST['jen']+0;	
	if ($jen == 3)
		$jen = 0;
	BuatHeaderTranskrip($mhsw, $jen, $pdf);  	
	$cetak = 'BuatIsiTranskrip'.$jen;
  $cetak($mhsw, $pdf);		  
	//if ($jen <> 0)
		BuatFooterTranskrip($mhsw, $jen, $pdf);
  
  $pdf->Output();
}
?>
