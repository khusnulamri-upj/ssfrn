<?php

// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 14 Agustus 2008

session_start();
include_once "../sisfokampus1.php";
include_once "../$_SESSION[mnux].lib.php";

HeaderSisfoKampus("Edit BIPOT");

// *** Parameters ***
//function ByrEdit(mhswid, khsid, md, bayarid)
$MhswID = sqling($_REQUEST['MhswID']);
$KHSID = $_REQUEST['KHSID'] + 0;
$md = $_REQUEST['md'] + 0;
$BayarID = sqling($_REQUEST['BayarID']);

// *** Main ***
$gos = (empty($_REQUEST['gos'])) ? 'Edit' : $_REQUEST['gos'];
$gos($MhswID, $KHSID, $md, $BayarID);

// *** Functions ***
function Edit($MhswID, $KHSID, $md, $BayarID) {
    $mhsw = GetFields('mhsw', "KodeID='" . KodeID . "' and MhswID", $MhswID, '*');
    $khs = GetFields('khs', 'KHSID', $KHSID, '*');
    if ($md == 0) {
        $jdl = "Edit Pembayaran";
        $w = GetFields('bayarmhsw', 'BayarMhswID', $BayarID, '*');
    } elseif ($md == 1) {
        $jdl = "Tambah Pembayaran";
        $w = array();
        $rekening_def = GetaField('rekening', "Def = 'Y' and NA", 'N', 'RekeningID');
        $w['RekeningID'] = $rekening_def;
        $w['Tanggal'] = date('Y-m-d');
    }
    else
        die(ErrorMsg('Error', "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
    // Tampilkan
    BayarScript();
    CheckFormScript('RekeningID,Bank');
    $optrek = GetOption2('rekening', "concat(RekeningID, ' - ', Nama)", 'RekeningID', $w['RekeningID'], "KodeID='" . KodeID . "'", 'RekeningID');
    $optTanggal = GetDateOption($w['Tanggal'], 'Tanggal');
    TampilkanJudul($jdl);
    echo "
  <table class=box cellspacing=1 width=100%>
  <form name='frmBayar' action='../$_SESSION[mnux].bayar.php' method=POST onSubmit=\"return CheckForm(this)\">
  <input type=hidden name='gos' value='Simpan' />
  <input type=hidden name='MhswID' value='$MhswID' />
  <input type=hidden name='KHSID' value='$KHSID' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='BayarID' value='$BayarID' />
  
  <tr><td class=inp>Dibayar oleh:</td>
      <td class=ul1 colspan=3>
        <font size=+1>$mhsw[Nama]</font> <sup>(NIM: $MhswID)</sup>
      </td>
      </tr>
  <tr><td class=inp>Ke Rekening:</td>
      <td class=ul1><select name='RekeningID'>$optrek</select></td>
      <td class=inp>Tgl Setor:</td>
      <td class=ul1>$optTanggal</td>
      </tr>
  <tr><td class=inp>Dibayar Dari Bank:</td>
      <td class=ul1><input type=text name='Bank' value='$w[Bank]' size=30 maxlength=50 /> <a href='#' onClick=\"javascript:SetCash()\">Set CASH</a><br />
        <sup>Isi dengan `CASH` bila dibayarkan dengan Tunai</sup>
      </td>
      <td class=inp>No. Bukti Setor:</td>
      <td class=ul1><input type=text name='BuktiSetoran' value='$w[BuktiSetoran]'
        size=30 maxlength=50 /><br />
        <sup>Kosongkan jika pembayaran cash</sup>
      </td>
      </tr>
  <tr><td class=inp>Catatan:</td>
      <td class=ul1 colspan=3><input type=text name='Keterangan' value='$w[Keterangan]'
        size=90 maxlength=100 />
      </td>
      </tr>
  </table>";
    TampilkanDetailBiaya($MhswID, $mhsw, $khs, $md);
}

function TampilkanDetailBiaya($MhswID, $mhsw, $khs, $md) {
    //22 juli 2013 
    $s = "select bm.*, s.Nama as _saat,
      format(bm.Jumlah, 0) as JML,
      format(bm.Besar, 0) as BSR,
      (bm.Jumlah * bm.Besar) as SubTTL,
      ((bm.Jumlah * bm.Besar) - bm.Dibayar) as SISA,
      bn.DipotongBeasiswa, b2.PerMataKuliah, b2.PerSKS, b2.PerLab
    from bipotmhsw bm
      left outer join bipot2 b2 on b2.BIPOT2ID = bm.BIPOT2ID
      left outer join saat s on b2.SaatID = s.SaatID
	  left outer join bipotnama bn on bn.BIPOTNamaID = bm.BIPOTNamaID
	where bm.MhswID = '$MhswID'
      and bm.PMBMhswID = 1
      and bm.TahunID = '$khs[TahunID]'
      and bm.KodeID = '" . KodeID . "'
      and bm.NA = 'N'
      order by bm.TagihanID, b2.Prioritas, bm.TrxID DESC, bm.BIPOTMhswID";
    //order by bm.TrxID, bm.BIPOTMhswID
    $r = _query($s);
    $r2 = _query($s);
    $n = 0;
    $sisa = 0;
    $potongan = 0;
    $arrPotongan = array();
    echo "<table class=box cellspacing=1 width=100%>";
    echo "<tr><th class=ttl colspan=7>Detail Biaya</th></tr>";
    echo "<tr>
      <th class=ttl>#</th>
      <th class=ttl>Nama Biaya<hr size=1 color=silver />Saat Bayar</th>
      <th class=ttl width=100>Jumlah
        <hr size=1 color=silver />
        Besar Biaya</th>
      <th class=ttl width=100>Sub Total</th>
      <th class=ttl width=100>Sdh Dibayar</th>
      <th class=ttl width=100>Dibayarkan</th>
      </tr>";
    $grpTagihanID = "xxxyyyxzzzss121";
    $jmlarrPotongan = array();
    $jmlPotongan = array();
    while ($wx = _fetch_array($r)) {
        //22 juli 2013       
        if ($wx['TagihanID'] != $grpTagihanID) {
            $grpTagihanID = $wx['TagihanID'];
            $arrPotongan[$wx['TambahanNama']] = 0;
            //$testx = 0;           
            $potongan = 0;
        }
        if ($wx['TrxID'] < 1) { // hitung potongan                    
            //potongan                        
            //if ($wx['PerMataKuliah'] == 'Y' or $wx['PerSKS'] == 'Y' or $wx['PerLab'] == 'Y') {
            $arrPotongan[$wx['TambahanNama']] += $wx['Jumlah'] * $wx['Besar'];
            //$testx += $wx['Jumlah'] * $wx['Besar'];
            $jmlarrPotongan[$wx['TagihanID']] = $arrPotongan[$wx['TambahanNama']];
            //} else {
            $potongan += $wx['Jumlah'] * $wx['Besar'];
            $jmlPotongan[$wx['TagihanID']] = $potongan;
            //}
            //echo 'test='.$grpTagihanID.' = '.$testx.'<br>';
        }
    } // while
    // tampilkan
    while ($w = _fetch_array($r2)) {
        /* if ($w['TrxID'] < 1) { // hitung potongan            
          //potongan
          if ($w['PerMataKuliah'] == 'Y' or $w['PerSKS'] == 'Y' or $w['PerLab'] == 'Y') {
          $arrPotongan[$w['TambahanNama']] += $w['Jumlah'] * $w['Besar'];
          } else {
          $potongan += $w['Jumlah'] * $w['Besar'];
          }
          } else { */ // hitung tagihan
        if ($w['TrxID'] >= 1) {
            $arrPotongan[$w['TambahanNama']] = $jmlarrPotongan[$w['TagihanID']];
            $potongan = $jmlPotongan[$w['TagihanID']];
            //tagihan                        
            $n++;
            if ($w['PerMataKuliah'] == 'Y' or $w['PerSKS'] == 'Y' or $w['PerLab'] == 'Y') {
                $potonganpartial = $arrPotongan[$w['TambahanNama']];
                if ($potonganpartial > 0 and $w['DipotongBeasiswa'] == 'Y') {
                    if ($potonganpartial >= $w['SISA']) {
                        $Dibayar = $w['SubTTL'];
                        $selisih = 0;
                        $sisa += $selisih;
                        $arrPotongan[$w['TambahanNama']] -= $w['SISA'];
                    } else {
                        $selisih = $w['SISA'] - $potonganpartial;
                        $Dibayar = $w['Dibayar'] + $potonganpartial;
                        $sisa += $selisih;
                        $arrPotongan[$w['TambahanNama']] = 0;
                    }
                } else {
                    $Dibayar = $w['Dibayar'];
                    $selisih = $w['SISA'];
                    $sisa += $selisih;
                }
            } else { // bukan Per matakuliah, per sks atau per lab
                if ($potongan > 0 and $w['DipotongBeasiswa'] == 'Y') {
                    if ($potongan >= $w['SISA']) {
                        $Dibayar = $w['SubTTL'];
                        $selisih = 0;
                        $sisa += $selisih;
                        $potongan -= $w['SISA'];
                    } else {
                        $selisih = $w['SISA'] - $potongan;
                        $Dibayar = $w['Dibayar'] + $potongan;
                        $sisa += $selisih;
                        $potongan = 0;
                    }
                } else {
                    $Dibayar = $w['Dibayar'];
                    $selisih = $w['SISA'];
                    $sisa += $selisih;
                }
            }
            if ($Dibayar >= $w['Jumlah'] * $w['Besar']) {
                $ro = 'readonly=TRUE';
                $c = "class=nac";
            } else {
                $ro = '';
                $c = "class=ul";
            }
            $SubTTL = number_format($w['SubTTL'], 2, ',', '.');
            $Dibayar = number_format($Dibayar, 2, ',', '.');
            echo "<tr>
            <td class=inp>$n</td>
            <td $c><b>$w[Nama]</b><br /><sup>$w[TambahanNama]</sup><div align=right><sub>$w[_saat]</sub></div></td>
            <td $c><sup>$w[JML] &times;</sup><br /><div align=right>$w[BSR]</div></td>
            <td $c align=right><a href='#' onClick=\"javascript:SetBayar($n, $selisih); HitungUlang();\">$SubTTL &raquo;</a> <input type=hidden name='SUBTTL_$n' value='$w[SubTTL]' /></td>
            <td $c align=right>$Dibayar</td>
            <td $c><input type=hidden name='BYRID_$n' value='$w[BIPOTMhswID]' /><input type=text name='BYR_$n' value='0' size=10 maxlength=20 style='text-align:right' onChange='HitungUlang()' $ro /></td>
            </tr>";
        } // if
    } // while
    $_sisa = number_format($sisa);
    echo "<input type=hidden name='CNT' value='$n' />";
    echo "<tr><td bgcolor=silver colspan=10 height=1></td></tr>";
    echo "<tr>
    <td class=ul1 colspan=4 align=right>Total Yg Harus Dibayar:</td>
    <td class=ul1 align=right><font size=+1>$_sisa</font></td>
    <td class=ul1><input type=text name='TTLBYR' size=10 maxlength=50 readonly=TRUE style='text-align:right' /></td>
    </tr>";
    echo "<tr>
    <td class=ul1 colspan=10 align=center>
    <input type=submit  name='Simpan' value='Simpan' />
    <input type=button name='Batal' value='Batal' onClick=\"window.close()\" />
    </td></tr>";
    echo "</form>";
    echo "</table></p>";
    echo <<<ESD
  <script>
  function SetBayar(n, jml) {
    //alert("frmBayar.BYR_" + n + ".value = 'test';");
    eval("frmBayar.BYR_" + n + ".value = '" + jml +"';");
  }
  </script>
ESD;
    HitungUlang($n);
}

//($MhswID, $KHSID, $md, $BayarID);
function Simpan($MhswID, $KHSID, $md, $BayarID) {
    $RekeningID = sqling($_REQUEST['RekeningID']);
    $Bank = sqling($_REQUEST['Bank']);
    $BuktiSetoran = sqling($_REQUEST['BuktiSetoran']);
    $Keterangan = sqling($_REQUEST['Keterangan']);
    $Tanggal = "$_REQUEST[Tanggal_y]-$_REQUEST[Tanggal_m]-$_REQUEST[Tanggal_d]";
    $CNT = $_REQUEST['CNT'] + 0;
    // Cek jika tidak ada detailnya
    if ($CNT == 0)
        die(ErrorMsg('Error', "Tidak ada detail biaya.<br />
      Data tidak bisa disimpan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
    // Cek dulu jumlah yg dibayarkan
    $jml = 0;
    $PembayaranValid = TRUE;
    for ($i = 1; $i <= $CNT; $i++) {
        $jml += $_REQUEST['BYR_' . $i] + 0;
        $byr = $_REQUEST['BYR_' . $i] + 0;
        $subttl = $_REQUEST['SUBTTL_' . $i] + 0;
        //if ($subttl < $byr)
            //$PembayaranValid = FALSE;
    }
    if ($jml <= 0) {
        die(ErrorMsg('Error', "Jumlah yang Anda bayarkan: <font size=+1>$jml</font>.<br />
      Tidak ada yang perlu dibayarkan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" /"));
    }
    if ($PembayaranValid == FALSE) {
        die(ErrorMsg('Error', "Pembayaran: <font size=+1>TIDAK VALID</font>.<br />
      Pembayaran tidak boleh melebihi Sub Total.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" /"));
    }
    $mhsw = GetFields('mhsw', "KodeID='" . KodeID . "' and MhswID", $MhswID, '*');
    $khs = GetFields('khs', 'KHSID', $KHSID, '*');
    // Oke, mulai simpan datanya
    // 1. Ambil nomer terakhir yang ada
    $BayarMhswID = GetNextBPM();
    // 2. Buat header bukti pembayaran
    $s = "insert into bayarmhsw
    (BayarMhswID, TahunID, KodeID,
    RekeningID, MhswID, TrxID, PMBMhswID,
    Bank, BuktiSetoran, Tanggal,
    Jumlah, Keterangan,
    LoginBuat, TanggalBuat)
    values
    ('$BayarMhswID', '$khs[TahunID]', '" . KodeID . "',
    '$RekeningID', '$mhsw[MhswID]', 1, 1,
    '$Bank', '$BuktiSetoran', '$Tanggal',
    $jml, '$Keterangan',
    '$_SESSION[_LoginBuat]', now())";
    $r = _query($s);
    // 3. Simpan detailnya
    for ($i = 1; $i <= $CNT; $i++) {
        $_j = $_REQUEST['BYR_' . $i] + 0;
        if ($_j > 0) { // Simpan
            $id = $_REQUEST['BYRID_' . $i] + 0;
            $BIPOTNamaID = GetaField('bipotmhsw', "BIPOTMhswID", $id, 'BIPOTNamaID');
            // Simpan dulu detail pembayarannya
            $s = "insert into bayarmhsw2
        (BayarMhswID, BIPOTMhswID, BIPOTNamaID, Jumlah,
        LoginBuat, TanggalBuat)
        values
        ('$BayarMhswID', $id, '$BIPOTNamaID', $_j,
        '$_SESSION[_Login]', now())";
            $r = _query($s);
            // Update detail biayanya
            $s = "update bipotmhsw
        set Dibayar = Dibayar + $_j
        where BIPOTMhswID = $id";
            $r = _query($s);
        }
    }
    HitungUlangBIPOTMhsw($MhswID, $khs['TahunID']);
    TutupScript($MhswID);
}

function BayarScript() {
    echo <<<SCR
  <script>
  function SetCash() {
    frmBayar.Bank.value = 'CASH';
    frmBayar.BuktiSetoran.value = 'CASH';
  }
  </script>
SCR;
}

function HitungUlang($n) {
    echo <<<SCR
  <script>
  function HitungUlang() {
    var i = 0;
    var ttl = 0;
SCR;
    for ($i = 1; $i <= $n; $i++) {
        echo "
    ttl = ttl + Number(frmBayar.BYR_" . $i . ".value);\n";
    }
    echo <<<SCR
    frmBayar.TTLBYR.value = FormatNumberBy3(ttl, ",", ",");
  }
    
    // function to format a number with separators. returns formatted number.
    // num - the number to be formatted
    // decpoint - the decimal point character. if skipped, "." is used
    // sep - the separator character. if skipped, "," is used
    function FormatNumberBy3(num, decpoint, sep) {
      // check for missing parameters and use defaults if so
      if (arguments.length == 2) {
        sep = ",";
      }
      if (arguments.length == 1) {
        sep = ",";
        decpoint = ".";
      }
      // need a string for operations
      num = num.toString();
      // separate the whole number and the fraction if possible
      a = num.split(decpoint);
      x = a[0]; // decimal
      y = a[1]; // fraction
      z = "";


      if (typeof(x) != "undefined") {
        // reverse the digits. regexp works from left to right.
        for (i=x.length-1;i>=0;i--)
          z += x.charAt(i);
        // add seperators. but undo the trailing one, if there
        z = z.replace(/(\d{3})/g, "$1" + sep);
        if (z.slice(-sep.length) == sep)
          z = z.slice(0, -sep.length);
        x = "";
        // reverse again to get back the number
        for (i=z.length-1;i>=0;i--)
          x += z.charAt(i);
        // add the fraction back in, if it was there
        if (typeof(y) != "undefined" && y.length > 0)
          x += decpoint + y;
      }
      return x;
    }
    
  </script>

SCR;
}

function TutupScript($MhswID) {
    echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]&gos=DetailMhsw&MhswID=$MhswID';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}

?>