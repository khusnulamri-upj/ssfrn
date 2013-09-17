<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 23 September 2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Daftarkan Wisudawan");
echo <<<SCR
  <script src="../$_SESSION[mnux].wan.script.js"></script>
SCR;

// *** Parameters ***
$TahunID = GetSetVar('TahunID');
$ProdiID = GetSetVar('ProdiID');
$md = $_REQUEST['md']+0;
$id = $_REQUEST['id']+0;

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'Edit' : $_REQUEST['gos'];
$gos($md, $id);

// *** Functions ***
function arrayPrasyaratWisuda($w) {
  $def = explode(',', $w['Prasyarat']);
  $hsl = array();
  $s = "select PrasyaratID, Nama
    from wisudaprasyarat
    where KodeID = '".KodeID."' and NA='N'
    order by PrasyaratID"; 
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    $ada = array_search($w['PrasyaratID'], $def);
    $ck = ($ada === false)? '' : 'checked';
    $hsl[] = "<input type=checkbox name='$w[PrasyaratID]' value='Y' $ck /> $w[PrasyaratID] &#8594; $w[Nama]";
  }
  $_hsl = implode("<br />", $hsl);
  return $_hsl;
}

function Edit($md, $id) {
  if ($md == 0) {
    $jdl = "Edit Wisudawan";
    $w = GetFields('wisudawan', 'WisudawanID', $id, '*');
    $w['Nama'] = GetaField('mhsw', "KodeID='".KodeID."' and MhswID", $w['MhswID'], 'Nama');
    $prs = arrayPrasyaratWisuda($w);
    $ro = "readonly=true";
    $btn = "";
  }
  elseif ($md == 1) {
    $jdl = "Tambah Wisudawan";
    $w = array();
    $w['PrasyaratLengkap'] = 'N';
    $prs = arrayPrasyaratWisuda($w);
    $ro = '';
    $btn = "&raquo;
        <a href='#'
          onClick=\"javascript:CariMhsw('$_SESSION[ProdiID]', 'frmWisuda')\" />Cari...</a> |
        <a href='#' onClick=\"javascript:frmWisuda.MhswID.value='';frmWisuda.NamaMhsw.value=''\">Reset</a>";
  }
  else die(ErrorMsg('Error',
    "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick='window.close()' />"));
  // tampilkan
  TampilkanJudul($jdl);
  $Lengkap = ($w['PrasyaratLengkap'] == 'Y')? 'checked' : '';
  $optpred = GetOption2('predikat', "concat(Nama, ' (', IPKMin, '-', IPKMax, ')')",
    'IPKMin', $w['Predikat'], "KodeID='".KodeID."'", 'Nama');
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmWisuda' action='../$_SESSION[mnux].wan.php' method=POST>
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='id' value='$id' />
  <input type=hidden name='TahunID' value='$_SESSION[TahunID]' />
  <input type=hidden name='ProdiID' value='$_SESSION[ProdiID]' />
  <input type=hidden name='gos' value='Simpan' />
  
  <tr><td class=inp>Mahasiswa:</td>
      <td class=ul>
      <input type=text name='MhswID' value='$w[MhswID]' size=10 maxlength=30 $ro />
        <input type=text name='NamaMhsw' value='$w[Nama]' size=30 maxlength=50 $ro
          onKeyUp="javascript:CariMhsw('$_SESSION[ProdiID]', 'frmWisuda')"/>
        $btn
      </td></tr>
  <tr><td class=inp>Judul Skripsi/<br />Tugas Akhir:</td>
      <td class=ul><textarea name='Judul' cols=60 rows=2>$w[Judul]</textarea></td></tr>
  
  <tr><td class=inp>Prasyarat:</td>
      <td class=ul>
      $prs
      </td></tr>
  <tr><td class=ul colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal' onClick='window.close()' />
      </td></tr>
  </form>
  </table>
  
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
  function CariMhsw(ProdiID, frm) {
    if (eval(frm + ".NamaMhsw.value != ''")) {
      eval(frm + ".NamaMhsw.focus()");
      showMhsw(ProdiID, frm, eval(frm +".NamaMhsw.value"), 'carimhsw');
      toggleBox('carimhsw', 1);
    }
  }
  //-->
  </script>
ESD;
}
function CekPrasyarat() {
  $hsl = array();
  $s = "select PrasyaratID, Nama
    from wisudaprasyarat
    where KodeID='".KodeID."' and NA='N'
    order by PrasyaratID";
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    if ($_REQUEST[$w['PrasyaratID']] == 'Y') $hsl[] = $w['PrasyaratID'];
  }
  $_hsl = implode(',', $hsl);
  return $_hsl;
}
function CekPrasyaratlengkap() {
  $hsl = 0;
  $s = "select PrasyaratID, Nama
    from wisudaprasyarat
    where KodeID='".KodeID."' and NA='N'
    order by PrasyaratID";
  $r = _query($s);
  $n=0;
  while ($w = _fetch_array($r)) {
    $n++;
    if ($_REQUEST[$w['PrasyaratID']] == 'Y') $hsl++;
  }
  $ck = ($n==$hsl)? 'Y' : 'N';
  return $ck;
}
function Simpan($md, $id) {
  $Judul = sqling($_REQUEST['Judul']);
  $Predikat = sqling($_REQUEST['Predikat']);
  $QryPrasyarat = CekPrasyarat();  
  $_QryPrasyarat = (empty($QryPrasyarat))? '' : ", Prasyarat = '$QryPrasyarat' ";
  $PrasyaratLengkap = CekPrasyaratlengkap();
  
  if ($md == 0) {
    $s = "update wisudawan
      set PrasyaratLengkap = '$PrasyaratLengkap',
          Judul = '$Judul',
          LoginEdit = '$_SESSION[_Login]', TanggalEdit = now()
          $_QryPrasyarat
      where WisudawanID = '$id' ";
    $r = _query($s);
    TutupScript();
  }
  elseif ($md == 1) {
    $MhswID = sqling($_REQUEST['MhswID']);
    $ada = GetFields("wisudawan", "KodeID='".KodeID."' and MhswID", $MhswID, '*');
    if (empty($ada)) {
      $gel = GetFields('wisuda', "KodeID='".KodeID."' and TahunID", $_SESSION['TahunID'], '*');
      $s = "insert into wisudawan
        (KodeID, TahunID, WisudaID, 
        MhswID, Judul, PrasyaratLengkap, Prasyarat,
        LoginBuat, TanggalBuat)
        values
        ('".KodeID."', '$_SESSION[TahunID]', '$gel[WisudaID]', 
        '$MhswID', '$Judul', '$PrasyaratLengkap', '$QryPrasyarat',
        '$_SESSION[_Login]', now())";
      $r = _query($s);
      
      // Update Tanggal Lulus dan IPK Mahasiswa bersangkutan
      $maxSesi = GetaField('khs', "MhswID='$MhswID' and KodeID", KodeID, 'max(Sesi)+0');
      $IPTerakhir = GetaField('khs', "MhswID='$MhswID' and Sesi='$maxSesi' and KodeID", KodeID, "IP");
      $TAIDTerakhir = GetaField('ta', "MhswID='$MhswID' and KodeID", KodeID, 'max(TAID)+0');
      $TanggalLulus = GetaField('ta', "TAID='$TAIDTerakhir' and KodeID", KodeID, 'TglUjian');
      $s = "update mhsw set IPK='$IPTerakhir', TanggalLulus='$TanggalLulus' where MhswID='$MhswID'";
      $r = _query($s);
       
      TutupScript();     
    }
    else die(ErrorMsg('Error',
      "Mahasiswa dengan NIM: <b>$MhswID</b> telah pernah terdaftar di wisuda.<br />
      Berikut adalah datanya:<br />
      NIM: <b>$ada[MhswID]</b><br />
      Gelombang: <b>$ada[TahunID]</b>
      <hr size=1 color=silver />
      <input type=button name='Tutup' value='Tutup' onClick='window.close()' />"));
  }
  else die(ErrorMsg('Error',
    "Mode edit <b>$md</b> tidak dikenali.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick='window.close()' />"));
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]&_tabWisuda=$_SESSION[_tabWisuda]&gos=Daftar';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
?>
