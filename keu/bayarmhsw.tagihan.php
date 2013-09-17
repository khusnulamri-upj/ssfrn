<?php

session_start();
include_once "../dwo.lib.php";
include_once "../db.mysql.php";
include_once "../connectdb.php";
include_once "../parameter.php";
include_once "../cekparam.php";
include_once '../util.lib.php';

$TahunID = $_REQUEST['TahunID'];
$MhswID = $_REQUEST['MhswID'];

$mhsw = GetFields('mhsw', "KodeID='" . KodeID . "' and MhswID", $MhswID, 'MhswID, Nama, ProdiID, ProgramID');

$NamaTahun = GetaField('tahun', "KodeID='" . KodeID . "' and TahunID='$TahunID' and ProdiID", $mshw['ProdiID'], 'Nama');

$identitas = GetFields('identitas', 'Kode', KodeID, 'Nama, Alamat1,Alamat2, Kota, KodePos, Telepon, Fax');

$smt = ((substr($TahunID, -1)) % 2 == 0) ? "Genap" : "Ganjil";
$smt_sesi = (substr($TahunID, 0, -1)) - substr($MhswID, 0, 4);
if (substr($TahunID, -1) == 1) {
    $smt_sesi = ($smt_sesi * 2) + 1;
} else {
    $smt_sesi = ($smt_sesi * 2) + 2;
}
$content = "";
$content .= '        
    <style> 
        .content{
            margin-left:40px;  
            font-family:Arial;
            font-size:10px;
        } 
        
    .footer{
            margin-left:40px;  
            font-family:Arial;
            font-weight:bold;
            font-size:8px;
            background-color:red;
            margin-top:50px;
            padding-left:5px;
            padding-top:5px;
            padding-bottom:5px;
            text-align:left;
        }   
        
        table.garis {
            border-collapse:collapse;         
        }
        table.garis tr {
            border:1px solid black;
        }
        table.garis td {
            border:1px solid black;
        }        
        
        .tengah{
            text-align:center;
        }
        .kiri{
            text-align:left;padding-left:5px;
        }
        
        .kanan{
            text-align:right;padding-right:2px;
        }
    </style>        
   
    <div class="content">
    <table>
        <tr>            
            <td width="620"><div style="font-size:24px;font-weight:bold;text-align:center">TAGIHAN</div></td>            
        </tr>
    </table>
    
    <table>
        <tr>   
            <td width="300" rowspan="7" style="padding-top:5px;"><img src="logo.jpg" width="120"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>            
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
           
            <td width="200"></td>
            <td valign="bottom">NO : </td>
        </tr>
        <tr>            
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>            
            <td></td>
            <td>Ditagihkan kepada :</td>
        </tr>
        <tr>            
            <td></td>
            <td></td>
        </tr>
        <tr>            
            <td></td>
            <td><b>' . strtoupper($mhsw["Nama"]) . '</b></td>
        </tr>
        <tr>
            <td>' . $identitas["Alamat1"] . '</td>
            <td></td>
            <td>NIM : <b>' . $mhsw["MhswID"] . '</b></td>
        </tr>
        <tr>
            <td>' . $identitas["Alamat2"] . ',' . $identitas["Kota"] . ' ' . $identitas["KodePos"] . '</td>
            <td></td>
            <td>Prodi : ' . $mhsw["ProdiID"] . ' Smt : ' . $smt . ' - ' . $smt_sesi . '</td>
        </tr>
        <tr>
            <td> Tlp.' . $identitas["Telepon"] . ', Fax : ' . $identitas["Fax"] . '</td>
            <td></td>
            <td>Tahun : ' . substr($TahunID, 0, -1) . '</td>
        </tr>
        <tr>
            <td>Bagian Keuangan, Psw : 132</td>
            <td></td>
            <td>Jenjang : ' . GetaField('prodi p left outer join jenjang j on p.JenjangID=j.JenjangID', "p.ProdiID='$mhsw[ProdiID]' and p.KodeID", KodeID, "concat(j.Nama, ' - ', j.Keterangan)") . '</td>
        </tr>
    </table>   
    
    <p><b>DETAIL TAGIHAN</b></p>
    <table class="garis">
        <tr style="text-align:center;">
            <td width="40">NOMOR</td>
            <td width="315">DESKRIPSI</td>
            <td width="25">UNIT</td>
            <td width="70">HARGA/UNIT</td>
            <td width="70">JUMLAH</td>
            <td width="70">DIBAYAR</td>
            <td width="70">SISA</td>
        </tr>';

/* $s = "SELECT TambahanNama, Nama, TrxID, Jumlah, Besar, Dibayar 
  FROM bipotmhsw
  WHERE KodeID = '" . KodeID . "' and MhswID = '" . $MhswID . "' and TahunID = '" . $TahunID . "' and NA='N'
  ORDER BY BIPOTMhswID asc"; */

$s = "select bm.*, s.Nama as _saat,
    format(bm.Jumlah, 0) as JML,
    format(bm.TrxID*bm.Besar, 0) as BSR,
    format(bm.Dibayar, 0) as BYR , b2.Prioritas
    from bipotmhsw bm
    left outer join bipot2 b2 on b2.BIPOT2ID = bm.BIPOT2ID
    left outer join saat s on b2.SaatID = s.SaatID
    where bm.PMBMhswID = 1    
      and bm.NA = 'N'  
      and bm.KodeID = '" . KodeID . "'
      and bm.MhswID = '" . $MhswID . "'
      and bm.TahunID = '" . $TahunID . "'
    order by bm.TagihanID, b2.Prioritas, bm.TrxID DESC, bm.BIPOTMhswID";

$q = _query($s);
$Total = 0;
$no = 1;

$s_total = "SELECT sum(TrxID*Jumlah*Besar) as total1, sum(Dibayar) as total2
        FROM bipotmhsw 
        WHERE KodeID = '" . KodeID . "' and MhswID = '" . $MhswID . "' and TahunID = '" . $TahunID . "' and NA='N'";
$q_total = _query($s_total);
$d_total = _fetch_array($q_total);
if ($d_total["total1"] <> $d_total["total2"]) {
    while ($d = _fetch_array($q)) {

        $cek_grpTagihan = GetaField("bipotmhsw", "NA='N' and TagihanID", $d["TagihanID"], "sum(TrxID*Jumlah*Besar) - sum(Dibayar)");
        if ($cek_grpTagihan <= 0) {
            
        } else {
            $Dibayar = $d['Dibayar'];
            $Besar2 = $d['Besar'] - $Dibayar;
            $SubTotal = $d['TrxID'] * $d['Jumlah'] * $d['Besar'];
            $Tagihan = $SubTotal - $Dibayar;
            if ($d['TrxID'] == -1) {
                $SubTotal = "(" . number_format($d['Jumlah'] * $d['Besar'], 0, ',', '.') . ")";
                $Tagihan2 = "(" . number_format($d['Jumlah'] * $d['Besar'], 0, ',', '.') . ")";
            } else {
                $Tagihan2 = number_format($Tagihan, 0, ',', '.');
                $SubTotal = number_format($SubTotal, 0, ',', '.');
            }

            $TrxID2 = ($d['TrxID'] == -1) ? '-' : '';
            $TambahanNama = (empty($w['TambahanNama'])) ? "" : ' (' . $w['TambahanNama'] . ')';
            $Total += $Tagihan;
            if ($Tagihan <> 0) {
                $content .='<tr>
        <td class="kanan">' . $no++ . '</td>
        <td class="kiri">' . $d["Nama"] . '</td>
        <td class="kanan">' . $d['TrxID'] * $d['Jumlah'] . '</td>
        <td class="kanan">' . number_format($d['Besar'], 0, ',', '.') . '</td>
        <td class="kanan">' . $SubTotal . '</td>
        <td class="kanan">' . number_format($d['Dibayar'], 0, ',', '.') . '</td>
        <td class="kanan">' . $Tagihan2 . '</td>
        </tr>';
            }
        }
    }
}

while ($no <= 8) {
    $content .='<tr>
        <td class="kanan">&nbsp;</td>
        <td class="kiri">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        </tr>';
    $no++;
}


$content .=' </table>
    <br>
    <table class="garis">
        <tr>
            <td width="584" style="text-align:left;padding-left:5px;"><b>TOTAL YANG HARUS DIBAYARKAN : </b></td>
            <td width="95" style="text-align:right;padding-right:5px;"><b>' . number_format($Total, 0, ',', '.') . '</b></td>
        </tr>
    </table>    
   
';

$identitas = GetFields('identitas', 'Kode', KodeID, '*');
$rekening = GetFields('rekening', "Def='Y' and KodeID", KodeID, '*');
$current_date = date('Y-m-d');
$content .= '
    <br>
    <table class="tidakgaris">
        <tr>
            <td  width="220">PEMBAYARAN DG TRANSFER KIRIM KE : </td>
            <td width="250" class="tengah">TANDA TERIMA TAGIHAN</td>
            <td>' . strtoupper($identitas['Kota']) . ',' . strtoupper(GetDateInWords($current_date)) . '</td>
        </tr>
        <tr>
            <td> BANK ' . strtoupper($rekening['Bank']) . ',' . strtoupper($rekening['Cabang']) . '</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>NO REK :' . $rekening['RekeningID'] . '</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>AN. ' . strtoupper($rekening['Nama']) . '</td>
            <td class="tengah">(.......................................)</td>
            <td></td>
        </tr>
    </table>
    </div>
    
    <div class="footer">
        DOKUMEN PENAGIHAN TIDAK MEMERLUKAN TANDA TANGAN.DOKUMEN DI CETAK OLEH BAG. KEUANGAN UPJ UNTUK DIDISTRIBUSIKAN MELALUI BAP-PMP
    </div>
   
';



require_once('../html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L', 'A5');
$html2pdf->WriteHTML($content);
$html2pdf->Output('Tagihan_' . $MhswID . '_' . $TahunID . '.pdf');
?>

