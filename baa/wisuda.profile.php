<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 23 September 2008

session_start();
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Profile Wisudawan", 1);

// *** infrastruktur **
echo <<<SCR
  <script src="../$_SESSION[mnux].profile.script.js"></script>
SCR;

// *** Parameters ***
$MhswID = sqling($_REQUEST['MhswID']);
$mhsw = GetFields('mhsw', "KodeID='".KodeID."' and MhswID", $MhswID, '*');
if (empty($mhsw)) 
  die(ErrorMsg('Error',
    "Mahasiswa dengan NIM: <b>$MhswID</b> tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup' onClick='window.close()' />"));

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'Profile' : $_REQUEST['gos'];
$gos($MhswID, $mhsw);

// *** Functions ***
function Profile($MhswID, $mhsw) {
  TampilkanJudul("Edit Profile Wisudawan");
  $tgllahir = GetDateOption($mhsw['TanggalLahir'], 'TGL');
  $optagm = GetOption2('agama', "concat(Agama, ' - ', Nama)", 'Agama', $mhsw['Agama'], '', 'Agama');
  $NamaDosen = (empty($mhsw['PenasehatAkademik']))? '' : GetaField('dosen', "KodeID='".KodeID."' and Login", $mhsw['PenasehatAkademik'], 'Nama');
  CheckFormScript('MhswID,Agama,TempatLahir,DosenID,Kota,NamaAyah');
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100%>
  
  <form name='frmp' action='../$_SESSION[mnux].profile.php' method=POST 
    onSubmit="return CheckForm(this)">
  <input type=hidden name='MhswID' value='$MhswID' />
  <input type=hidden name='gos' value='Simpan' />
  
  <tr><td class=inp>NIM:</td>
      <td class=ul><b>$MhswID</b></td>
      </tr>
  <tr><td class=inp>Nama Mahasiswa:</td>
      <td class=ul><b>$mhsw[Nama]</b></td>
      </tr>
  <tr><td class=inp>Tanggal Lahir:</td>
      <td class=ul>$tgllahir</td>
      </tr>
  <tr><td class=inp>Tempat Lahir:</td>
      <td class=ul>
      <input type=text name='TempatLahir' value='$mhsw[TempatLahir]'
        size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Agama:</td>
      <td class=ul><select name='Agama'>$optagm</select></td>
      </tr>
  <tr><td class=inp>Alamat:</td>
      <td class=ul>
      <input type=text name='Alamat' value='$mhsw[Alamat]'
        size=40 maxlength=100 />
      </td></tr>
  <tr><td class=inp>Kota/Kabupaten:</td>
      <td class=ul>
      <input type=text name='Kota' value='$mhsw[Kota]'
        size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Nama Ayah:</td>
      <td class=ul>
      <input type=text name='NamaAyah' value='$mhsw[NamaAyah]'
        size=40 maxlength=50 />
      </td></tr>
  <tr><td class=inp>Penasehat Akademik:</td>
      <td class=ul>
      <input type=text name='DosenID' value='$mhsw[PenasehatAkademik]' size=10 maxlength=50 />
      <input type=text name='Dosen' value='$NamaDosen' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$mhsw[ProdiID]', 'frmp')" />
      <div style='text-align:right'>
      &raquo;
      <a href='#' onClick="javascript:CariDosen('$mhsw[ProdiID]', 'frmp')" />Cari...</a> |
      <a href='#' onClick="javascript:frmp.DosenID.value='';frmp.Dosen.value=''">Reset</a>
      </div>
      </td></tr>
  
  <tr><td class=ul colspan=2 align=center>
      <input type=submit name='Simpan' value='Simpan' />
      <input type=button name='Batal' value='Batal' onClick='window.close()' />
      </td></tr>
  </form>
  </table>
  
  <div class='box0' id='caridosen'></div>
  
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
  function CariDosen(ProdiID, frm) {
    //alert(document.getElementByName(frm));
    if (eval(frm + ".Dosen.value != ''")) {
      eval(frm + ".Dosen.focus()");
      showDosen(ProdiID, frm, eval(frm +".Dosen.value"), 'caridosen');
      toggleBox('caridosen', 1);
    }
  }
  //-->
  </script>
ESD;
}
function Simpan($MhswID, $mhsw) {
  $TanggalLahir = "$_REQUEST[TGL_y]-$_REQUEST[TGL_m]-$_REQUEST[TGL_d]";
  $TempatLahir = sqling($_REQUEST['TempatLahir']);
  $Agama = sqling($_REQUEST['Agama']);
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $NamaAyah = sqling($_REQUEST['NamaAyah']);
  $DosenID = sqling($_REQUEST['DosenID']);
  
  // Simpan
  $s = "update mhsw
    set TanggalLahir = '$TanggalLahir',
        TempatLahir = '$TempatLahir',
        Agama = '$Agama',
        Alamat = '$Alamat',
        Kota = '$Kota',
        NamaAyah = '$NamaAyah',
        PenasehatAkademik = '$DosenID'
    where KodeID = '".KodeID."'
      and MhswID = '$MhswID' ";
  $r = _query($s);
  TutupScript();
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

</BODY>
</HTML>
