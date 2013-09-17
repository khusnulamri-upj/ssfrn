<?php

// Hanya akan memproses NIM Sementara. 

session_start();
include_once "../sisfokampus1.php";
HeaderSisfoKampus("Proses NIM Sementara");

// *** Main ***
$TahunID = GetSetVar('TahunID');
$ProdiID = GetSetVar('ProdiID');
$ProgramID = GetSetVar('ProgramID');
$StatusAwalID = GetSetVar('StatusAwalID');
$StatusAwalID2 = GetSetVar('StatusAwalID2');

// *** Main ***
TampilkanJudul("Proses NIM Sementara");
$gos = (empty($_REQUEST['gos']))? 'AmbilData' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function AmbilData() {
  $tmp = GetaField('prodi', 'ProdiID', $_SESSION['ProdiID'], 'FormatNIMSementara'); 
  $tmpxx = $tmp;
  $posT = strpos($tmp, '~T~');
  $tmp = str_replace('~YY~', substr($_SESSION['TahunID'], 2, 2), $tmp);
  $tmp = str_replace('~YYYY~', substr($_SESSION['TahunID'], 0, 4), $tmp);
  $tmp = str_replace('~PRG~', $_SESSION['ProgramID'], $tmp);
  $tmp = str_replace('~STAWAL~', $_SESSION['StatusAwalID'], $tmp);    
  $digit = substr($tmp, strpos($tmp, '~NMR')+4, 1)+0;
  $tmp = str_replace('~NMR3~', '~NMR~', $tmp); 
  $tmp = str_replace('~NMR4~', '~NMR~', $tmp);
  $tmp = str_replace('~NMR5~', '~NMR~', $tmp);  
  if (!empty($posT)) { // ada format T
    //$StatusAwalID2 = ($_SESSION['StatusAwalID2'] == 'Y')? '2' : '1';
    //$tmp = str_replace('~T~', $StatusAwalID2, $tmp);  
    $pos = strpos($tmp, '~T~');
    $pattern = substr($tmp, 0, $pos);
    $rpattern = substr($tmp, $pos+8);
  } else { // tak ada format T
    $pos = strpos($tmp, '~NMR~');
    $pattern = substr($tmp, 0, $pos);
    $rpattern = substr($tmp, $pos+5);
  }    
  $whr_pattern = (!empty($pattern))? "and LEFT(m.MhswID, ".strlen($pattern).") = '$pattern'" : ""; 
  $whr_rpattern = (!empty($rpattern))? "and RIGHT(m.MhswID, ".strlen($rpattern).") = '$rpattern'" : "";  
  $s = "select m.MhswID, m.Nama
    from mhsw m left outer join statusmhsw sm on m.StatusMhswID=sm.StatusMhswID
    where m.KodeID = '".KodeID."'
      and m.TahunID = '$_SESSION[TahunID]'
      and m.ProdiID = '$_SESSION[ProdiID]'
      $whr_pattern
      $whr_rpattern
      and sm.Keluar='N'
      and m.NA = 'N'
    order by concat(left(m.MhswID,length(m.MhswID)-4),right(m.MhswID,3))"; //m.MhswID = '12TEMPTGF1002' or m.MhswID='12TEMPTGF2003' or m.MhswID='12TEMPTGF1004'
  $r = _query($s);
  $n = 0;
  while ($w = _fetch_array($r)) {
  	$_SESSION['PRC_URUTNIMS_MhswID_'.$n] = $w['MhswID'];
    $n++;
  }
  $_SESSION['PRC_URUTNIMS_TahunID'] = $_SESSION['TahunID'];
  $_SESSION['PRC_URUTNIMS_ProdiID'] = $_SESSION['ProdiID'];
  $_SESSION['PRC_URUTNIMS_ProgramID'] = $_SESSION['ProgramID'];
  $_SESSION['PRC_URUTNIMS_StatusAwalID'] = $_SESSION['StatusAwalID'];
  $_SESSION['PRC_URUTNIMS_StatusAwalID2'] = $_SESSION['StatusAwalID2'];
  $_SESSION['PRC_URUTNIMS_JML'] = $n;
  $_SESSION['PRC_URUTNIMS_PRC'] = 0;
  // Tampilkan konfirmasi
  echo Konfirmasi("Konfirmasi Proses",
    "Anda akan memproses NIM tetap dari mahasiswa yang aktif pada: </br>
    Prodi: <b>$_SESSION[ProdiID]</b></br>
    Tahun Akd: <b>$_SESSION[TahunID]</b></br>
    Jumlah yg akan diproses: <b>$_SESSION[PRC_URUTNIMS_JML]</b>.<br />
    Anda yakin akan memprosesnya?
    <hr size=1 color=silver />
    Opsi: <input type=button name='Proses' value='Proses Sekarang' onClick=\"window.location='../$_SESSION[mnux].prc.php?gos=Proses'\" />
      <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />");
}

function Proses() {
  $jml = $_SESSION['PRC_URUTNIMS_JML']+0;
  $prc = $_SESSION['PRC_URUTNIMS_PRC']+0;  
  if ($prc < $jml) {
  	// Parameter
  	$MhswIDLama = $_SESSION['PRC_URUTNIMS_MhswID_'.$prc];
    $NIM = GetNextNIM($_SESSION['TahunID'], GetFields('mhsw', "MhswID='$MhswIDLama' and KodeID", KodeID, '*'));
    $NIM2 = explode('.', $NIM);
    $NIM = $NIM2[0];
    $NIMx = $NIM2[1];
    // Proses
    // PMB
    $arrTablesToUpdate = array('bayarmhsw', 'bipotmhsw', 'khs', 'krs', 'pmb', 'presensimhsw', 'prosesstatusmhsw');
    foreach($arrTablesToUpdate as $table) {	
      $s1 = "update $table set MhswID = '$NIM' where MhswID='$MhswIDLama'";
      $r1 = _query($s1);
    }
    $s1 = "update mhsw set MhswID = '$NIM', NIMSementara = 'N', Login = '$NIM', MhswIDLama='$MhswIDLama' where MhswID='$MhswIDLama'"; //, KDPIN='$NIMx'
    $r1 = _query($s1);
    // Tampilkan
    $persen = ($jml > 0)? $prc/$jml*100 : 0;
    $sisa = ($jml > 0)? 100-$persen : 0;
    $persen = number_format($persen);
    echo "<p align=center>
    <font size=+1>$persen %</font><br />
    <img src='../img/B1.jpg' width=1 height=20 /><img src='../img/B2.jpg' width=$persen height=20 /><img src='../img/B3.jpg' width=$sisa height=20 /><img src='../img/B1.jpg' width=1 height=20 /><br />
    Memproses: #$prc<br />
    <sup>$MhswIDLama</sup><br />
    <h1 align=center>
      MhswID Baru : $NIM
    </h1>
    </p>
    <hr size=1 color=silver />
    <p align=center>
      <input type=button name='Batal' value='Batalkan' onClick=\"window.close()\" />
    </p>";

    // Next
    $_SESSION['PRC_URUTNIMS_PRC']++;
    // Reload
    $tmr = 10;
    echo <<<SCR
    <script>
    window.onload=setTimeout("window.location='../$_SESSION[mnux].prc.php?gos=Proses'", $tmr);
    </script>
SCR;
  }
  else echo Konfirmasi("Proses Selesai",
    "Proses telah selesai.<br />
    Data yang berhasil diproses: <b>$_SESSION[PRC_URUTNIMS_PRC]</b>.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />");
}
?>
