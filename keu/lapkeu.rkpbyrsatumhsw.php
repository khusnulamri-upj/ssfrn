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
//$NamaMhsw = GetSetVar('NamaMhsw');

// *** Main ***
$gos = (empty($_REQUEST['gos'])) ? 'PilihTahun' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function PilihTahun() {
    $optTahun = "";
    $s = "SELECT MID(TahunID,1,4) AS Tahun FROM tahun GROUP BY MID(TahunID,1,4) ORDER BY Tahun";
    $q = _query($s);
    while ($w = _fetch_array($q)) {
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
						" . $optTahun . "
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

class ReportConfig {
    const wThn = 7;
    const wSesi = 9;
    const wStsMhsw = 9;
    const wNamaBipot = 60;
    const wTglByr = 13;
    const wJmlByr = 14;
    const wTotalJmlByr = 14;
    const wHutang = 14;
    const wTrn = 15;
    const wNoTrn = 14;
    const wKet = 10;
    //const fontHeader = "Helvetica";
    const fsHeader = 6;
    const hHeader = 3;
    //const fontIsi = "Helvetica";
    const fsIsi = 6;
    const hIsi = 3;
}

function Cetak() {
    // *** Init PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    //$pdf = new FPDF('L', 'mm', 'A5');
    $pdf->SetTitle("Rekap Pembayaran Keuangan per Mahasiswa");
    $pdf->AddPage();
    $lbr = 290;

    BuatIsinya($pdf);

    ob_clean();
    $pdf->Output();
}

function BuatIsinya($p) {
    global $lbr;

    BuatHeadernya($p);

    $rpt = new ReportConfig();

    $s =
    "SELECT bi.TagihanID, bi.TahunID, bi.Nama,
    IFNULL(byr.Jumlah, CASE WHEN bi.TrxID < 0 THEN (bi.TrxID*bi.Jumlah*bi.Besar) ELSE 0 END) AS jml_byr,
    DATE_FORMAT(hbyr.Tanggal,'%d-%m-%Y') AS tgl_byr, hbyr.Bank, byr.BayarMhswID,
    sts.Nama AS sts_mhs
    FROM bipotmhsw bi
    LEFT OUTER JOIN bayarmhsw2 byr ON bi.BIPOTMhswID = byr.BIPOTMhswID
    LEFT OUTER JOIN bayarmhsw hbyr ON byr.BayarMhswID = hbyr.BayarMhswID
    LEFT OUTER JOIN khs k ON ((k.MhswID = bi.MhswID) AND (k.TahunID = bi.TahunID))
    LEFT OUTER JOIN statusmhsw sts ON k.StatusMhswID = sts.StatusMhswID
    WHERE bi.MhswID = '$_SESSION[NPM]'
    AND bi.TahunID LIKE '$_SESSION[Tahun]%'
    AND bi.NA = 'N'
    AND bi.PMBMhswID = 1
    ORDER BY bi.TahunID, bi.TagihanID, bi.BIPOTMhswID, byr.BayarMhswID";

    $q = _query($s);

    $isFirstRow = true;
    $printTahun = true;
    $printSmt = true;
    $printNama = true;
    $before = null;

    $totalBaris = _num_rows($q);

    while (true) {

        $w = _fetch_array($q);

        if (empty($before)) {
            $before = $w;
        } else {
            $before = $after;
        }
        $after = $w;

        //untuk print tahun
        if (substr($after[TahunID], 0, 4) == substr($before[TahunID], 0, 4)) {
            if (!$isFirstRow) {
                $countRowTahun = $countRowTahun + 1;
            }
            if ($printTahun && !$isFirstRow) {
                $ThnAjar = substr($before[TahunID], 0, 4);
                $printTahun = false;
            } else {
                $ThnAjar = '';
            }
            //$isFirstRow diubah di bawah;
        } else {
            if ($countRowTahun > 0) {
                $ThnAjar = '';
            } else {
                $ThnAjar = substr($before[TahunID], 0, 4);
            }
            $countRowTahun = 0;
            $printTahun = true;
        }
        //untuk print semester
        if ((substr($before[TahunID], -1) == substr($after[TahunID], -1)) && !$printTahun) {
            if (!$isFirstRow) {
                $countRowSmt = $countRowSmt + 1;
            }
            if ($printSmt && !$isFirstRow) {
                $NumSmt = substr($before[TahunID], -1);
                $printSmt = false;
            } else {
                $NumSmt = -1;
            }
            //$isFirstRow diubah di bawah;
        } else {
            if ($countRowSmt > 0) {
                $NumSmt = -1;
            } else {
                $NumSmt = substr($before[TahunID], -1);
            }
            $countRowSmt = 0;
            $printSmt = true;
        }

        if ((($NumSmt % 2) == 0) && ($NumSmt >= 0)) {
            $Smt = 'Genap';
            $StsMhsw = $before[sts_mhs];
        } else if ((($NumSmt % 2) == 1) && ($NumSmt >= 0)) {
            $Smt = 'Ganjil';
            $StsMhsw = $before[sts_mhs];
        } else {
            $Smt = '';
            $StsMhsw = '';
        }

        //untuk print uraian pembayaran
        if (($after[TagihanID] == $before[TagihanID]) && ($after[Nama] == $before[Nama])) {
            if (!$isFirstRow) {
                $countRowNama = $countRowNama + 1;
            }
            if ($printNama && !$isFirstRow) {
                $NamaBipot = $before[Nama];
                $printNama = false;
            } else {
                $NamaBipot = '';
            }
            //$isFirstRow diubah di bawah;
        } else {
            if ($countRowNama > 0) {
                $NamaBipot = '';
            } else {
                $NamaBipot = $before[Nama];
            }
            $countRowNama = 0;
            $printNama = true;
        }

        if ($before[jml_byr] < 0) {
            $jml_byr = -1 * $before[jml_byr];
            $print_jml_byr = number_format($jml_byr, 0, '.', ',') . '*';
        } else {
            $jml_byr = 1 * $before[jml_byr];
            $print_jml_byr = number_format($jml_byr, 0, '.', ',') . '';
        }

        if ($after[TagihanID] == $before[TagihanID]) {
            if (!$isFirstRow) {
                $TotalJmlByr = $TotalJmlByr + $jml_byr;
                $countRow = $countRow + 1;
            }
            //$isFirstRow diubah di bawah;
            $printTotalJmlByr = '';
            $printHutang = '';
            $printKet = '';
        } else {
            if ($countRow > 0) {
                $TotalJmlByr = $TotalJmlByr + $jml_byr;
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

            $printHutang = $ww[hrs_byr] - $printTotalJmlByr;
            if ($printHutang > 0) {
                $printKet = 'Kurang';
            } elseif ($printHutang == 0) {
                $printKet = 'Lunas';
            } else {
                $printKet = '';
            }
        }

        //$StsMhsw = 'Active';

        //print border
        if (!empty($ThnAjar)) {
            $bTahun = "LRT";
        } else {
            $bTahun = "LR";
        }

        if (!empty($Smt)) {
            $bSmt = "LRT";
            $bTrn = "LT";
            $bNoTrn = "RT";
        } else {
            $bSmt = "LR";
            $bTrn = "L";
            $bNoTrn = "R";
        }
        
        $barisKe = $barisKe + 1;

        if (($totalBaris + 1) == $barisKe) {
            $bTahun = $bTahun . "B";
            $bSmt = $bSmt . "B";
            $bTrn = $bTrn . "B";
            $bNoTrn = $bNoTrn . "B";
        }

        if (!$isFirstRow) {

            $p->SetFont('Helvetica', '', $rpt::fsIsi);
            $p->Cell($rpt::wThn, $rpt::hIsi, $ThnAjar, $bTahun, 0, 'L', true);
            $p->Cell($rpt::wSesi, $rpt::hIsi, $Smt, $bSmt, 0, 'L', true);
            $p->Cell($rpt::wStsMhsw, $rpt::hIsi, $StsMhsw, $bSmt, 0, 'L', true);
            
            if ($before[jml_byr] < 0) {
                $p->SetFont('Helvetica', 'I', $rpt::fsIsi);
                $NamaBipot = '';
            } else {
                $p->SetFont('Helvetica', '', $rpt::fsIsi);
            }
            $p->Cell($rpt::wNamaBipot, $rpt::hIsi, $NamaBipot, $bSmt, 0, 'L', true);
            
            $p->SetFont('Helvetica', '', $rpt::fsIsi);
            
            $printTglByr = (empty($before[tgl_byr])) ? '    -    -' : $before[tgl_byr];
            $p->Cell($rpt::wTglByr, $rpt::hIsi, $printTglByr, $bSmt, 0, 'L', true);
            
            $p->Cell($rpt::wJmlByr, $rpt::hIsi, $print_jml_byr, $bSmt, 0, 'R', true);
            //$p->Cell($rpt::wJmlByr-2, $rpt::hIsi, $print_jml_byr, $bSmt, 0, 'R', true);
            //$p->Cell(2, $rpt::hIsi, '*', $bSmt, 0, 'R', true);

            //$printTotalJmlByr = (empty($printTotalJmlByr)) ? '-' : number_format($printTotalJmlByr, 0, '.', ',');
            $printTotalJmlByr = ($printTotalJmlByr == '') ? '-' : number_format($printTotalJmlByr, 0, '.', ',');
            $p->Cell($rpt::wTotalJmlByr, $rpt::hIsi, $printTotalJmlByr, $bSmt, 0, 'R', true);
            
            //$printHutang = (empty($printHutang)) ? '-' : number_format($printHutang, 0, '.', ',');
            $printHutang = (empty($printKet)) ? '-' : number_format($printHutang, 0, '.', ',');
            $p->Cell($rpt::wHutang, $rpt::hIsi, $printHutang, $bSmt, 0, 'R', true);

            $p->Cell($rpt::wTrn, $rpt::hIsi, $before[Bank], $bTrn, 0, 'L', true);
            $p->Cell($rpt::wNoTrn, $rpt::hIsi, $before[BayarMhswID], $bNoTrn, 0, 'C', true);
            $p->Cell($rpt::wKet, $rpt::hIsi, $printKet, $bSmt, 1, 'L', true);
        } else {
            $isFirstRow = false;
        }
        //increment di atas
        if (($totalBaris + 1) == $barisKe) {
            break;
        }
    }
    
    $p->Ln();
    $p->Cell($lbr, $rpt::hIsi, "*) Potongan", 0);
    $p->Ln();
    
    $spasi = ($rpt::wThn+$rpt::wSesi+$rpt::wStsMhsw+$rpt::wNamaBipot+$rpt::wTglByr+$rpt::wJmlByr+$rpt::wTotalJmlByr);
    $p->Cell($spasi, $rpt::hIsi, "", 0);
    $s =
    "SELECT UCASE(a.Nama) AS Nama_User, b.Kota AS Kota,
    DATE_FORMAT(NOW(),'%d-%m-%Y') AS Tgl_Buat FROM ".$_SESSION[_TabelUser]." a, identitas b
    WHERE a.Login='".$_SESSION[_Login]."'
    AND a.KodeID = '".$_SESSION[_KodeID]."' 
    AND a.NA = 'N'
    AND b.Kode = a.KodeID";

    $q = _query($s);
    
    $w = _fetch_array($q);
    
    $p->Cell($lbr, $rpt::hIsi, $w[Kota].", ".$w[Tgl_Buat], 0);
    $p->Ln();
    $p->Cell($spasi, $rpt::hIsi, "", 0);
    $p->Cell($lbr, $rpt::hIsi, "Petugas,", 0);
    $p->Ln(9);
    $p->Cell($spasi, $rpt::hIsi, "", 0);
    $p->Cell($lbr, $rpt::hIsi, $w[Nama_User], 0);
    
}

function BuatHeadernya($p) {
    global $lbr;
    
    $rpt = new ReportConfig();

    $p->SetFont('Helvetica', '', $rpt::fsHeader);
    $p->SetFillColor(255, 255, 255); //background putih
    $p->Cell($lbr, $rpt::hHeader, "REKAP PEMBAYARAN KEUANGAN MAHASISWA", 0, 1, 'L', true);
    $p->Cell(($rpt::wThn+$rpt::wSesi+$rpt::wStsMhsw-2), $rpt::hHeader, "Nama Mahasiswa", 0);
    $p->Cell(2, $rpt::hHeader, ":", 0);
    
    $s = "SELECT UCASE(Nama) AS Nama FROM mhsw WHERE MhswID = '$_SESSION[NPM]'";

    $q = _query($s);
    
    $w = _fetch_array($q);
    
    $p->Cell(($rpt::wNamaBipot+$rpt::wTglByr+2), $rpt::hHeader, $w[Nama], 0);
    $p->Ln();
    $p->Cell(($rpt::wThn+$rpt::wSesi+$rpt::wStsMhsw-2), $rpt::hHeader, "N P M", 0);
    $p->Cell(2, $rpt::hHeader, ":", 0);
    $p->Cell(($rpt::wNamaBipot+$rpt::wTglByr+2), $rpt::hHeader, $_SESSION[NPM], 0);
    $p->Ln(6);

    $p->SetFont('Helvetica', '', $rpt::fsHeader);
    $p->Cell($rpt::wThn, $rpt::hHeader, 'T.A.', 1, 0, 'L', true);
    $p->Cell($rpt::wSesi, $rpt::hHeader, 'Smt.', 1, 0, 'L', true);
    $p->Cell($rpt::wStsMhsw, $rpt::hHeader, 'Status', 1, 0, 'L', true);
    $p->Cell($rpt::wNamaBipot, $rpt::hHeader, 'Uraian Pembayaran', 1, 0, 'L', true);
    $p->Cell($rpt::wTglByr, $rpt::hHeader, 'Tgl. Bayar', 1, 0, 'C', true);
    $p->Cell($rpt::wJmlByr, $rpt::hHeader, 'Bayar', 1, 0, 'C', true);
    $p->Cell($rpt::wTotalJmlByr, $rpt::hHeader, 'Total', 1, 0, 'C', true);
    $p->Cell($rpt::wHutang, $rpt::hHeader, 'Tunggakan', 1, 0, 'C', true);
    $p->Cell($rpt::wTrn, $rpt::hHeader, 'Bukti Bayar', 'LTB', 0, 'L', true);
    $p->Cell($rpt::wNoTrn, $rpt::hHeader, 'Kwitansi', 'RTB', 0, 'C', true);
    $p->Cell($rpt::wKet, $rpt::hHeader, 'Ket.', 1, 1, 'C', true);
}

?>
