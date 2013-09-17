<?php
// Proses Login
// Author: Emanuel Setio Dewo
// 13 Desember 2005

// *** Main ***
//die($_REQUEST['gos']);
$gos = (empty($_REQUEST['gos']))? 'cek' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function gagal() {
   echo $_err = ErrorMsg("Login Gagal", 
     "Login dan Password yang Anda masukkan tidak valid.<br>
     Hubungi Administrator untuk informasi lebih lanjut.<hr size=1 color=black>
     Pilihan: <a href='?nme=$_REQUEST[nme]&mnux=login&lid=$_REQUEST[lid]&lgn=frm'>Login</a> | <a href='?mnux='>Kembali</a>");
}
function berhasil() {
  global $_ProductName, $_Version, $arrID;
// Tampilkan welcome
  TampilkanJudul("Selamat Datang");
  echo Konfirmasi("$arrID[Nama]", 
    "<table class=bsc cellspacing=1 width=100%>
    <tr>
    <td class=ul1 colspan=2 nowrap>Selamat datang di $_ProductName - $arrID[Nama]</td>
    </tr>
    <tr>
      <td class=inp>Nama :</td>
      <td class=ul1><b>$_SESSION[_Nama]</b></td>
    </tr>
    <tr>
      <td class=inp>LevelID :</td>
      <td class=ul1><b>$_SESSION[_LevelID]</b></td>
    </tr>
    <tr>
      <td class=inp>Institusi :</td>
      <td class=ul1><b>$_SESSION[_KodeID]</b></td>
    </tr>
    <tr>
      <td class=inp>Session :</td>
      <td class=ul1>$_SESSION[_Session]</td>
      </tr>
    <tr>
      <td class=inp>Pilihan:</td>
      <td class=ul1>
      <input type=button name='Logout' value='Logout' onClick=\"location='?mnux=loginprc&gos=lout'\" />
      </td>
    </tr>
    </table>");
}
function cek() {
  global $arrID;
  //RMK KHUSNUL 20130911
  //$_tbl = GetaField('level', 'LevelID', $_REQUEST['lid'], 'TabelUser');
  
  $Institusi = $_REQUEST['institusi'];
  $loginVal = sqling($_REQUEST[Login]);  
  $passVal = str_replace("\\", "x", $_REQUEST[Password]);
  $passVal2 = sqling($passVal);  
  
  //$s = "update agama set NamaKU = '$passVal'";
  //$r = _query($s);
  
  //RMK KHUSNUL 20130911
  /*$s = "select * from $_tbl 
    where Login='$loginVal'
      and LevelID = '$_REQUEST[lid]' 
      and KodeID = '".KodeID."' 
      and NA = 'N'
      and Password=LEFT(PASSWORD('$passVal2'),10) limit 1";
  
  $r = _query($s);
  $_dat = _fetch_array($r);
  */
  
  //ADD KHUSNUL 20130911
  $s = "SELECT TabelUser FROM level WHERE NA = 'N' GROUP BY TabelUser ORDER BY TabelUser DESC";
        
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    $ss = "select * from ".$w['TabelUser']." 
    where Login='$loginVal'
      and KodeID = '".KodeID."' 
      and NA = 'N'
      and Password=LEFT(PASSWORD('$passVal2'),10) limit 1";
  
    $rr = _query($ss);
    $_dat = _fetch_array($rr);
    if (!empty($_dat)) {
        break;
    }
  }
  //ADD KHUSNUL 20130911 --END
  
  if (empty($_dat)) {
    gagal();
  } else {
    $sid = session_id();
    // Set Parameter
    $_SESSION['_Login'] = $_REQUEST['Login'];
    $_SESSION['_Nama'] = $_dat['Nama'];
    $_SESSION['_TabelUser'] = $_tbl;
    //CHG KHUSNUL 20130911
    //$_SESSION['_LevelID'] = $_REQUEST['lid'];
    //$_SESSION['LevelID'] = $_REQUEST['lid'];
    $_SESSION['_LevelID'] = $_dat['LevelID'];
    $_SESSION['LevelID'] = $_dat['LevelID'];
    //CHG KHUSNUL 20130911 --END
    $_SESSION['_Session'] = $sid;
    $_SESSION['_Superuser'] = $_dat['Superuser'];
    $_SESSION['_ProdiID'] = $_dat['ProdiID'];
    $_SESSION['KodeID'] = $Institusi;
    $_SESSION['_KodeID'] = $Institusi;
    $_SESSION['mnux'] = 'login';
    $_REQUEST['lgn'] = 'berhasil';
    // Refresh
    echo "<script>window.location='?mnux=loginprc&gos=berhasil';</script>";
  }  
}
function lout() {
  ResetLogin();
  echo "<script>window.location='?mnux=';</script>";
}
?>
