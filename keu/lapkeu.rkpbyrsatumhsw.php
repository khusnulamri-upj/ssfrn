<?php
session_start();
include_once "../dwo.lib.php";
include_once "../db.mysql.php";
include_once "../connectdb.php";
include_once "../parameter.php";
include_once "../cekparam.php";
include_once "../fpdf.php";

echo <<<SCR
  <script src="lapkeu.script.js"></script>
SCR;

// *** Parameters ***
$Tahun = GetSetVar('Tahun');
$NPM = GetSetVar('NPM');
$NamaMhsw = GetSetVar('NamaMhsw');

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'PilihTahun' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function PilihTahun() {
	$optTahun = "";
	$s = "SELECT MID(TahunID,1,4) AS Tahun FROM tahun GROUP BY MID(TahunID,1,4) ORDER BY Tahun";
	$q = _query($s);
        while ($w = _fetch_array($q)){
		$optTahun .= "<option value=$w[Tahun]>$w[Tahun]</option>";
	}
        CheckFormScript("NPM");
        echo "
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../themes/default/index.css\" />
		<form name=\"frmFilter\" action=\"\" method=POST onSubmit=\"return CheckForm(this)\">
		<input type=hidden name=gos value=Cetak />
		<table class=box width=600 cellpadding=1 align=center>
		<tr>
			<th class=ttl colspan=3 align=center>Rekapitulasi Pembayaran Keuangan Per Mahasiswa</th>
		</tr>
		<tr>
			<td class=wrn>&nbsp;</td>
			<td class=inp>Tahun Akademik : </td>
			<td class=ul>
				<select name=Tahun>
						".$optTahun."
				</select>
			</td>
		</tr>
                		<tr>
			<td class=wrn>&nbsp;</td>
			<td class=inp>N.P.M : </td>
			<td class=ul>
                            <input type=text name='NPM' value='' size=10 maxlength=30 />
                            <input type=text name='NamaMhsw' value='' size=30 maxlength=50/>
                            &raquo;
                            <a href='#'
                            onClick=\"javascript:CariMhsw('frmFilter')\" />Cari...</a> |
                            <a href='#' onClick=\"javascript:frmFilter.NPM.value='';frmFilter.NamaMhsw.value=''\">Reset</a>
                        </td>
		</tr>
		<tr>
			<td colspan=3 align=center><input class=buttons type=submit value=Cetak /></td>
		</tr>
		</table>
                </form>
	";
echo <<<ESD
  <div class='box0' id='carimhsw'></div>
  
  <script>
  <!--
  function toggleBox(szDivID, iState) // 1 visible, 0 hidden
  {
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].visibility = iState ? "show" : "hide";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.visibility = iState ? "visible" : "hidden";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.visibility = iState ? "visible" : "hidden";
    }
  }
      
  function CariMhsw(frm) {
      eval(frm + ".NPM.focus()");
      showMhsw(frm, eval(frm +".NPM.value"), eval(frm +".NamaMhsw.value"), 'carimhsw');
      toggleBox('carimhsw', 1);
  }
  -->
  </script>
  </BODY>
ESD;
}

class ReportWidth
{
  const wThn = 8;
  const wSesi = 10;
  const wStsMhsw = 11;
  const wNamaBipot = 40;
  const wTglByr = 16;
  const wJmlByr = 18;
  const wTotalJmlByr = 18;
  const wHutang = 18;
  const wTrn = 26;
  const wNoTrn = 18;
  const wKet = 12;
}  
  

function Cetak() {
  // *** Init PDF
  $pdf = new FPDF('P','mm','A4');
  $pdf->SetTitle("Rekap Pembayaran Keuangan per Mahasiswa");
  $pdf->AddPage();
  $lbr = 290;
  
  BuatIsinya($Tahun, $NPM, $NamaMhsw, $pdf);
  
  ob_clean();
  $pdf->Output();
}

function BuatIsinya($Thn, $NPM, $NamaMhsw, $p) {
  global $lbr;
  //$n = 0;
  $t = 4;
  
  BuatHeadernya($NPM, $NamaMhsw, $p);
  
  /* $p->SetFont('Helvetica', 'B', 8);
  $p->Cell($width::wThn, $t, '2013', 1, 0, 'L', true);
  $p->Cell($width::wSesi, $t, 'Ganjil', 1, 0, 'L', true);
  $p->Cell($width::wStsMhsw, $t, 'Active', 1, 0, 'L', true);
  $p->Cell($width::wNamaBipot, $t, 'Sumb. Peny. Pend. SPP', 1, 0, 'L', true);
  $p->Cell($width::wTglByr, $t, '00-00-0000', 1, 0, 'C', true);
  $p->Cell($width::wJmlByr, $t, '000,000,000', 1, 0, 'R', true);
  $p->Cell($width::wTotalJmlByr, $t, '000,000,000', 1, 0, 'R', true);
  $p->Cell($width::wHutang, $t, '000,000,000', 1, 0, 'R', true);
  $p->Cell($width::wTrn, $t, 'SETOR PERMATA', 1, 0, 'L', true);
  $p->Cell($width::wNoTrn, $t, '2013-000001', 1, 0, 'L', true);
  $p->Cell($width::wKet, $t, 'Kurang', 1, 1, 'L', true); */
  
  $width = new ReportWidth();
  
  $s = 
 "SELECT bi.TagihanID, bi.TahunID, bi.Nama,
  IFNULL(byr.Jumlah, CASE WHEN bi.TrxID < 0 THEN (bi.TrxID*bi.Jumlah*bi.Besar) ELSE 0 END) AS jml_byr,
  DATE_FORMAT(hbyr.Tanggal,'%d-%m-%Y') AS tgl_byr, hbyr.Bank, byr.BayarMhswID
  FROM bipotmhsw bi
  LEFT OUTER JOIN bayarmhsw2 byr ON bi.BIPOTMhswID = byr.BIPOTMhswID
  LEFT OUTER JOIN bayarmhsw hbyr ON byr.BayarMhswID = hbyr.BayarMhswID
  WHERE bi.MhswID = '$_SESSION[NPM]'
  AND bi.TahunID LIKE '$_SESSION[Tahun]%'
  AND bi.NA = 'N'
  AND bi.PMBMhswID = 1
  ORDER BY bi.TahunID, bi.TagihanID, bi.BIPOTMhswID, byr.BayarMhswID";
 
  $q = _query($s);
  
  $totalJumlah = 0;
  $totalSisa = 0;
  
  $p->SetFont('Helvetica', 'B', 8);
  
  $before = null;
  
  while ($w = _fetch_array($q)){
  
  if (empty($before)) {
      $before = $w;
  } else {
      $before = $after;
  }    
  $after = $w;
  
  $ThnAjar = substr($before[TahunID],0,4);
  if ((substr($before[TahunID],-1) % 2) == 0) {
      $Smt = "Genap";
  } else {
      $Smt = "Ganjil";
  }
  
  $isFirstRow = true;
  
  if (substr($after[TahunID],0,4) == substr($before[TahunID],0,4)) {
      if (!$isFirstRow) {
          //$TotalJmlByr = $TotalJmlByr+$jml_byr;
          $countRow = $countRow + 1;
      } else {
          $isFirstRow = false;
      }
      $printTotalJmlByr = '';
      $printHutang = '';
      $printKet = '';
  } else {
     if ($countRow > 0) {
          $TotalJmlByr = $TotalJmlByr+$jml_byr;
      } else {
          $TotalJmlByr = $before[jml_byr];
      }
      $countRow = 0;
      $printTotalJmlByr = $TotalJmlByr;
  
  $p->Cell($width::wThn, $t, $ThnAjar, 1, 0, 'L', true);
  $p->Cell($width::wSesi, $t, $Smt, 1, 0, 'L', true);
  $p->Cell($width::wStsMhsw, $t, 'Active', 1, 0, 'L', true);
  $p->Cell($width::wNamaBipot, $t, $before[Nama], 1, 0, 'L', true);
  $p->Cell($width::wTglByr, $t, $before[tgl_byr], 1, 0, 'C', true);
  
  if ($before[jml_byr] < 0) {
      $jml_byr = -1*$before[jml_byr];
      $print_jml_byr = number_format($jml_byr,0,'.',',').'*';
  } else {
      $jml_byr = 1*$before[jml_byr];
      $print_jml_byr = number_format($jml_byr,0,'.',',');
  }
  
  $p->Cell($width::wJmlByr, $t, $print_jml_byr, 1, 0, 'R', true);
  
  $isFirstRow = true;
  
  if ($after[TagihanID] == $before[TagihanID]) {
      if (!$isFirstRow) {
          $TotalJmlByr = $TotalJmlByr+$jml_byr;
          $countRow = $countRow + 1;
      } else {
          $isFirstRow = false;
      }
      $printTotalJmlByr = '';
      $printHutang = '';
      $printKet = '';
  } else {
     if ($countRow > 0) {
          $TotalJmlByr = $TotalJmlByr+$jml_byr;
      } else {
          $TotalJmlByr = $before[jml_byr];
      }
      $countRow = 0;
      $printTotalJmlByr = $TotalJmlByr;
  
  $ss = 
  "SELECT SUM((CASE WHEN TrxID > 0 THEN TrxID ELSE 0 END)*Jumlah*Besar) AS hrs_byr
   FROM bipotmhsw
   WHERE MhswID = '$_SESSION[NPM]'
   AND TahunID LIKE '$_SESSION[Tahun]%'
   AND NA = 'N'
   AND PMBMhswID = 1
   AND TagihanID = '$before[TagihanID]'
   GROUP BY TagihanID";
  
  $qq = _query($ss);
  
  $ww = _fetch_array($qq);    
      
  $printHutang = $ww[hrs_byr]-$printTotalJmlByr;
  if ($printHutang > 0) {
      $printKet = 'Kurang';
  } elseif ($printHutang == 0) {
      $printKet = 'Lunas';
  } else {
      $printKet = '';
  }
      
}
  
  /*if ($after[TagihanID] == $before[TagihanID]) {
      if (!$isFirstRow) {
          $TotalJmlByr = $TotalJmlByr+$before[jml_byr];
      }       
  } else {
      $TotalJmlByr = $before[jml_byr];
  }*/
  
  $p->Cell($width::wTotalJmlByr, $t, number_format($printTotalJmlByr,0,'.',','), 1, 0, 'R', true);
  $p->Cell($width::wHutang, $t, number_format($printHutang,0,'.',','), 1, 0, 'R', true);
  $p->Cell($width::wTrn, $t, $before[Bank], 1, 0, 'L', true);
  $p->Cell($width::wNoTrn, $t, $before[BayarMhswID], 1, 0, 'L', true);
  $p->Cell($width::wKet, $t, $printKet, 1, 1, 'L', true);
  }
  
  /*$JumSesi = GetaField('prodi', "ProdiID", $ProdiID, 'JumlahSesi');
  $MaxSesi = GetaField('mk', "ProdiID", $ProdiID, 'MAX(Sesi)');
  $s = "select k.MhswID as _MhswID, k.Sesi, m.Nama as _NamaMhsw from khs k left outer join mhsw m on m.MhswID = k.MhswID
  		where k.ProdiID = '$ProdiID' and m.TahunID like '$Angkatan%' and k.TahunID like '$TahunID%' group by k.MhswID";
		
  $q = _query($s);
  $totalJumlah = 0;
  $totalSisa = 0;
  while ($w = _fetch_array($q)){
  		$n++;
		$p->SetFont('Helvetica', '', 8);
		$p->Cell(10, $t, $n, 1, 0, 'R');
		$p->Cell(60, $t, $w[_NamaMhsw], 1, 0, 'L');
		$p->Cell(30, $t, $w[_MhswID], 1, 0, 'C');
		
		$js = $JumSesi;
		
		$CekSesi = (($TahunID - $Angkatan)*$js)+$JumSesi;
		if ($CekSesi > $MaxSesi){
			$JumSesi -= ($CekSesi - $MaxSesi);
			if ($JumSesi <= 0){
				$JumSesi = 1;
			}
		}
		$jumlahBiaya = 0;
		$jumlahBayar = 0;
		for ($i=1;$i<=$JumSesi;$i++){
			$sesi = (($TahunID - $Angkatan)*$js)+$i;
			if ($sesi > $MaxSesi){
				$sesi = $MaxSesi;
			}
			$s2 = "select * from khs where MhswID = '$w[_MhswID]' and Sesi = '$i' and TahunID like '$TahunID%' and ProdiID = '$ProdiID'";
			$q2 = _query($s2);
			$w2 = _fetch_array($q2);
			
			$jumlahBayar += $w2[Bayar];
			$bayar = ($w2[Bayar] == 0)? '-' : number_format($w2[Bayar],0,'.',',');
			$p->Cell(90/$JumSesi, $t, $bayar, 1, 0, 'R');
		}
		$jumlahBiaya = GetFields('bipotmhsw', "KodeID='".KodeID."' and TahunID like '$TahunID%' and TrxID = '1' and MhswID", $w[_MhswID], 'SUM(Besar) as _jumlah');
		$jumlahPotongan = GetFields('bipotmhsw', "KodeID='".KodeID."' and TahunID like '$TahunID%' and TrxID = '-1' and MhswID", $w[_MhswID], 'SUM(Besar) as _jumlah');
		$jumlah = $jumlahBiaya[_jumlah] - $jumlahPotongan[_jumlah];
		$sisa = (($jumlah - $jumlahBayar) > 0)? number_format($jumlah - $jumlahBayar,0,'.',',') : 'Lunas';
		
		//$p->Cell(30, $t, '', 1, 0, 'R');
		$p->Cell(30, $t, number_format($jumlahBayar,0,'.',','), 1, 0, 'R');
		$p->Cell(30, $t, $sisa, 1, 1, 'R');	
		
		$totalJumlah += $jumlahBayar;	
		$totalSisa += ($jumlah - $jumlahBayar);	
  }
  	
	// buat total
	$lb = 190;
	$p->SetFont('Helvetica', 'B', 8);
	$p->Cell($lb, $t, '', 0, 0);
	$p->Cell(30, $t, number_format($totalJumlah,0,'.',','), 1, 0, 'R');
	$p->Cell(30, $t, number_format($totalSisa,0,'.',','), 1, 1, 'R');*/
}
function BuatHeadernya($NPM, $NamaMhsw, $p) {
  global $lbr;
  
  $t = 4;
  //$NamaProdi = GetaField('prodi', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'Nama');
  $p->SetFont('Helvetica', 'B', 8);
  $p->SetFillColor(255, 255, 255);
  $p->Cell($lbr, $t, "REKAP PEMBAYARAN KEUANGAN MAHASISWA", 0, 1, 'L', true);
  $p->Cell(30, $t, "Nama Mahasiswa", 0);
  $p->Cell(2, $t, ":", 0);
  $p->Cell(120, $t, $_SESSION[NamaMhsw], 0);
  $p->Ln();
  $p->Cell(30, $t, "N P M", 0);
  $p->Cell(2, $t, ":", 0);
  $p->Cell(120, $t, $_SESSION[NPM], 0);
  $p->Ln(10);

  /*$NamaTagihan = GetaField('bipotnama', "Urutan", 1, 'Nama');  
  $NamaSesi = GetaField('prodi', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'NamaSesi');
  $JumSesi = GetaField('prodi', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'JumlahSesi');
  $MaxSesi = GetaField('mk', "KodeID='".KodeID."' and ProdiID", $ProdiID, 'MAX(Sesi)');
  
  */ 
  $t = 6;
  
  $width = new ReportWidth();
  
  $p->SetFont('Helvetica', 'B', 8);
  $p->Cell($width::wThn, $t, 'T.A.', 1, 0, 'L', true);
  $p->Cell($width::wSesi, $t, 'Smt.', 1, 0, 'L', true);
  $p->Cell($width::wStsMhsw, $t, 'Status', 1, 0, 'L', true);
  $p->Cell($width::wNamaBipot, $t, 'Uraian Pembayaran', 1, 0, 'L', true);
  $p->Cell($width::wTglByr, $t, 'Tgl. Bayar', 1, 0, 'C', true);
  $p->Cell($width::wJmlByr, $t, 'Bayar', 1, 0, 'C', true);
  $p->Cell($width::wTotalJmlByr, $t, 'Total', 1, 0, 'C', true);
  $p->Cell($width::wHutang, $t, 'Tunggakan', 1, 0, 'C', true);
  $p->Cell($width::wTrn, $t, 'Bukti Bayar', 1, 0, 'L', true);
  $p->Cell($width::wNoTrn, $t, 'Kwitansi', 1, 0, 'L', true);
  $p->Cell($width::wKet, $t, 'Ket.', 1, 1, 'L', true);
  
  /*$js = $JumSesi;
  $CekSesi = (($TahunID - $Angkatan)*$js)+$JumSesi;
  if ($CekSesi > $MaxSesi){
  	$JumSesi -= ($CekSesi - $MaxSesi);
	if ($JumSesi <= 0){
		$JumSesi = 1;
	}
  }
  for ($i=1;$i<=$JumSesi;$i++){
  	$sesi = (($TahunID - $Angkatan)*$js)+$i;
	if ($sesi > $MaxSesi){
		$sesi = $MaxSesi;
	}
  	$p->Cell(90/$JumSesi, $t, $NamaSesi.' '.$sesi, 1, 0, 'C', true);
  }
  $p->Ln($t);*/

}
?>
