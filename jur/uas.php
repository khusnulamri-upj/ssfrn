<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 21 Agustus 2008

// *** Parameters ***
$_uasProdi = GetSetVar('_uasProdi');
$_uasProg  = GetSetVar('_uasProg');
$_uasTahun = GetSetVar('_uasTahun');

// *** Main ***
TampilkanJudul("Penjadwalan UAS");
TampilkanHeaderUAS();
RandomStringScript();
// validasi
if (!empty($_uasTahun) && !empty($_uasProdi)) {
  $gos = (empty($_REQUEST['gos']))? 'DftrUAS' : $_REQUEST['gos'];
  $gos();
}

// *** Functions ***
function TampilkanHeaderUAS() {
  $optprodi = GetProdiUser($_SESSION['_Login'], $_SESSION['_uasProdi']);
  $optprog  = GetOption2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $_SESSION['_uasProg'], "KodeID='".KodeID."'", 'ProgramID');
  if (!empty($_SESSION['_uasTahun']) && !empty($_SESSION['_uasProdi'])) {
    JdwlEdtScript();
    $btn1 = " |
      <input type=button name='HapusSemua' value='Hapus Semua Jadwal UAS' 
        onClick=\"javascript:JdwlDelAll('$_SESSION[_uasTahun]', '$_SESSION[_uasProdi]','$_SESSION[_uasProg]')\" />
     "; 
  }
  echo <<<SCR
  <table class=box cellspacing=1 align=center width=800>
  <form name='frmJadwalHeader' action='?' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='gos' value='' />
  
  <tr>
      <td class=inp>Tahun Akd:</td>
      <td class=ul1><input type=text name='_uasTahun' value='$_SESSION[_uasTahun]' size=5 maxlength=5 /></td>
      <td class=inp>Program:</td>
      <td class=ul1><select name='_uasProg'>$optprog</select></td>
      <td class=inp>Program Studi:</td>
      <td class=ul1 colspan=3><select name='_uasProdi'>$optprodi</select></td>
      </tr>
  <tr><td bgcolor=silver height=1 colspan=10></td></tr>
  <tr><td class=ul1 colspan=10 nowrap>
      <input type=submit name='btnKirim' value='Kirim Parameter' />
      <input type=button name='btnReset' value='Reset Parameter'
        onClick="location='?mnux=$_SESSION[mnux]&_uasHari=&_uasKelas=&_uasMKKode=&_uasSemester='" />
      $btn1</td></tr>
  <tr>
      <td class=ul1 colspan=10 nowrap>
      $btn2
      </td>
      </tr>
  </form>
  </table>
SCR;
}
function DftrUAS() {
  // Buat Header
  echo "<table class=box cellspacing=1 align=center width=800>";
  $hdr = "
  <tr><th class=ttl width=50>#</th>
      <th class=ttl>Matakuliah</th>
	  <th class=ttl width=50>Kelas</th>
      <th class=ttl width=20>Edit</th>
	  <th class=ttl width=75>Ujian</th>
      <th class=ttl width=75>Jam</th>
	  <th class=ttl width=40 title='Pembagian Kursi'>Kursi</th>
      <th class=ttl width=20 title='Hapus Jadwal'>Del</th>
      </tr>";

  $whr_prg = (empty($_SESSION['_uasProg']))? '' : "and j.ProgramID = '$_SESSION[_uasProg]'";
  
  $s = "select j.JadwalID, j.ProdiID, j.ProgramID, j.HariID,
      j.RuangID, j.MKKode, j.Nama, j.NamaKelas, j.DosenID, j.SKS,
      concat(d.Nama, ' <sup>', d.Gelar, '</sup>') as DSN,
      LEFT(j.JamMulai, 5) as _JM, LEFT(j.JamSelesai, 5) as _JS,
      h.Nama as HR, mk.Sesi, j.Final, 
      j.JumlahMhsw, j.Kapasitas, 
      j.BiayaKhusus, j.Biaya, format(j.Biaya, 0) as _Biaya
    from jadwal j
      left outer join hari h on j.HariID = h.HariID
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join mk mk on mk.MKID = j.MKID
	  left outer join jenisjadwal jj on jj.JenisJadwalID = j.JenisJadwalID
	where j.KodeID = '".KodeID."'
      and j.TahunID = '$_SESSION[_uasTahun]'
      and j.ProdiID = '$_SESSION[_uasProdi]'
      $whr_prg $whr_hr $whr_smt $whr_kls $whr_kd
      and j.NA = 'N'
	  and jj.Tambahan = 'N'
    order by j.UASTanggal, j.UASJamMulai, j.UASJamSelesai, j.Nama";
  $r = _query($s); $n = 0;
  $HariID = -320;
  $kanan = "<img src='img/kanan.gif' />";
  echo $hdr;
  while ($w = _fetch_array($r)) {
    $n++;
    if ($w['Final'] == 'Y') $c = "class=nac";
    else $c = "class=ul";
    
    // Ambil dosen2
    $dsn = AmbilDosen2($w['JadwalID']);
    
    // Tampilkan data
	$JumlahJadwalUAS = GetaField('jadwaluas', "JadwalID='$w[JadwalID]' and KodeID", KodeID, "count(JadwalUASID)");
	$rowspan = "rowspan=". (($JumlahJadwalUAS == 0)? 1 : $JumlahJadwalUAS);
	
    echo "<tr>
      <td class=inpx align=center width=20 $rowspan>$n</font></br>
        <div align=center><sub title='ID Jadwal'>#$w[JadwalID]</div></sub>
        </td>
      <td $c $rowspan><b>$w[Nama]</b> <sup>$w[MKKode]</sup><br>
		<div align=left>
			(<b>$w[HR]</b>, $w[_JM]&#8594;$w[_JS])
			</div>
		<div align=right>
			<b>Dosen:</b><i> $w[DSN]
			$dsn</i></div>
        </td>
      <td $c align=center $rowspan>
        &nbsp;<br>
		<b>$w[NamaKelas]</b>&nbsp;<br>
		&nbsp;</br>
		<div valign=bottom># Mhsw: <b>$w[JumlahMhsw]</b></div>
        </td>";
	
	if($JumlahJadwalUAS == 0)
	{ if($w['Final'] == 'Y')
		echo "
		<td $c colspan=5 align=center><b>Belum terjadwal.</b></td>";
		else
		echo "
	  <td $c colspan=5 align=center><b>Belum terjadwal.</b> <a href='#' onClick=\"javascript:JdwlEdt(1, $w[JadwalID])\">>> Tambah <<</a></td>";
	}
	else
	{ 
	  $s1 = "select  ju.JadwalUASID,
	            date_format(ju.Tanggal, '%d-%m-%y') as _UASTanggal,
			    huas.Nama as _UASHari, ju.JumlahMhsw as _JumlahMhswUAS,
			    LEFT(ju.JamMulai, 5) as _UASJamMulai, LEFT(ju.JamSelesai, 5) as _UASJamSelesai
				from jadwaluas ju left outer join hari huas on huas.HariID = date_format(ju.Tanggal, '%w')
				where ju.JadwalID='$w[JadwalID]' and ju.KodeID='".KodeID."'";
	  $r1 = _query($s1);
	  while($w1 = _fetch_array($r1))
	  {	  if ($w['Final'] == 'Y')
	      {  $edt = "<img src='img/lock.jpg' width=26 title='Sudah difinalisasi. Sudah tidak dapat diedit.' />";
			 $del = "&times;";
			 $editkursi = "<a href='#' onClick=\"alert('Penempatan kursi mahasiswa sudah tidak dapat dilakukan.')\"><img src='img/kursi.jpg'></a>";
          }
		  else 
		  {	 $edt = "<a href='#' onClick=\"javascript:JdwlEdt(0, $w[JadwalID], $w1[JadwalUASID])\" title='Edit jadwal'><img src='img/edit.jpg' width=20 /></a>";
			 $del = ($w1['JumlahMhsw'] > 0)? "<abbr title='Tidak dapat dihapus karena sudah ada Mhsw yang mendaftar'>&times;</abbr>" : "<a href='#' onClick=\"javascript:JdwlDel($w[JadwalID],$w1[JadwalUASID])\" title='Hapus jadwal'><img src='img/del.gif' /></a>";
			 $editkursi = "<a href='#' onClick=\"EdtKursi('$w1[JadwalUASID]')\"><img src='img/kursi.jpg'></a>";
		  }
		  
		  echo "
		  <td $c align=center>
			$edt
			</td>
		  <td $c align=center>
			<sup>$w1[_UASHari]</sup><br />
			$w1[_UASTanggal]
			</td>
		  <td $c align=center>
			<sup>$w1[_UASJamMulai]</sup>&#8594;<sub>$w1[_UASJamSelesai]</sub>
			</td>
		  
		  <td $c align=center valign=center nowrap>
			$editkursi
			<div valign=bottom># Mhsw: <b>$w1[_JumlahMhswUAS]</b></div>
			</td>
		  <td class=ul1 align=center>
			$del
		  </tr>";
	   }
	}
  }
  echo "</table></p>";
}

function AmbilDosen2($id) {
  $s = "select d.Nama, d.Gelar, jd.JenisDosenID
    from jadwaldosen jd
      left outer join dosen d on d.Login = jd.DosenID and d.KodeID = '".KodeID."'
    where jd.JadwalID = '$id'
    order by d.Nama";
  $r = _query($s);
  //die("<pre>$s</pre>");
  $a = array();;
  while ($w = _fetch_array($r)) {
    $a[] = "&rsaquo; $w[Nama] <sup>($w[Gelar])</sup>";
  }
  $a = (!empty($a))? "<br />".implode("<br />", $a) : '';
  return $a;
}

function JdwlDel() {
  $id = $_REQUEST['id'];
  $uasid = $_REQUEST['uasid'];
  $s = "update jadwal set JadwalUASID = 0 where JadwalID = '$id' ";
  $r = _query($s);
  $s = "delete from jadwaluas where JadwalUASID = '$uasid' ";
  $r = _query($s);
  BerhasilSimpan("?mnux=$_SESSION[mnux]&gos=", 1);
}

function JdwlDelAll() {
  $thn = sqling($_REQUEST['thn']);
  $prd = sqling($_REQUEST['prd']);
  $prg = sqling($_REQUEST['prg']);
  $whr_prg = (empty($prg))? '' : "and ProgramID = '$prg' ";
  // Hapus
  $s = "update jadwal set JadwalUASID = 0 where TahunID = '$thn' and ProdiID = '$prd' $whr_prg";
  $r = _query($s);
  $s = "select JadwalID from jadwal where TahunID = '$thn' and ProdiID = '$prd' $whr_prg";
  $r = _query($s);
  while ($w = _fetch_array($r)){
  	  $s2 = "delete from jadwaluas where JadwalID = $w[JadwalID] ";
	  $r2 = _query($s2);
  }
  BerhasilSimpan("?mnux=$_SESSION[mnux]&gos=", 1);
}

function JdwlEdtScript() {
  echo <<<SCR
  <script>
  function JdwlEdt(md, id, juasid) {
    var _rnd = randomString();
    lnk = "$_SESSION[mnux].edit.php?md="+md+"&id="+id+"&juasid="+juasid+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=600, height=400, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function JdwlDel(id,uasid) {
    if (confirm("Anda yakin akan menghapus jadwal ini?")) {
      var _rnd = randomString();
      window.location = "?mnux=$_SESSION[mnux]&BypassMenu=1&gos=JdwlDel&id="+id+"&uasid="+uasid+"&_rnd="+_rnd;
    }
  }
  function JdwlDelAll(thn, prd, prg) {
    var psn = (prg == "")? "Anda juga akan menghapus semua jadwal dari semua program pendidikan." : "";
    if (confirm("Anda yakin akan menghapus semua jadwal ini? "+psn)) {
      var _rnd = randomString();
      window.location = "?mnux=$_SESSION[mnux]&BypassMenu=1&gos=JdwlDelAll&thn=" + thn + "&prd=" + prd + "&prg=" + prg+"&_rnd="+_rnd;
    }
  }
  function EdtKursi(id) {
    var _rnd = randomString();
	lnk = "$_SESSION[mnux].pilihkursi.php?id="+id+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=1000, height=600, scrollbars, status, resizable");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
SCR;
}
?>

</BODY>
</HEAD>
