<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 25/11/2008

session_start();
include_once "../sisfokampus1.php";
HeaderSisfoKampus("Proses BIPOT Mhsw");

include_once "../keu/bayarmhsw.lib.php";

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'Prosesnya' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function Prosesnya() {
  $max = $_SESSION['_bptMax']+0;
  $max = ($max == 0)? 10 : $max;
  $page = $_SESSION['_bptPage']+0;
  
  $mulai = $max * $page;
  
  $s = "select h.*, m.Nama as NamaMhsw
    from khs h
      left outer join mhsw m on m.MhswID = h.MhswID and m.KodeID = '".KodeID."'
    where h.KodeID = '".KodeID."'
      and h.TahunID = '$_SESSION[TahunID]'
          and left(m.TahunID, 4) = '$_SESSION[TahunID2]'          
    order by h.MhswID
    limit $mulai, $max";
  $r = _query($s);
  $jml = _num_rows($r);
  if ($jml > 0) {
    while ($w = _fetch_array($r)) {
      $_SESSION['_bptCounter']++;
      $jml = ProsesBIPOT($w['MhswID'], $w['TahunID'])+0;
      $_jml = number_format($jml);
      echo "
      <script>
      parent.fnProgress($_SESSION[_bptCounter], '$w[MhswID]', '$w[NamaMhsw]', '$_jml');
      </script>";
    }
    $_SESSION['_bptPage']++;
    $tmr = 1;
    echo <<<ESD
    <script>
    window.onload=setTimeout("window.location='../$_SESSION[mnux].prc.php'", $tmr);
    </script>
ESD;
  }
  else {
    echo <<<ESD
    <script>
    parent.fnSelesai('$_SESSION[TahunID]', $_SESSION[_bptCounter]);
    </script>
ESD;
  }
}
function ProsesBIPOT($MhswID, $TahunID) {
  // Ambil data
  $mhsw = GetFields('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, "*");
  $khs = GetFields('khs', "KodeID = '".KodeID."' and TahunID = '$TahunID' and MhswID", $MhswID, "*");
  $khslalu = array();
    if ($khs[Sesi] > 1) {
        $sesilalu = $khs[Sesi] - 1;
        $khslalu = GetFields('khs', "KodeID = '" . KodeID . "' and Sesi = '$sesilalu' and MhswID", $mhsw[MhswID], "*");
        /* while(!empty($khslalu))
          {	if($khslalu['StatusMhswID'] != 'A')
          {	$sesilalu = $sesilalu-1;
          $khslalu = GetFields('khs', "KodeID = '".KodeID."' and Sesi = '$sesilalu' and MhswID", $MhswID, "*");
          }
          else
          {	break;
          }
          } */
    }
  // Ambil BIPOT-nya
  $s = "select * 
    from bipot2 
    where BIPOTID = '$mhsw[BIPOTID]'
      and Otomatis = 'Y'
      and NA = 'N'
    order by TrxID, Prioritas";
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    $oke = true;
    // Apakah sesuai dengan status awalnya?
    $pos = strpos($w['StatusAwalID'], ".".$mhsw['StatusAwalID'].".");
    $oke = $oke && !($pos === false);

	// Apakah sesuai dengan status mahasiswanya?
    $pos = strpos($w['StatusMhswID'], ".".$khs['StatusMhswID'].".");
    $oke = $oke && !($pos === false);
	
    // Apakah grade-nya?
    if ($oke) {
      if ($w['GunakanGradeNilai'] == 'Y') {
        $pos = strpos($w['GradeNilai'], ".".$mhsw['GradeNilai'].".");
        $oke = $oke && !($pos === false);
      }
    }
	
	// Apakah Jumlah SKS Tahun ini mencukupi?
	if ($oke) {
	  if ($w['GunakanGradeIPK'] == 'Y') {
		if($khs['SKS'] < GetaField('gradeipk', "IPKMin <= $mhsw[IPK] and $mhsw[IPK] <= IPKMax and KodeID", KodeID, 'SKSMin')) $oke = false;
		else $oke = true;
	  }
	}
	
	// Apakah Grade IPK-nya OK?
	if ($oke) {
      if ($w['GunakanGradeIPK'] == 'Y') {
        $pos = strpos($w['GradeIPK'], ".".GetaField('gradeipk', "IPKMin <= $mhsw[IPK] and $mhsw[IPK] <= IPKMax and KodeID", KodeID, 'GradeIPK').".");
        $oke = $oke && !($pos === false);
      }
    }
    
	// Apakah dimulai pada sesi ini?
    if ($oke) {
      if ($w['MulaiSesi'] <= $khs['Sesi'] or $w['MulaiSesi'] == 0) $oke = true;
	  else $oke = false;
    }
	
    // Simpan data
    if ($oke) {
      // Cek, sudah ada atau belum? Kalau sudah, ambil ID-nya
      $ada = GetaField('bipotmhsw',
        "KodeID='".KodeID."' and MhswID = '$mhsw[MhswID]'
        and PMBMhswID = 1 and NA = 'N'
        and TahunID='$khs[TahunID]' and BIPOT2ID",
        $w['BIPOT2ID'], "BIPOTMhswID") +0;
      // Cek apakah memakai script atau tidak?
      if ($w['GunakanScript'] == 'Y') BipotGunakanScript($mhsw, $khs, $w, $ada, 1);
      // Jika tidak perlu pakai script
      else {          
        // Jika tidak ada duplikasi, maka akan di-insert. Tapi jika sudah ada, maka dicuekin aja.
        if ($ada == 0) {            
          // Simpan
          $Nama = GetaField('bipotnama', 'BIPOTNamaID', $w['BIPOTNamaID'], 'Nama');
          if ($w['PerMatakuliah'] == 'N') {
            $s1 = "insert into bipotmhsw
              (KodeID, COAID, PMBMhswID, MhswID, TahunID,
              BIPOT2ID, BIPOTNamaID, Nama, TrxID,
              Jumlah, Besar, Dibayar,
              Catatan, NA,
              LoginBuat, TanggalBuat)
              values
              ('".KodeID."', '$w[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]',
              '$w[BIPOT2ID]', '$w[BIPOTNamaID]', '$Nama', '$w[TrxID]',
              1, '$w[Jumlah]', 0,
              'Auto', 'N',
              '$_SESSION[_Login]', now())";
            $r1 = _query($s1);
            //22 juli 2013   
            //jika insert tagihan
            $idt = mysql_insert_id();
              $bn = GetFields('bipotnama', 'BIPOTNamaID', $w[BIPOTNamaID], '*');
              if ($bn[TrxID] == 1) {
                  $sx = "update bipotmhsw set TagihanID = '" . $idt . "' where BIPOTMhswID='" . $idt . "'";
                  $rx = _query($sx);
              } else {
                  //jika insert potongan
                  $cek_tagihan = GetaField("bipotmhsw", "MhswID='$mhsw[MhswID]' and TahunID='$khs[TahunID]' and NA='N' and TrxID", 1, "TagihanID", "order by BIPOTMhswID desc");
                  $sx = "update bipotmhsw set TagihanID = '" . $cek_tagihan . "' where BIPOTMhswID='" . $idt . "'";
                  $rx = _query($sx);
              }
          } else {           
              // Ambil BIPOT Biaya Per Mata Kuliah
              // mario
              // 200813
              $Jumlah2 = 0;
              $COAID = '';
              $sxx = "select k.MKKode, k.Nama, k.SKS, j.BiayaKhusus, j.Biaya, j.NamaBiaya, j.AdaResponsi
                                  from krs k 
                                          left outer join jadwal j on k.JadwalID=j.JadwalID and j.KodeID='" . KodeID . "'
                                          left outer join mk mk on mk.MKID=k.MKID and mk.KodeID='" . KodeID . "'
                                  where k.MhswID='$mhsw[MhswID]' and k.TahunID='$khs[TahunID]' and mk.PraktekKerja='N' and k.KodeID='" . KodeID . "'";
              $rxx = _query($sxx);
              while ($wxx = _fetch_array($rxx)) {
                  $s1 = "select * from bipot2 where BIPOTID = '$mhsw[BIPOTID]' and Otomatis = 'Y' and (PerMataKuliah = 'Y' or PerLab = 'Y') and NA = 'N' order by TrxID, Prioritas";
                  $r1 = _query($s1);
                  while ($w1 = _fetch_array($r1)) {
                      $MsgList[] = '-----------------------------------------------------------------';
                      $MsgList[] = "Memproses $w1[BIPOT2ID], Rp. $w1[Jumlah]";
                      $oke = true;
                      // Cek apakah mata kuliah ini dapat dikenakan biaya Lab
                      if ($w1['PerLab'] == 'Y') {
                          if ($wxx['AdaResponsi'] == 'Y')
                              $oke = true;
                          else
                              $oke = false;
                      }
                      else
                          $oke = true;
                      // Apakah sesuai dengan status awalnya?
                      $pos = strpos($w1['StatusAwalID'], "." . $mhsw['StatusAwalID'] . ".");
                      $oke = $oke && !($pos === false);
                      $MsgList[] = "Sesuai dengan status awalnya ($w1[StatusAwalID] ~ $mhsw[StatusAwalID])? $oke";
                      // Apakah sesuai dengan status mahasiswanya?
                      $pos = strpos($w1['StatusMhswID'], "." . $khs['StatusMhswID'] . ".");
                      $oke = $oke && !($pos === false);
                      $MsgList[] = "Sesuai dengan status mahasiswanya ($w1[StatusMhswID] ~ $khs[StatusMhswID])? $oke";
                      // Apakah grade-nya?
                      if ($oke) {
                          if ($w1['GunakanGradeNilai'] == 'Y') {
                              $pos = strpos($w1['GradeNilai'], "." . $mhsw['GradeNilai'] . ".");
                              $oke = $oke && !($pos === false);
                              $MsgList[] = "Gunakan Grade Nilai? $oke";
                          }
                      }
                      // Apakah Jumlah SKS Tahun lalu mencukupi?
                      if ($oke) {
                          if ($w1['GunakanGradeIPK'] == 'Y') {
                              $_SKS = GetaField('gradeipk', "IPKMin <= '$khslalu[IPS]' and '$khslalu[IPS]' <= IPKMax and KodeID", KodeID, 'SKSMin');
                              if ($_SKS > $khslalu[SKS])
                                  $oke = false;
                              else
                                  $oke = true;
                              $MsgList[] = "Jumlah SKS Tahun Mencukupi($_SKS ~ $khslalu[SKS])? $oke";
                          }
                      }
                      // Apakah Grade IPK-nya OK?
                      if ($oke) {
                          if ($w1['GunakanGradeIPK'] == 'Y') {
                              if (!empty($khslalu)) {
                                  $_GradeIPK = GetaField('gradeipk', "IPKMin <= $khslalu[IPS] and $khslalu[IPS] <= IPKMax and KodeID", KodeID, 'GradeIPK');
                                  $pos = strpos($w1['GradeIPK'], "." . $_GradeIPK . ".");
                                  $oke = $oke && !($pos === false);
                                  $MsgList[] = "Grade IPK OK ($_GradeIPK ~ $w1[GradeIPK])? $oke";
                              } else {
                                  $oke = false;
                              }
                          }
                      }
                      // Apakah dimulai pada sesi ini?
                      if ($oke) {
                          if ($w1['MulaiSesi'] <= $khs['Sesi'] or $w1['MulaiSesi'] == 0)
                              $oke = true;
                          else
                              $oke = false;
                          $MsgList[] = "Mulai pada sesi ini ($khs[Sesi] ~ $w1[MulaiSesi])? $oke";
                      }
                      // Apakah ada setup berapa kali ambil?
                      if ($oke && $w1['KaliSesi'] > 0) {
                          $_kali = GetaField('bipotmhsw', "MhswID='$MhswID' and NA='N' and PMBMhswID=1 and BIPOTNamaID='$w1[BIPOTNamaID]' and TambahanNama='$wxx[MKKode] - $wxx[Nama] - $wxx[SKS] SKS' and KodeID", KodeID, "count(BIPOTMhswID)") + 0;
                          $oke = $_kali < $w1['KaliSesi'];
                          $MsgList[] = "Berapa Kali Ambil - ($_kali ~ $w1[KaliSesi])? $oke";
                      }
                      if ($oke)
                          $MsgList[] = "ALL OK! GO FOR IT!";
                      // Simpan data
                      if ($oke) {
                          $ada = GetaField('bipotmhsw', "KodeID='" . KodeID . "' and MhswID = '$mhsw[MhswID]' and NA = 'N' and PMBMhswID = 1 and TahunID='$khs[TahunID]' and BIPOTNamaID = '$w1[BIPOTNamaID]'	
                    and TambahanNama='$wxx[MKKode] - $wxx[Nama] - $wxx[SKS] SKS'
                    and BIPOT2ID", $w1['BIPOT2ID'], "BIPOTMhswID") + 0;
                          if ($ada == 0) {
                              // Simpan
                              //$COAID = $w1['COAID'];
                              $Nama = GetaField('bipotnama', 'BIPOTNamaID', $w1['BIPOTNamaID'], 'Nama');
                              if ($w1['PerSKS'] == 'Y')
                                  $Jumlah = $wxx['SKS'];
                              else
                                  $Jumlah = 1;     
                              // mario
                              // 200813
                              $Besar = $w1['Jumlah'];
                              $Jumlah2 += $Jumlah;
                              $COAID = $w1[COAID];
                              $BIPOT2ID = $w1[BIPOT2ID];
                              $BIPOTNamaID = $w1[BIPOTNamaID];
                              $TrxID = $w1[TrxID];
                              /* $s2 = "insert into bipotmhsw (KodeID, COAID, PMBMhswID, MhswID, TahunID, BIPOT2ID, BIPOTNamaID, TambahanNama, Nama, TrxID, 
                                Jumlah, Besar, Dibayar,Catatan, NA, LoginBuat, TanggalBuat)
                                values ('" . KodeID . "', '$w1[COAID]', 1, '$mhsw[MhswID]', '$khs[TahunID]', '$w1[BIPOT2ID]', '$w1[BIPOTNamaID]', '" . $w['MKKode'] . " - " . $w['Nama'] . " - " . $w['SKS'] . " SKS', '$Nama', '$w1[TrxID]',
                                '$Jumlah', '$Besar', 0, 'Auto', 'N', '$_SESSION[_Login]', now())";
                                $r2 = _query($s2);

                               */
                          }
                      } // if
                  } // while
              } // while 
              // mario
              // 200813
              if ($Nama <> '' && $Besar > 0) {
                  $s2 = "insert into bipotmhsw (KodeID, COAID, PMBMhswID, MhswID, TahunID, BIPOT2ID, BIPOTNamaID, TambahanNama, Nama, TrxID, 
                    Jumlah, Besar, Dibayar,Catatan, NA, LoginBuat, TanggalBuat)
                  values ('" . KodeID . "', '$COAID', 1, '$mhsw[MhswID]', '$khs[TahunID]', '$BIPOT2ID', '$BIPOTNamaID', '', '$Nama', '$TrxID', 
                    '$Jumlah2', '$Besar', 0, 'Auto', 'N', '$_SESSION[_Login]', now())";
                  $r2 = _query($s2);
                  //22 juli 2013   
                  //jika insert tagihan
                  $idt = mysql_insert_id();

                  $bn = GetFields('bipotnama', 'BIPOTNamaID', $BIPOTNamaID, '*');
                  if ($bn[TrxID] == 1) {

                      $sx = "update bipotmhsw set TagihanID = '" . $idt . "' where BIPOTMhswID='" . $idt . "'";
                      $rx = _query($sx);
                  } else {
                      //jika insert potongan
                      $cek_tagihan = GetaField("bipotmhsw", "MhswID='$mhsw[MhswID]' and TahunID='$khs[TahunID]' and NA='N' and TrxID", 1, "TagihanID", "order by BIPOTMhswID desc");
                      $sx = "update bipotmhsw set TagihanID = '" . $cek_tagihan . "' where BIPOTMhswID='" . $idt . "'";
                      $rx = _query($sx);
                  }
              }
          } // if PerMatakuliah
            
        }// end $ada=0
      } // end if $ada
    }   // end if $oke
  }     // end while
  $jml = HitungUlangBIPOTMhsw($MhswID, $TahunID);
  return $jml;
}
?>
