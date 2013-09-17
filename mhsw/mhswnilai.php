<?php

// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 16 Sept 2008
// *** Parameters ***
$_krsTahunID = GetSetVar('_krsTahunID');
$_krsMhswID = GetSetVar('_krsMhswID');

// *** Main ***
TampilkanJudul("Nilai Semester Mahasiswa");
CekBolehAksesModul();
TampilkanCariMhswnya();
if (!empty($_krsTahunID) && !empty($_krsMhswID)) {
    $oke = BolehAksesData($_krsMhswID);
    if ($oke)
        $oke = ValidasiDataMhsw($_krsTahunID, $_krsMhswID, $khs);
    if ($oke) {
        $mhsw = GetFields("mhsw m
      left outer join statusawal sta on sta.StatusAwalID = m.StatusAwalID 
	  left outer join dosen d on d.Login = m.PenasehatAkademik", "m.KodeID = '" . KodeID . "' and m.MhswID", $_krsMhswID, "m.*, sta.Nama as STAWAL, d.Nama as namaDosen");
        $thn = GetFields("tahun", "KodeID = '" . KodeID . "' and TahunID", $_krsTahunID, "*");
        $gos = sqling($_REQUEST['gos']);
        if (empty($gos)) {
            TampilkanHeaderMhsw($thn, $mhsw, $khs);
            TampilkanDaftarKRSMhsw($thn, $mhsw, $khs);
        }
        else
            $gos();
    }
}

// *** Functions ***
function TampilkanCariMhswnya() {
    $s = "select DISTINCT(TahunID) from tahun where KodeID='" . KodeID . "' order by TahunID DESC";
    $r = _query($s);
    $opttahun = "<option value=''></option>";
    while ($w = _fetch_array($r)) {
        $ck = ($w['TahunID'] == $_SESSION['_krsTahunID']) ? "selected" : '';
        $opttahun .= "<option value='$w[TahunID]' $ck>$w[TahunID]</option>";
    }

    $_inputTahun = "<select name='_krsTahunID' onChange='this.form.submit()'>$opttahun</select>";
    echo "<table class=box cellspacing=1 align=center width=800>
  <form action='?' method=POST>
  <input type=hidden name='_krsHariID' value='' />
  <tr><td class=wrn width=2></td>
      <td class=inp width=80>Tahun Akd:</td>
      <td class=ul1 width=200>$_inputTahun</td>
      <td class=inp width=80>NIM:</td>
      <td class=ul1><input type=text name='_krsMhswID' value='$_SESSION[_krsMhswID]' size=20 maxlength=50 /></td>
      <td class=ul1 width=180>
        <input type=submit name='Cari' value='Cari' />
        </td>
      </tr>
  </form>
  </table>";
}

function CekBolehAksesModul() {
    $arrAkses = array(1, 20, 41, 120);
    $key = array_search($_SESSION['_LevelID'], $arrAkses);
    if ($key === false)
        die(ErrorMsg('Error', "Anda tidak berhak mengakses modul ini.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut."));
}

function BolehAksesData($nim) {
    if ($_SESSION['_LevelID'] == 120 && $_SESSION['_Login'] != $nim) {
        echo ErrorMsg('Error', "Anda tidak boleh melihat data KRS mahasiswa lain.<br />
      Anda hanya boleh mengakses data dari NIM: <b>$_SESSION[_Login]</b>.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut");
        return false;
    }
    else
        return true;
}

function ValidasiDataMhsw($thn, $nim, &$khs) {
    $khs = GetFields("khs k
    left outer join statusmhsw s on s.StatusMhswID = k.StatusMhswID", "k.KodeID = '" . KodeID . "' and k.TahunID = '$thn' and k.MhswID", $nim, "k.*, s.Nama as STA");
    if (empty($khs)) {
        $buat = ($_SESSION['_LevelID'] == 120) ? '' :
                "<hr size=1 color=silver />
      Opsi: Buat data semester Mhsw";
        echo ErrorMsg("Error", "Mahasiswa <b>$nim</b> tidak terdaftar di Tahun Akd <b>$thn</b>.<br />
      Masukkan data yang valid. Hubungi Sysadmin untuk informasi lebih lanjut.
      $buat");
        return false;
    } else {
        return true;
    }
}

function TampilkanHeaderMhsw($thn, $mhsw, $khs) {
    $KRSMulai = FormatTanggal($thn['TglKRSMulai']);
    $KRSSelesai = FormatTanggal($thn['TglKRSSelesai']);
    $BayarMulai = FormatTanggal($thn['TglBayarMulai']);
    $BayarSelesai = FormatTanggal($thn['TglBayarSelesai']);
    $GelarPA = GetaField('dosen', "KodeID='" . KodeID . "' and Login", $mhsw['PenasehatAkademik'], 'Gelar');
    // batas waktu
    $skrg = date('Y-m-d');
    if ($thn['TglKRSMulai'] <= $skrg && $skrg <= $thn['TglKRSSelesai']) {
        if ($_SESSION['_LevelID'] == 120) {
            $CetakKRS = "<a href='#' onClick=\"alert('Hubungi Staf TU/Adm Akademik untuk mencetak LRS/KRS.')\"><img src='img/printer2.gif' /></a>";
            $CetakLRS = '';
        } else {
            $CetakKRS = "<input type=button name='CetakKRS' value='Cetak KRS' onClick=\"javascript:CetakKRS($khs[KHSID])\" />";
            $CetakLRS = "<input type=button name='CetakLRS' value='Cetak LRS' onClick=\"javascript:CetakLRS($khs[KHSID])\"/>";
        }
    } else {
        $CetakKRS = '&nbsp;';
        $CetakLRS = '&nbsp;';
    }
    $keu = BuatSummaryKeu($mhsw, $khs);
    echo "<table class=box cellspacing=1 align=center width=800>
  <tr><td class=wrn width=2 rowspan=4></td>
      <td class=inp width=80>Mahasiswa:</td>
      <td class=ul width=200>$mhsw[Nama] <sup>($mhsw[MhswID])</sup></td>
      <td class=inp width=80>Sesi:</td>
      <td class=ul>$khs[Sesi]</td>
      <td class=inp width=80>Status:</td>
      <td class=ul width=100>$khs[STA] <sup>($khs[StatusMhswID])</sup></td>
      </tr>
  <tr>
      <td class=inp title='Dosen Pembimbing Akademik'>Pemb. Akd:</td>
      <td class=ul>$mhsw[namaDosen] <sup>$GelarPA</sup>&nbsp;</td>
      <td class=inp>Jml SKS:</td>
      <td class=ul>$khs[SKS]<sub title='Maksimum SKS yg boleh diambil'>&minus;$khs[MaxSKS]</sub></td>
      <td class=inp>Status Awal:</td>
      <td class=ul>$mhsw[STAWAL] <sup>($mhsw[StatusAwalID])</sup></td>
      </tr>
  <tr><td class=ul colspan=6>$keu</td></tr>
  </table>";
}

function BuatSummaryKeu($mhsw, $khs) {
    $_Biaya = number_format($khs['Biaya']);
    $_Potongan = number_format($khs['Potongan']);
    $_Bayar = number_format($khs['Bayar']);
    $_Tarik = number_format($khs['Tarik']);
    $Sisa = $khs['Biaya'] - $khs['Potongan'] + $khs['Tarik'] - $khs['Bayar'];
    $_Sisa = number_format($Sisa);
    $color = ($Sisa > 0) ? 'color=red' : '';
    $NamaBipot = GetaField('bipot', 'BIPOTID', $mhsw['BIPOTID'], 'Tahun');
    $NamaBipot = (empty($NamaBipot)) ? 'Blm diset' : $NamaBipot;
    return <<<ESD
  <table class=box cellspacing=1 width=100%>
  <tr><td class=inp width=15%>Bipot</td>
      <td class=inp width=15%>Total Biaya</td>
      <td class=inp width=15%>Total Potongan</td>
      <td class=inp width=15%>Total Bayar</td>
      <td class=inp width=15%>Total Penarikan</td>
      <td class=inp>SISA</td>
      </tr>
  <tr><td class=ul align=right>$NamaBipot
      </td>
      <td class=ul align=right>$_Biaya</td>
      <td class=ul align=right>$_Potongan</td>
      <td class=ul align=right>$_Bayar</td>
      <td class=ul align=right>$_Tarik</td>
      <td class=ul align=right><font size=+1 $color>$_Sisa</font></td>
  </table>
ESD;
}

function TampilkanDaftarKRSMhsw($thn, $mhsw, $khs) {
    $s = "select k.*
    from krs k
    where k.KHSID = '$khs[KHSID]'
    order by k.MKKode";
    $r = _query($s);
    $n = 0;

    echo "<table class=box cellspacing=1 align=center width=800>
    <tr><th class=ttl>#</th>
        <th class=ttl>Kode</th>
        <th class=ttl>Nama Matakuliah</th>
        <th class=ttl>SKS</th>
        <th class=ttl><abbr title='Nilai Akhir'>N.Akh</abbr></th>
        <th class=ttl>Grade</th>
        <th class=ttl>Bobot</th>
    </tr>";
    $jmlsks = 0;
    $jmlbobot=0;
    while ($w = _fetch_array($r)) {
        $n++;
        echo <<<ESD
    <tr>
        <td class=inp width=30>$n</td>
        <td class=ul width=100>$w[MKKode]</td>
        <td class=ul>$w[Nama]</td>
        <td class=ul align=right width=20>$w[SKS]</td>
        <td class=ul align=right width=30>$w[NilaiAkhir]</td>
        <td class=ul width=30 align=center>$w[GradeNilai]</td>
        <td class=ul width=30 align=right>$w[BobotNilai]</td>
        </tr>                    
ESD;
        
        $jmlsks = $jmlsks+$w[SKS];
        $jmlbobot = $jmlbobot + ($w[SKS]*$w[BobotNilai]);
    }
    
    $ips = round(($jmlbobot/$jmlsks),2);

    echo <<<ESD
    <tr>
        <td class=inp width=30>&nbsp;</td>
        <td class=ul width=100>&nbsp;</td>
        <td class=ul><b>Jumlah</b></td>
        <td class=ul align=right width=20><b>$jmlsks</b></td>
        <td class=ul align=right width=30>&nbsp;</td>
        <td class=ul width=30 align=center>&nbsp;</td>
        <td class=ul width=30 align=right><b>$jmlbobot</b></td>
        </tr>
ESD;

    echo "</table> <br/>";

    echo <<<ESD
   <table class=box cellspacing=1 align=center width=800>
  <tr>    
      <td class=inp width=80><b>Jumlah SKS : </b></td>
      <td class=ul width=200><b>$jmlsks</b></td      
  </tr>
  <tr>      
      <td class=inp width=80><b>Jumlah Bobot : </b></td>
      <td class=ul width=200><b>$jmlbobot</b></td 
  <tr>
  <tr>      
      <td class=inp width=80><b>Indeks Prestasi Semester (IPS) : </b></td>
      <td class=ul width=200><b>$ips</b></td 
  <tr>          
  </table>
ESD;
}

?>
