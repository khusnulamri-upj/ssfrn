<?php
// Input Presenter
include_once "presenter.hdr.php";
include_once "class/dwolister.class.php";

// *** Functions ***/

function CariPresenter() {
  TampilkanFilterPresenter('presenter', 1);
  DaftarPresenter('presenter', "gos=PresenterEdt&md=0&presenterid==Login=", "Login,Nama,Jabatan,Telephone,Alamat");
}

function PresenterAdd($mnux='presenter', $gos='PresenterAddSav', $sub='') {
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $w = GetFields('presenter', 'Login', $_REQUEST['presenterid'], '*');
    $jdl = "Edit Data Pribadi";
    $_strdsnid = "<input type=hidden name='presenterid' value='$w[Login]'><b>$w[Login]</b>";
  }
  else {
    $w = array();
    $w['Login'] = '';
    $w['Nama'] = '';
    $w['Jabatan'] = '';
    $w['Telephone'] = '';
    $w['Handphone'] = '';
    $w['Email'] = '';
    $w['TempatLahir'] = '';
    $w['TanggalLahir'] = date('Y-m-d');
    $w['KelaminID'] = 'P';
    $w['AgamaID'] = 'K';
    $w['NA'] = 'N';
    
    $jdl = "Tambah Presenter";
    $_strpresenterid = "<input type=text name='presenterid' size=20 maxlength=20>";
  }
  $_na = ($w['NA'] == 'Y')? 'checked' : '';
  $snm = session_name(); $sid = session_id();

  $TglLahir = GetDateOption($w['TanggalLahir'], 'TglLahir');
  $optagm = GetOption2('agama', "concat(Agama, ' - ', Nama)", 'Agama', $w['AgamaID'], '', 'Agama');
  $radkel = GetRadio("select Nama, Kelamin from kelamin order by Nama", "Kelamin", "Nama", "Kelamin", $w['KelaminID'], ", ");    
  
  echo "<p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='$mnux'>
  <input type=hidden name='gos' value='$gos'>
  <input type=hidden name='sub' value='$sub'>
  <input type=hidden name='md' value='$md'>
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp1>Login/Presenter ID</td><td class=ul>$_strpresenterid</td></tr>
  <tr><td class=inp1>Nama Presenter</td><td class=ul><input type=text name='Nama' value='$w[Nama]' size=40 maxlength=50></td></tr>
  <tr><td class=inp1>Jabatan</td><td class=ul><input type=text name='Gelar' value='$w[Gelar]' size=$0 maxlength=100></td></tr>
  <tr><td class=inp1>Tempat/Tanggal Lahir</td><td class=ul><input type=text name='TempatLahir' value='$w[TempatLahir]' size=30 maxlength=50>Tanggal: $TglLahir</td></tr>
  <tr><td class=inp1>Jenis Kelamin</td><td class=ul>$radkel</td></tr>
  <tr><td class=inp1>Agama</td><td class=ul><select name='AgamaID'>$optagm</select></td></tr>
  <tr><td class=inp1># Telepon</td><td class=ul><input type=text name='Telephone' value='$w[Telephone]' size=40 maxlength=50></td></tr>
  <tr><td class=inp1># Ponsel</td><td class=ul><input type=text name='Handphone' value='$w[Handphone]' size=40 maxlength=50></td></tr>
  <tr><td class=inp1>E-mail</td><td class=ul><input type=text name='Email' value='$w[Email]' size=40 maxlength=50></td></tr>
  <tr><td class=inp1>Tidak aktif?</td><td class=ul><input type=checkbox name='NA' value='Y' $_na></td></tr>
  <tr><td colspan=2><input type=submit name='Simpan' value='Simpan'>
    <input type=reset name='Reset' value='Reset'>
    <input type=button name='Batal' value='Batal' onClick=\"location='?mnux=dosen&gos=&$snm=$sid'\"></td></tr>
  </form></table></p>";
}

function SetPasswordPresenter($tgl) {
  $tmp = explode('-', $tgl);
  
  $tanggal = '';
  $bulan   = '';
  $tahun   = '';
  
  $tanggal = $tmp[2];
  $bulan   = $tmp[1];
  $tahun   = $tmp[0];
  
  $thn2digit = substr($tahun, -2);
  
  $pass = "$tanggal" . "$bulan" . "$thn2digit";
  
  return $pass;
}

function DsnEdtPribadi() {
  $_REQUEST['md']+0;
  PresenterAdd('presenter', 'PresenterEdtPribadiSav', 'PresenterEdtPribadi');
}
function DsnEdtPribadiSav() {
  PresenterAddSav('DsnEdt');
}

function PresenterAddSav($gos='PesenterEdt') {
  $md = $_REQUEST['md']+0;
  $Login = sqling($_REQUEST['dsnid']);
  $Nama = sqling($_REQUEST['Nama']);
  $TempatLahir = sqling($_REQUEST['TempatLahir']);
  $TanggalLahir = "$_REQUEST[TglLahir_y]-$_REQUEST[TglLahir_m]-$_REQUEST[TglLahir_d]";
  $Jabatan = sqling($_REQUEST['Gelar']);
  $Telephone = sqling($_REQUEST['Telephone']);
  $Handphone = sqling($_REQUEST['Handphone']);
  $Email = sqling($_REQUEST['Email']);
  $KelaminID = $_REQUEST['Kelamin'];
  $AgamaID = $_REQUEST['AgamaID'];
  
  $NA = (empty($_REQUEST['NA']))? 'N' : $_REQUEST['NA'];
  if ($md == 0) {
    $s = "update presenter set Nama='$Nama',
      TempatLahir='$TempatLahir', TanggalLahir='$TanggalLahir', 
      Jabatan='$Jabatan', Telephone='$Telephone', Handphone='$Handphone',
      KelaminID='$KelaminID', AgamaID='$AgamaID',
      Email='$Email', NA='$NA'
      where Login='$Login' ";
    $r = _query($s);
    $gos();
  }
  else {
    $ada = GetFields('presenter', "Login", $Login, '*');
    if (empty($ada)) {
      $pass = setPasswordPresenter($TanggalLahir);
      $s = "insert into presenter (Login, Nama, TempatLahir, TanggalLahir,
        AgamaID, KelaminID, Password,
        KodeID, Gelar, Telephone, Handphone,
        Email, NA)
        values ('$Login', '$Nama', '$TempatLahir', '$TanggalLahir',
        '$AgamaID', '$KelaminID', PASSWORD('$pass'), '$Homebase',
        '$_SESSION[KodeID]', '$Gelar', '$Telephone', '$Handphone',
        '$Email', '$NA')";
      $r = _query($s);
      $_SESSION['presenterid'] = $_REQUEST['presenterid'];
      $_SESSION['presentersub'] = "PresenterEdtPribadi";
      $_SESSION['prscr'] = $_REQUEST['prsid'];
      $_SESSION['prskeycr'] = "Login";
      echo "<script>window.location = '?mnux=dosen';</script>";
    }
    else echo ErrorMsg("Gagal", "Data Presenter tidak dapat disimpan karena Nomor Presenter: <b>$Login</b> sudah dipakai oleh:
      <b>$ada[Nama]</b>.<br>
      Gunakan Nomor Lain lain.<hr size=1 color=silver>
      Pilihan: <a href='?mnux=presenter&gos=PresenterAdd&md=1'>Tambah Dosen</a> |
      <a href='?mnux=dosen&gos='>Kembali ke Daftar Presenter</a>");
  }
}

// *** Edit Dosen ***
function TampilkanHeaderPresensiEdit($w) {
  global $arrID;
  echo "<p><table class=box cellspacing=1 cellpadding=4>
  <td class=ul colspan=2><b>$arrID[Nama]</b></td></tr>
  <tr><td class=inp1>Login/Presenter ID:</td><td class=ul><b>$w[Login]</b></td></tr>
  <tr><td class=inp1>Nama:</td><td class=ul><b>$w[Nama]</b>, $w[Gelar]</td></tr>
  <tr><td class=ul>Pilihan:</td><td class=ul><a href='?mnux=presenter&gos=&sub='>Kembali ke Daftar Presenter</a></td></tr>
  </table></p>";
}
function TampilkanMenuEditDosen($w) {
  $arrMenuPresenter = array(
			   'Data Pribadi->DsnEdt->DsnEdtPribadi'
   );
  
  echo "<p><table class=menu cellspacing=1 cellpadding=4><tr>";
  $_SESSION['prssub'] = (empty($_SESSION['prssub']))? 'PresenterEdtPribadi' : $_SESSION['prssub'];
  for ($i = 0; $i < sizeof($arrMenuPresenter); $i++) {
    $mn = explode('->', $arrMenuPresenter[$i]);
    $c = ($mn[2] == $_SESSION['prssub'])? 'class=menuaktif' : 'class=menuitem';
    echo "<td $c><a href='?mnux=presenter&gos=$mn[1]&presenterid=$w[Login]&prssub=$mn[2]'>$mn[0]</a></td>";
  }
  echo "</tr></table></p>";
}
function PresenterEdt() {
  $w = GetFields('presenter', "Login", $_SESSION['presenterid'], '*');
  TampilkanHeaderpresenterEdit($w);
  TampilkanMenuEditPresenter($w);
  if (!empty($_SESSION['prssub'])) $_SESSION['prssub']();
}


// *** Parameters ***
$prssub = GetSetVar('prssub');
$prsurt = GetSetVar('prsurt', 'Login');
$presenterid = GetSetVar('presenterid');
$prscr = GetSetVar('prscr');
$prskeycr = GetSetVar('prskeycr');
$prspage = GetSetVar('prspage');

if ($prskeycr == 'Reset') {
  $prscr = '';
  $_SESSION['prscr'] = '';
  $prskeycr = '';
  $_SESSION['prskeycr'] = '';
}
$gos = (empty($_REQUEST['gos']))? 'CariPresenter' : $_REQUEST['gos'];

// *** Main ***
TampilkanJudul("Master Presenter");
$gos();
?>