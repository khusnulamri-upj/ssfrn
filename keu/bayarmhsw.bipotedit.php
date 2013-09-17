<?php

// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 14 Agustus 2008

session_start();
include_once "../sisfokampus1.php";
include_once "../$_SESSION[mnux].lib.php";

HeaderSisfoKampus("Edit BIPOT");

// *** Parameters ***
$MhswID = sqling($_REQUEST['MhswID']);
$TahunID = sqling($_REQUEST['TahunID']);

$md = $_REQUEST['md'] + 0;
$id = $_REQUEST['id'] + 0; // Jika edit, maka gunakan id ini utk edit biaya mhsw
// *** Main ***
$gos = (empty($_REQUEST['gos'])) ? 'Edit' : $_REQUEST['gos'];
$gos($MhswID, $TahunID, $md, $id);

// *** Functions ***
function Edit($MhswID, $TahunID, $md, $id) {
    if ($md == 0) {
        $jdl = "Edit Bipot Mhsw";
        $w = GetFields('bipotmhsw', 'BIPOTMhswID', $id, '*');
        $ro = "readonly=true disabled=true";
    } elseif ($md == 1) {
        $jdl = "Tambah Bipot Mhsw";
        $w = array();
        $w['Jumlah'] = 1;
        $ro = '';
    }
    else
        die(ErrorMsg('Error', "Mode edit <b>$md</b> tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih detail.
    <hr size=1 color=silver />
    Opsi: <input type=button name='Tutup' value='Tutup'
      onClick=\"window.close()\" />"));
    // Tampilkan formulir
    if ($_SESSION['_LevelID'] == 1) {
        $Dibayar = "<tr>
      <td class=inp>Dibayar:</td>
      <td class=ul><input type=text name='Dibayar' value='$w[Dibayar]' size=20 maxlength=20 /></td>
      </tr>";
    } else {
        $Dibayar = "<input type=hidden name='Dibayar' value='$w[Dibayar]' />";
    }
    $BIPOTID = GetaField('mhsw', 'MhswID', $MhswID, 'BIPOTID');
    $bn = GetFields('bipotnama', 'BIPOTNamaID', $w['BIPOTNamaID'], 'Nama');
    $optbipotnama = GetOption2('bipotnama a inner join bipot2 b on a.BIPOTNamaID = b.BIPOTNamaID', "concat(a.Nama, ' (', a.TrxID, ')')", 'a.TrxID, a.Urutan', $w['BIPOTNamaID'], "a.KodeID='" . KodeID . "' and b.BIPOTID='$BIPOTID' and a.NA='N' and b.NA='N'", "concat(a.BIPOTNamaID,',',b.Jumlah)", 1); // concat(a.BIPOTNamaID,',',b.Jumlah)
    $optbipotnama2 = ($md == 1) ? "<select id='bipotnamaid2' name='BIPOTNamaID2' onChange='test(this.value)' $ro>$optbipotnama</select>" : "";
    $txtbipotnama = ($md == 1) ? "" : "<input type=text value='$bn[Nama]' size=50 length=50 disabled>";
    echo "<p><table class=box cellspacing=1 width=100%>
  <form action='../$_SESSION[mnux].bipotedit.php' method=POST>
  <input type=hidden name='gos' value='Simpan' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='MhswID' value='$MhswID' />
  <input type=hidden name='TahunID' value='$TahunID' />
  <input type=hidden name='id' value='$id' />  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp>BIPOT:</td><td class=ul1>
    $optbipotnama2
    $txtbipotnama
    <input type=hidden id='bipotnamaid' name='BIPOTNamaID' value='$w[BIPOTNamaID]'> </td></tr>
  <tr><td class=inp>Jumlah:</td><td class=ul1><input type=text name='Jumlah' value='$w[Jumlah]' size=3 maxlength=3 /></td></td>
  <tr><td class=inp>Besar, Rp:</td><td class=ul1><input type=text id='besar' name='Besar' value='$w[Besar]' size=20 maxlength=20 /></td></tr>
  $Dibayar
  <tr><td class=inp>Catatan:</td><td class=ul1><textarea name='Catatan' cols=30 rows=4>$w[Catatan]</textarea></td></tr>
  <tr><td class=ul1 colspan=2 align=center><input type=submit name='Simpan' value='Simpan' /><input type=button name='Batal' value='Batal' onClick=\"window.close()\" /></td></tr>  
  </form></table></p>";
}

function Simpan($MhswID, $TahunID, $md, $id) {   
    $BIPOTNamaID = $_REQUEST['BIPOTNamaID'] + 0;
    $Jumlah = $_REQUEST['Jumlah'] + 0;
    $Besar = $_REQUEST['Besar'] + 0;
    $Dibayar = $_REQUEST['Dibayar'] + 0;
    $Catatan = sqling($_REQUEST['Catatan']);
    
    $cek_tagihan = GetaField("bipotmhsw", "MhswID='$MhswID' and TahunID='$TahunID' and NA='N' and TrxID", 1, "TagihanID", "order by BIPOTMhswID desc");
    $cek_sisa = GetaField("bipotmhsw", "NA='N' and TagihanID", $cek_tagihan, "sum(TrxID*Jumlah*Besar)");       
    $trxBIPOTID = GetaField('mhsw', "MhswID", $MhswID, 'BIPOTID');
    $trxbipot = GetaField('bipot2', "BIPOTID = '".$trxBIPOTID."' and BIPOTNamaID", $BIPOTNamaID, "TrxID");
    
    if(($trxbipot < 0) && !empty($cek_tagihan) && ($cek_sisa-($Jumlah*$Besar)<0)){        
        die(ErrorMsg('Stop',"Potongan tidak boleh melebihi biaya.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
    }
    
    // Simpan
    if ($md == 0) {
        $s = "update bipotmhsw set Jumlah = '$Jumlah', Besar  = '$Besar', Dibayar = '$Dibayar', Catatan = '$Catatan', LoginEdit = '$_SESSION[_Login]', TanggalEdit = now() where BIPOTMhswID = '$id' ";
        $r = _query($s);

        $s = "update bayarmhsw2 set Jumlah = '$Dibayar' where BIPOTMhswID = '$id' and BIPOTNamaID = '$BIPOTNamaID'";
        $r = _query($s);

        $BayarMhswID = GetaField('bayarmhsw2', "BIPOTMhswID = '$id' and BIPOTNamaID", $BIPOTNamaID, 'BayarMhswID');
        $TotalDibayar = GetaField('bayarmhsw2', "BIPOTMhswID = '$id' and NA = 'N' and BIPOTNamaID", $BIPOTNamaID, 'sum(Jumlah)') + 0;
        $s = "update bayarmhsw set Jumlah = $TotalDibayar where BayarMhswID = '$BayarMhswID'";
        $r = _query($s);

        HitungUlangBIPOTMhsw($MhswID, $TahunID);
        TutupScript($MhswID, $TahunID);
    } elseif ($md == 1) {
        $BIPOTID = GetaField('mhsw', "MhswID", $MhswID, 'BIPOTID');
        $bn = GetFields('bipotnama', 'BIPOTNamaID', $BIPOTNamaID, '*');
        $b2 = GetFields('bipot2', "BIPOTID = '$BIPOTID' and BIPOTNamaID", $BIPOTNamaID, '*');
        $s = "insert into bipotmhsw (KodeID, PMBMhswID, MhswID, TahunID,
        BIPOT2ID, BIPOTNamaID, Nama, TrxID,
        Jumlah, Besar, Dibayar, Catatan,
        LoginBuat, TanggalBuat)
      values ('" . KodeID . "', 1, '$MhswID', '$TahunID',
        '$b2[BIPOT2ID]', $BIPOTNamaID, '$bn[Nama]', $bn[TrxID],
        $Jumlah, $Besar, $Dibayar, '$Catatan',
        '$_SESSION[_Login]', now())";
        $r = _query($s);

        //22 juli 2013   
        //jika insert tagihan
        $idt = mysql_insert_id();
        if ($bn[TrxID] == 1) {
            $s = "update bipotmhsw set TagihanID = '" . $idt . "' where BIPOTMhswID='" . $idt . "'";
            $r = _query($s);
        } else {
            //jika insert potongan
            $cek_tagihan = GetaField("bipotmhsw", "MhswID='$MhswID' and TahunID='$TahunID' and NA='N' and TrxID", 1, "TagihanID", "order by BIPOTMhswID desc");
            $s = "update bipotmhsw set TagihanID = '" . $cek_tagihan . "' where BIPOTMhswID='" . $idt . "'";
            $r = _query($s);
        }

        HitungUlangBIPOTMhsw($MhswID, $TahunID);
        TutupScript($MhswID, $TahunID);
    }
    else
        die(ErrorMsg('Error', "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    Opsi: <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />"));
}

function TutupScript($MhswID, $TahunID) {
    echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]&gos=&MhswID=$MhswID&TahunID=$TahunID';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}

echo <<<ESD
  <script>
  function test(cval) {		
    var str = cval;    
    var str2 = str.split(',');
    var bipotnamaid = str2[0];    
    var besar = str2[1];    
    (document.getElementById('bipotnamaid')).value = bipotnamaid;
    (document.getElementById('besar')).value = besar;    
  }

  </script>
ESD;
?>
