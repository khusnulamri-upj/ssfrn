<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 04 Sept 2008

// *** Parameters ***
$TahunID = GetSetVar('TahunID');
$FilterMhswID = GetSetVar('FilterMhswID');
$FilterNamaMhsw = GetSetVar('FilterNamaMhsw');
$FilterProdiID = GetSetVar('FilterProdiID');


// *** Main ***
TampilkanJudul("Daftar Mhsw Skripsi/Tugas Akhir");
TampilkanFilter();
$gos = (empty($_REQUEST['gos']))? 'DftrMhswTA' : $_REQUEST['gos'];
$gos();

// *** Functions ***
function TampilkanFilter() {
  $optprodi = GetProdiUser($_SESSION['_Login'], $_SESSION['FilterProdiID']);
  echo "<table class=box cellspacing=1 align=center width=940>
  <form name='frmFilterTA' action='?' method=POST>
  <input type=hidden name='gos' value='' />
  <input type=hidden name='tapage' value='1' />
  <tr>
      <td class=inp>Tahun Akd:</td>
      <td class=ul><input type=text name='TahunID' value='$_SESSION[TahunID]' size=5 maxlength=5 /></td>
      <td class=inp>Filter Prodi:</td>
      <td class=ul><select name='FilterProdiID' onChange='this.form.submit()'>$optprodi</select></td>
      </tr>
  <tr><td class=inp>Cari NIM:</td>
      <td class=ul><input type=text name='FilterMhswID' value='$_SESSION[FilterMhswID]' size=20 maxlength=20 /></td>
      <td class=inp>Cari Nama:</td>
      <td class=ul><input type=text name='FilterNamaMhsw' value='$_SESSION[FilterNamaMhsw]' size=20 maxlength=20 /></td>
      </tr>
  <tr>
      <td class=ul colspan=4 align=center>
        <input type=submit name='Cari' value='Cari Data' />
        <input type=button name='ResetFilter' value='Reset Filter'
          onClick=\"location='?mnux=$_SESSION[mnux]&gos=&TahunID=&FilterProdiID=&FilterMhswID=&FilterNamaMhsw='\" />
        &#9655;&#9654;
        <input type=button name='DaftarkanMhswTA' value='Daftarkan Skripsi/TA Mhsw'
          onClick=\"javascript:TAEdit(1,0)\" />
        <input type=button name='CetakDaftarTA' value='Cetak Daftar'
          onClick=\"javascript:CetakTA()\" />
      </td>
      </tr>
  </form>
  </table>";
  RandomStringScript();
echo <<<SCR
  <script>
  <!--
  function TAEdit(md,id) {
    if (frmFilterTA.FilterProdiID.value == '') alert("Pilihan Program Studi terlebih dahulu");
    else {
      _rnd = randomString();
      lnk = "$_SESSION[mnux].edit.php?md="+md+"&TAID="+id+"&ProdiID="+frmFilterTA.FilterProdiID.value+"&_rnd="+_rnd;
      win2 = window.open(lnk, "", "width=700, height=500, scrollbars, status");
      if (win2.opener == null) childWindow.opener = self;
    }
  }
  function TAUjian(id) {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].ujian.php?TAID="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function CetakTA() {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].cetak.php?TahunID=$_SESSION[TahunID]&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function EditBimbingan(id) {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].bimbingan.php?_rnd="+_rnd+"&TAID="+id;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }function EditPembimbing(id) {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].pembimbing.php?_rnd="+_rnd+"&TAID="+id;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function fnKelulusan(id) {
    _rnd = randomString();
    lnk = "$_SESSION[mnux].lulus.php?_rnd="+_rnd+"&TAID="+id;
    win2 = window.open(lnk, "", "width=700, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  //-->
  </script>
SCR;
}
function TampilkanFotoScript() {
  echo <<<SCR
  <script>
  function TampilkanFoto(MhswID, Nama, Foto) {
    jQuery.facebox("<font size=+1>"+Nama+"</font> <sup>(" + MhswID + ")</sup><hr size=1 color=silver /><img src='"+Foto+"' />");
  }
  </script>
SCR;
}
function DftrMhswTA() {
  TampilkanFotoScript();
  // setup where-statement
  $whr_prodi = (empty($_SESSION['FilterProdiID']))? '' : "and m.ProdiID='$_SESSION[FilterProdiID]'";
  $whr_nama = (empty($_SESSION['FilterNamaMhsw']))? '' : "and m.Nama like '$_SESSION[FilterNamaMhsw]%'";
  $whr_nim  = (empty($_SESSION['FilterMhswID']))?   '' : "and m.MhswID like '$_SESSION[FilterMhswID]%'";
  $whr_tahun = (empty($_SESSION['TahunID']))? '' : "and t.TahunID = '$_SESSION[TahunID]' ";
  // Tampilkan
  $tapage = GetSetVar('tapage', 1);
  include_once "class/dwolister.class.php";
  $lst = new dwolister;
  $lst->maxrow = 10;
  $lst->page = $_SESSION['tapage']+0;
  $lst->pageactive = "=PAGE=";
  $lst->pages = "<a href='?mnux=$_SESSION[mnux]&tapage==PAGE='>=PAGE=</a>";
  $lst->tables = "ta t
    left outer join mhsw m on t.MhswID = m.MhswID and m.KodeID = '".KodeID."'
    left outer join dosen d on d.Login = t.Pembimbing and d.KodeID = '".KodeID."'
    left outer join dosen d1 on d1.Login = t.Penguji and d1.KodeID = '".KodeID."'
    left outer join prodi prd on prd.ProdiID = m.ProdiID
    where t.NA = 'N'
    $whr_prodi
    $whr_nama
    $whr_nim
    $whr_tahun
    ";
  $lst->fields = "t.*, m.Nama as NamaMhsw,
    date_format(TglMulai, '%d-%m-%Y') as _TglMulai,
    date_format(TglSelesai, '%d-%m-%Y') as _TglSelesai,
    date_format(TglUjian, '%d-%m-%Y') as _TglUjian,
    m.PenasehatAkademik, 
    d.Nama as NamaDosen, d.Gelar,
    d1.Nama as NamaPenguji, d1.Gelar as GelarPenguji,
    
    replace((select group_concat(concat('&rsaquo; ', td_d.Nama, ' <sup>', td_d.Gelar, '</sup>')) 
    from tadosen td
      left outer join dosen td_d on td_d.Login = td.DosenID and td_d.KodeID = '".KodeID."' 
    where td.TAID = t.TAID
      and td.Tipe=0), ',', '<br />') as _DP,
    
	(select count(tb.TAID) from tabimbingan tb where tb.TAID = t.TAID) as Bimbingan,
	
    replace((select group_concat(concat('&rsaquo; ', td_d.Nama, ' <sup>', td_d.Gelar, '</sup>')) 
    from tadosen td
      left outer join dosen td_d on td_d.Login = td.DosenID and td_d.KodeID = '".KodeID."' 
    where td.TAID = t.TAID
      and td.Tipe=1), ',', '<br />') as _DU
    ";
  $lst->headerfmt = "<table class=box cellspacing=1 cellpadding=4 width=940>
    <tr><th class=ttl width=10>Edit</th>
        <th class=ttl width=80>NPM</th>
        <th class=ttl>Nama</th>
        <th class=ttl>Judul</th>
        <th class=ttl width=70>Tgl Mulai<hr size=1 color=white />Selesai</th>
        <th class=ttl width=180>Pembimbing</th>
        <th class=ttl width=70>Bimbingan</th>
		<th class=ttl width=180>Ujian Akhir<hr size=1 color=white />Penguji</th>
        <th class=ttl width=10>Lulus</th>
        </tr>";
  $lst->footerfmt = "</table></p>";
  $lst->detailfmt = "<tr>
    <td class=cna=Lulus= align=center>
      <a href='#' onClick=\"javascript:TAEdit(0,=TAID=)\"><img src='img/edit.png' title='Edit Data TA' /></a>
      </td>
    <td class=cna=Lulus= align=center>
      =MhswID=
      <hr size=1 color=silver />
      <sup>=TahunID=</sup>
      </td>
    <td class=cna=Lulus=>=NamaMhsw=</td>
    <td class=cna=Lulus=>=Judul=</td>
    <td class=cna=Lulus= align=center>
      <sup>=_TglMulai=
      <hr size=1 color=silver />
      =_TglSelesai=</sup>
      </td>
	<td class=cna=Lulus=>
      &bull; =NamaDosen= <sup>=Gelar=</sup><br />
      =_DP=
      <div align=right>
      <a href='#' onClick=\"javscript:EditPembimbing(=TAID=)\" title='Edit Dosen Pembimbing'><img src='img/edit.png' /></a>
      </div>
      </td>
    <td class=cna=Lulus= align=center>
	  =Bimbingan=&times;
	  <div align=right>
      <a href='#' onClick=\"javscript:EditBimbingan(=TAID=)\" title='Edit Bimbingan'><img src='img/edit.png' /></a>
	  </td>
    <td class=cna=Lulus= align=center>
      <sup>=_TglUjian=</sup>
      <hr size=1 color=silver />
      <div align=left>
      &bull; =NamaPenguji= <sup>=GelarPenguji=</sup><br />
      =_DU=
      </div>
      <div align=right>
      <a href='#' onClick=\"javascript:TAUjian(=TAID=)\" title='Edit Dosen Penguji'><img src='img/edit.png' /></a>
      </div>
      </td>
    <td class=cna=Lulus= align=center>
      <a href='#' onClick=\"javascript:fnKelulusan(=TAID=)\"><img src='img/=Lulus=.gif' /></a>
      </td>
    </tr>
    <tr><td bgcolor=silver colspan=9 height=1></td></tr>";
  echo $lst->TampilkanData();
  echo $ttl;
  echo "<p>Hal.: ". $lst->TampilkanHalaman() . "<br />".
    "Total: " . number_format($lst->MaxRowCount). "</p>";
}
?>
