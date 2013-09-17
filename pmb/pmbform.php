<?php
// Author : Emanuel Setio Dewo
// Start  : 5 Agustus 2008
// Email  : setio.dewo@gmail.com

// *** Parameters ***
$gelombang = GetaField('pmbperiod', "KodeID='".KodeID."' and NA", 'N', "PMBPeriodID");
$_pmbNama = GetSetVar('_pmbNama');
$_pmbFrmID = GetSetVar('_pmbFrmID');
$_pmbPrg = GetSetVar('_pmbPrg');
$_pmbNomer = GetSetVar('_pmbNomer');
$_pmbPage = GetSetVar('_pmbPage');
$_pmbUrut = GetSetVar('_pmbUrut', 0);
$arrUrut = array('Nomer PMB~p.PMBID asc, p.Nama', 'Nomer PMB (balik)~p.PMBID desc, p.Nama', 'Nama~p.Nama');
RandomStringScript();

// *** Main ***
TampilkanJudul("Pengisian Formulir - $gelombang");
if (empty($gelombang)) {
  echo ErrorMsg("Error",
    "Tidak ada gelombang PMB yang aktif.<br />
    Hubungi Kepala PMB untuk mengaktifkan gelombang.");
}
else {
  $gos = (empty($_REQUEST['gos']))? 'DftrForm' : $_REQUEST['gos'];
  $gos($gelombang);
}

// *** Functions ***
function IsiFormulirScript($gel) {
  echo <<<SCR
  <script>
  function IsiFormulir(MD,GEL,ID) {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].isi.php?md="+MD+"&gel="+GEL+"&id="+ID+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=800, height=700, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
function CetakKartuScript() {
  echo <<<SCR
  <script>
  function CetakKartu(ID) {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].kartutest.php?id="+ID+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
    window.location = "?mnux=$_SESSION[mnux]";
  }
  function PilihKursi(ID, gel)
  {	_rnd = randomString();
    lnk = "$_SESSION[mnux].pilihkursi.php?id="+ID+"&_rnd="+_rnd+"&gel="+gel;
    win2 = window.open(lnk, "", "width=800, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}

function GetUrutanPMB() {
  global $arrUrut;
  $a = ''; $i = 0;
  foreach ($arrUrut as $u) {
    $_u = explode('~', $u);
    $sel = ($i == $_SESSION['_pmbUrut'])? 'selected' : '';
    $a .= "<option value='$i' $sel>". $_u[0] ."</option>";
    $i++;
  }
  return $a;
}

function TampilkanHeader($gel) {
  IsiFormulirScript($gel);
  CetakKartuScript();
  $optfrm = GetOption2('pmbformulir', 'Nama', 'Nama', $_SESSION['_pmbFrmID'],
    "KodeID='".KodeID."'", 'PMBFormulirID');
  $optprg = GetOption2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_pmbPrg'], "KodeID='".KodeID."'", 'ProgramID');
  $opturut = GetUrutanPMB();
  if($_SESSION['_LevelID'] != 33)
  {	$AmbilLalu = "<input type=button name='btnAmbilPMBLalu' value='Ambil Dari Periode Lalu'
        onClick=\"javascript:AmbilPMBLalu('$gel')\" />"; 
  
  echo "<table class=box cellspacing=1 align=center>
  <form action='?' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='gos' value='' />
  <input type=hidden name='_pmbPage' value='0' />
  
  <tr>
      <td class=inp>Cari Nama:</td>
      <td class=ul1><input type=text name='_pmbNama' value='$_SESSION[_pmbNama]' size=20 maxlength=30 /></td>
      <td class=inp width=100>Filter Formulir:</td>
      <td class=ul1>
        <select name='_pmbFrmID'>$optfrm</select>
      </td>
      </tr>
  <tr>
      <td class=inp>Cari No. Formulir:</td>
      <td class=ul1><input type=text name='_pmbNomer' value='$_SESSION[_pmbNomer]' size=20 maxlength=30 /></td>
      <td class=inp>Urutkan:</td>
      <td class=ul1><select name='_pmbUrut'>$opturut</select></td>
      </tr>
  <tr>
      <td class=inp>Program:</td>
      <td class=ul1><select name='_pmbPrg'>$optprg</select></td>
      <td class=ul1 colspan=2 align=center nowrap>
      <input type=submit name='Submit' value='Submit' />
      <input type=button name='Reset' value='Reset'
        onClick=\"location='?mnux=$_SESSION[mnux]&gos=&_pmbPage=0&_pmbNama=&_pmbNomer='\" />
      &raquo&raquo<input type=button name='IsiFrm' value='Isi Formulir' onClick=\"javascript:IsiFormulir(1,'$gel','')\" />&laquo&laquo
      $AmbilLalu
      </td>
  </form>
  </table>";
  }
  // Javascript
  echo <<<ESD
  <script>
  function AmbilPMBLalu(gel) {
    lnk = "$_SESSION[mnux].lalu.php?gel="+gel;
    win2 = window.open(lnk, "", "width=820, height=500, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}

function DftrForm($gel) {
  TampilkanHeader($gel);
  
  global $arrUrut;
  $_maxbaris = 10;
  include_once "class/dwolister.class.php";
  // Urutan
  
  if($_SESSION['_LevelID'] != 33)
  {
	  $_urut = $arrUrut[$_SESSION['_pmbUrut']];
	  $__urut = explode('~', $_urut);
	  $urut = "order by ".$__urut[1];
	  // Filter formulir
	  $whr = array();
	  if (!empty($_SESSION['_pmbFrmID'])) $whr[] = "p.PMBFormulirID='$_SESSION[_pmbFrmID]'";
	  if (!empty($_SESSION['_pmbPrg']))   $whr[] = "p.ProgramID = '$_SESSION[_pmbPrg]' ";
	  if (!empty($_SESSION['_pmbNama']))  $whr[] = "p.Nama like '%$_SESSION[_pmbNama]%'";
	  if (!empty($_SESSION['_pmbNomer'])) $whr[] = "p.PMBID like '%$_SESSION[_pmbNomer]%'";
	  
	  $_whr = implode(' and ', $whr);
	  $_whr = (empty($_whr))? '' : 'and '.$_whr;
  }
  else
  {	$_whr = "and p.PMBID = $_SESSION[_Login]";
  }
  $pagefmt = "<a href='?mnux=$_SESSION[mnux]&gos=&_pmbPage==PAGE='>=PAGE=</a>";
  $pageoff = "<b>=PAGE=</b>";

  $brs = "<hr size=1 color=silver />";
  $gantibrs = "<tr><td bgcolor=silver height=1 colspan=12></td></tr>";
  $lst = new dwolister;
  $lst->tables = "pmb p 
    left outer join pmbformulir f on p.PMBFormulirID = f.PMBFormulirID
    left outer join prodi _p1 on p.Pilihan1 = _p1.ProdiID
    left outer join prodi _p2 on p.Pilihan2 = _p2.ProdiID
    left outer join prodi _p3 on p.Pilihan3 = _p3.ProdiID
    left outer join program _prg on p.ProgramID = _prg.ProgramID
    left outer join statusawal _sta on p.StatusAwalID = _sta.StatusAwalID
    where p.KodeID = '".KodeID."' 
      and p.PMBPeriodID='$gel'
      $_whr
      $urut";
  $lst->fields = "p.PMBID, p.Nama, p.Kelamin, p.ProdiID, p.Pilihan1, p.Pilihan2, p.Pilihan3, p.Foto,
    f.Nama as FRM, _p1.Nama as P1, 
	if(f.JumlahPilihan <= 2, _p2.Nama, '-') as P2, 
	if(f.JumlahPilihan <= 2, p.NA, 'Y') as NA2,
	if(f.JumlahPilihan <= 3, _p3.Nama, '-') as P3,
	if(f.JumlahPilihan <= 3, p.NA, 'Y') as NA3,
	if(p.StatusAwalID='S', concat('<font color=blue>',_sta.Nama,'<font>') , _sta.Nama) as STA,
    _prg.Nama as PRG, p.CetakKartu, p.NA,
	if(f.Wawancara = 'Y' and f.USM = 'Y',
		(
		if (EXISTS(select ru.RuangUSMID from ruangusm ru where ru.PMBID=p.PMBID and KodeID='".KodeID."')
			and (EXISTS(select w.WawancaraUSMID from wawancara w where w.PMBID=p.PMBID and PMBPeriodID='$gel' and KodeID='".KodeID."')),
			'kursiN', 'kursiY')
		),
		(
			if(f.Wawancara = 'N' and f.USM = 'Y',
			(
			if (EXISTS(select ru.RuangUSMID from ruangusm ru where ru.PMBID=p.PMBID and KodeID='".KodeID."'),
				'kursiN', 'kursiY')
			),
			(
			if(f.Wawancara = 'Y' and f.USM = 'N',
				(
				if (EXISTS(select w.WawancaraUSMID from wawancara w where w.PMBID=p.PMBID and PMBPeriodID='$gel' and KodeID='".KodeID."'),
					'kursiN', 'kursiY')
				),'kursiN')
			))
		)) as _JenisKursi";
  //$lst->startrow = $_SESSION['_pmbPage']+0;
  $lst->maxrow = $_maxbaris;
  $lst->pages = $pagefmt;
  $lst->pageactive = $pageoff;
  $lst->page = $_SESSION['_pmbPage']+0;
  $lst->headerfmt = "<p><table class=box cellspacing=1 align=center width=1000>
    
    <tr>
    <th class=ttl colspan=2>#</th>
    <th class=ttl>PMB #</th>
    <th class=ttl colspan=2>Nama</th>
    <th class=ttl>Status</th>
    <th class=ttl>Formulir<hr size=1 color=silver />Program</th>
    <th class=ttl>Pilihan1</th>
    <th class=ttl>Pilihan2</th>
    <th class=ttl>Pilihan3</th>
    <th class=ttl>&nbsp;</th>
    <th class=ttl>Foto</th>
    </tr>";
  $lst->detailfmt = "<tr>
    <td class=inp width=10>=NOMER=</td>
    <td class=ul1 width=10>
      <a href='#' onClick=\"javascript:IsiFormulir(0,'$gel','=PMBID=')\" />
      <img src='img/edit.png' /></a></td>
    <td class=ul1 width=80>=PMBID=</td>
    <td class=cna=NA=>=Nama= <img src='img/=Kelamin=.bmp' /></td>
    <td class=cna=NA= width=10 align=center><a href='#' onClick=\"PilihKursi('=PMBID=', '$gel')\"><img src='img/=_JenisKursi=.jpg'></a></td>
    <td class=cna=NA= width=70>=STA=</td>
    <td class=cna=NA= width=120>
      =FRM=&nbsp;
      <hr size=1 color=silver />
      =PRG=&nbsp;
      </td>
    <td class=cna=NA= width=140>=P1=&nbsp;</td>
    <td class=cna=NA2= width=140>=P2=&nbsp;</td>
    <td class=cna=NA3= width=140>=P3=&nbsp;</td>
    <td class=ul1 width=10 align=center>
      <a href='#' onClick=\"javascript:CetakKartu('=PMBID=')\" /><img src='img/printer2.gif' /></a><br />
      <sup>=CetakKartu=&times;</sup></td>
    <td class=cna=NA3= width=80 align=center><a href='?mnux=$_SESSION[mnux]&gos=GantiFoto&PID==PMBID='>=Foto=<br>Upload Foto</a></td>
    </tr>".$gantibrs;
  $lst->footerfmt = "</table>";

  $hal = $lst->TampilkanHalaman($pagefmt, $pageoff);
  $ttl = $lst->MaxRowCount;
  echo $lst->TampilkanData();
  echo "<p align=center>Hal: $hal <br />(Tot: $ttl)</p>";
}

function GantiFoto() {
  $MaxFileSize = 500000;
  $MaxFileSize2 = number_format($MaxFileSize);
  $PID = $_REQUEST['PID'];
  $w = GetFields('pmb', 'PMBID', $PID, '*');
  echo <<<ESD
  <p><table class=box cellspacing=1 cellpadding=4>
  <form action='?' enctype='multipart/form-data' method=POST>
  <input type=hidden name='MAX_FILE_SIZE' value='$MaxFileSize' />
  <input type=hidden name='gos' value='SimpanFoto' />
  <input type=hidden name='PID' value='$PID' />
  <input type=hidden name='BypassMenu' value='1' />  
  
  <tr><td class=inp>Nama:</td>
      <td class=ul>$w[Nama]</td></tr>
  <tr><td class=inp>Nomer Test:</td>
      <td class=ul>$w[PMBID]</td></tr>
  
  <tr><td class=inp width=100>File Foto</td>
    <td class=ul><input type=file name='foto' size=35></td></tr>
  <tr><td class=ul colspan=2 align=center>
      File gambar foto yang bisa diupload hanya yang berformat <b>jpg/jpeg</b>.<br />
      Ukuran gambar maximal: <b>$MaxFileSize2</b> byte.
      </td></tr>
  <tr><td class=ul colspan=2 align=center>
    <input type=submit name='btnUpload' value='Upload File Foto' />
    <input type=button name='btnBatal' value='Batal' onClick="location='?mnux=$_SESSION[mnux]&gos='" />
    </td></tr>
  </form></table></p>
ESD;
}
function SimpanFoto() {
  $PID = $_REQUEST['PID'];  
  $MaxFileSize2 = number_format($_REQUEST['MAX_FILE_SIZE']);
  
  $upf = $_FILES['foto']['tmp_name'];
  $arrNama = explode('.', $_FILES['foto']['name']);
  $tipe = $_FILES['foto']['type'];
  $arrtipe = explode('/', $tipe);
  $extensi = $arrtipe[1];
  if (strtolower($extensi) != 'jpg' && strtolower($extensi) != 'jpeg')
    die(ErrorMsg("Error",
      "File foto yang bisa diupload hanya yang berformat jpg/jpeg. $extensi<br />
      Hubunti Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnKembali' value='Kembali' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\" />"));
  $dest = "foto/" . $PID . '.jpg';
  //echo $dest;
  if (move_uploaded_file($upf, $dest)) {
		$s = "update pmb set Foto='$dest' where PMBID='$PID' ";
    $r = _query($s);
    $_rand = rand();
    BerhasilSimpan("?mnux=$_SESSION[mnux]&gos=&_rnd=".$_rand, 1);
  }
  else echo ErrorMsg("Gagal Upload Foto",
    "Tidak dapat meng-upload file foto.<br />
    Periksa file yg di-upload, karena besar file dibatasi cuma: <b>$MaxFileSize2</b> byte.");
  //print_r($_FILES);
}

?>
