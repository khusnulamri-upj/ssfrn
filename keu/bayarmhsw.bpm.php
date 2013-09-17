<?php

session_start();
include_once "../dwo.lib.php";
include_once "../db.mysql.php";
include_once "../connectdb.php";
include_once "../parameter.php";
include_once "../cekparam.php";
include_once '../util.lib.php';

$id = sqling($_REQUEST['id']);
$trx = $_REQUEST['trx'] + 0;

$byr = GetFields('bayarmhsw', "BayarMhswID='$id' and KodeID", KodeID, "*");
$mhsw = GetFields('mhsw', "MhswID = '$byr[MhswID]' and KodeID", KodeID, '*');
$prd = GetFields('prodi', "ProdiID = '$mhsw[ProdiID]' and KodeID", KodeID, 'Nama');

$MhswID = $mhsw["MhswID"];
$TahunID = $byr['TahunID'];

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
            margin-top:30px;
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
        
        .keterangan{
            font-size:9px;
            padding : 5px;
            text-align : justify;
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
            <td width="620"><div style="font-size:24px;font-weight:bold;text-align:center">KWITANSI</div></td>            
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
            <td valign="bottom">NO KWITANSI :' . $id . ' </td>
        </tr>
        <tr>            
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            
            <td></td>
            <td><b>BUKTI PEMBAYARAN MAHASISWA</b></td>
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
            <td>Prodi : ' . $mhsw["ProdiID"] . '&nbsp;&nbsp;&nbsp;Smt : ' . $smt . ' - ' . $smt_sesi . '</td>
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
    
    <p><b>DETAIL PEMBAYARAN</b></p>
    <table class="garis">
        <tr style="text-align:center;">
            <td width="60">NOMOR</td>
            <td width="418">DESKRIPSI</td>          
            <td width="100">JUMLAH</td>
            <td width="100">KETERANGAN</td>
        </tr>';

//$s = "  SELECT b2.*, bn.Nama
//        FROM bayarmhsw2 b2
//        LEFT OUTER JOIN bipotnama bn on b2.BIPOTNamaID = bn.BIPOTNamaID
//        WHERE b2.BayarMhswID = '$id' and b2.NA = 'N'
//        ORDER BY bn.Urutan";
$s = "SELECT bm.Nama, b2.Jumlah as bayar_sekarang , bm.TagihanID , b2.BayarMhswID
        FROM bipotmhsw bm, bayarmhsw2 b2 where bm.BIPOTMhswID=b2.BIPOTMhswID and BayarMhswID = '" . $id . "' and b2.NA = 'N'";

$r = _query($s);
$Total = 0;
$no = 1;

$ket = "";
$cek_sisa = 0;
while ($w = _fetch_array($r)) {

    $cek_sisa = GetaField("bipotmhsw", "NA='N' and TagihanID", $w["TagihanID"], "sum(TrxID*Jumlah*Besar) - sum(Dibayar)");
    if ($cek_sisa <= 0) {
        $ket = "Lunas";
    } else {
        $ket = "Sebagian";
    }

    $Total += $w['bayar_sekarang'];

    $content .='<tr>
        <td class="kanan">' . $no++ . '</td>
        <td class="kiri">' . $w["Nama"] . '</td>        
        <td class="kanan">' . number_format($w["bayar_sekarang"], 0, ',', '.') . '</td>
        <td class="kiri">' . $ket. '</td>
        </tr>';
}


while ($no <= 8) {
    $content .='<tr>
        <td class="kanan">&nbsp;</td>
        <td class="kiri">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        <td class="kanan">&nbsp;</td>
        </tr>';
    $no++;
}


$content .=' </table>
    <br>
    <table class="garis">
        <tr>
            <td width="477" style="text-align:left;padding-left:5px;"><b>TOTAL PEMBAYARAN : </b></td>
            <td width="92" style="text-align:right;padding-right:5px;"><b>' . number_format($Total, 0, ',', '.') . '</b></td>
            <td width="92" style="text-align:right;padding-right:5px;">&nbsp;</td>
        </tr>
    </table>    
   
';

$identitas = GetFields('identitas', 'Kode', KodeID, '*');
$rekening = GetFields('rekening', "Def='Y' and KodeID", KodeID, '*');
$ket_bayar = GetaField("bayarmhsw", "BayarMhswID", $id, "Keterangan");
$current_date = date('Y-m-d');
$content .= '
    <br>
    <table class="tidakgaris">
        <tr>
            <td  width="220">CATATAN : <br>
            <table class="garis"><tr><td width="200" height="40" class="keterangan">'.$ket_bayar.'</td></tr></table>
            </td>
            <td width="250" class="tengah">TANDA TERIMA KWITANSI <br><br><br><br><br>(.......................................)</td>
            <td class="tengah">' . strtoupper($identitas['Kota']) . ',' . strtoupper(GetDateInWords($current_date)) . '<br><br><br><br><br>(.......................................)</td>
        </tr>
        
    </table>
    </div>   
    
    <div class="footer">
        &nbsp;
    </div>
';



require_once('../html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L', 'A5');
$html2pdf->WriteHTML($content);
$html2pdf->Output('Kwitansi_' . $MhswID . '_' . $TahunID . '.pdf');
?>

