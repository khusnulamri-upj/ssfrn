<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 04 Desember 2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Detail Honor Mingguan", 1);

// *** Parameters ***
$_detDosenID = $_REQUEST['_detDosenID'];
$_detTahun = $_REQUEST['_detTahun'];
$_detBulan = $_REQUEST['_detBulan'];

// *** Main ****
$gos = (empty($_REQUEST['gos']))? 'fnMingguan' : $_REQUEST['gos'];
$gos($_detDosenID, $_detTahun, $_detBulan);

// *** Functions ***
function fnMingguan($DosenID, $Tahun, $Bulan) {
  $arrMinggu = GetArrayMinggu();
  $s = "select hd.*
    from honordosen hd
    where hd.DosenID = '$DosenID'
      and hd.Tahun = '$Tahun'
      and hd.Bulan = '$Bulan'
	  
    order by hd.Minggu, hd.HonorDosenID";
  $r = _query($s);
  $arrhon = array();
  $arrhonid = array();
  while ($w = _fetch_array($r)) {
    $jml = ($w['TunjanganJabatan1'] +
      $w['TunjanganJabatan2'] +
      $w['TunjanganSKS'] +
      $w['TunjanganTransport'] +
      $w['TunjanganTetap'] +
      $w['Tambahan'] - $w['Potongan']);
    $pjk = $jml - ($jml * $w['Pajak']/100);
    $arrhon[$w['Minggu']] += $pjk;
    if ($arrhonid[$w['Minggu']] == '') $arrhonid[$w['Minggu']] = $w['HonorDosenID'];
    else $arrhonid[$w['Minggu']] = $arrhonid[$w['Minggu']] . ';'. $w['HonorDosenID'];
  }
  // Tampilkan
  PrintScript();
  echo "<table class=bsc cellspacing=0 width=100%>";
  echo "<tr>";
  foreach ($arrMinggu as $m) {
    $jml = $arrhon[$m];
    $id = $arrhonid[$m];
    if (!empty($id)) {
      $_jml = number_format($jml);
	  $_cetak = "<a href='#' onClick=\"CetakHondok('$DosenID', '$Bulan', '$Tahun', '$id')\"><img src='../img/printer2.gif' width=13/></a>";
      $_edt = "<a href='../$_SESSION[mnux].minggu.php?gos=fnEditHondok&_detDosenID=$DosenID&_detBulan=$Bulan&_detTahun=$Tahun&_detid=$id'><img src='../img/edit.png' /></a>";
    } 
    else {
      $_jml = '&nbsp;';
	  $_cetak = '';
      $_edt = '';
    }
    echo "<td class=ul align=right width=98 valign=top>
	  $_jml
      $_edt
	  $_cetak
      </td>";
  }
  $_cetaksemua = "<a href='#' onClick=\"CetakHondok1('$DosenID', '$Bulan', '$Tahun', '')\"><img src='../img/printer2.gif' /></a>";
  echo "<td>$_cetaksemua</td></tr>";
}
function fnEditHondok($DosenID, $Tahun, $Bulan) {
  $_detid = $_REQUEST['_detid'];
  $id = explode(';', $_detid);
  $jml = sizeof($id);
  if ($jml > 1) {
    $pil = '';
    foreach ($id as $i) {
      $pil .= "<a href='../$_SESSION[mnux].minggu.php?gos=fnEditHondok&_detDosenID=$DosenID&_detBulan=$Bulan&_detTahun=$Tahun&_detid=$i'>[#$i]</a>. ";
    }
    echo "<p align=center>&raquo; Ada $jml&times; honor minggu ini. Pilih salah satu: $pil <a href='../$_SESSION[mnux].minggu.php?gos=&_detDosenID=$DosenID&_detBulan=$Bulan&_detTahun=$Tahun'>[Batal]</a>";
  }
  else fnEditHondokid($DosenID, $Tahun, $Bulan, $_detid);
}
function fnEditHondokid($DosenID, $Tahun, $Bulan, $id) {
  $TahunID = GetaField('honordosen', 'HonorDosenID', $id, 'TahunID');
  echo <<<ESD
  <p align=center>Mengedit #$id.</p>
  <script>
  parent.EditHondok('$DosenID', $id, '$TahunID');
  </script>
ESD;
}
function GetArrayMinggu() {
  $s = "select MingguID, Nama
    from minggu
		where NA = 'N'
    order by MingguID";
  $r = _query($s);
  $arr = array();
  while ($w = _fetch_array($r)) {
    $arr[] = $w['MingguID'];
  }
  return $arr;
}
function PrintScript()
{	echo "<script>
				function CetakHondok(DosenID, Bulan, Tahun, id)
				{	
					  lnk = '../$_SESSION[mnux].cetak.php?_detDosenID='+DosenID+'&_detBulan='+Bulan+'&_detTahun='+Tahun+'&_detid='+id;
					  win2 = window.open(lnk, \"\", \"width=600, height=400, scrollbars, status\");
					  if (win2.opener == null) childWindow.opener = self;
				}
				function CetakHondok1(DosenID, Bulan, Tahun, id)
				{	
					  lnk = '../$_SESSION[mnux].cetak1.php?_detDosenID='+DosenID+'&_detBulan='+Bulan+'&_detTahun='+Tahun+'&_detid='+id;
					  win2 = window.open(lnk, \"\", \"width=600, height=400, scrollbars, status\");
					  if (win2.opener == null) childWindow.opener = self;
				}
			</script>
		";
}
?>
