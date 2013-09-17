<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 04/12/2008
//

// *** Parameters ***
$_honBulan = GetSetVar('_honBulan', date('m'));
$_honTahun = GetSetVar('_honTahun', date('Y'));
$_honDosen = GetSetVar('_honDosen');
$_honProdi = GetSetVar('_honProdi');

// *** Main ***
TampilkanJudul("Honor Dosen");
TampilkanHeaderHonorer();
$gos = (empty($_REQUEST['gos']))? 'DftrHondok' : $_REQUEST['gos'];
$gos();


// *** Functions ***
function TampilkanHeaderHonorer() {
  $optbulan = GetMonthOption($_SESSION['_honBulan']);
  $optprodi = GetOption2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID',
    $_SESSION['_honProdi'], "KodeID='".KodeID."'", 'ProdiID');
  RandomStringScript();
  echo <<<ESD
  <table class=box cellspacing=1 width=880>
  <form name='frmHeaderHondok' action='?' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='gos' value='' />
  <input type=hidden name='_honPage' value='1' />
  
  <tr>
      <td class=ul1>
        Bulan:<br />
        <select name='_honBulan' onChange='this.form.submit()'>$optbulan</select></td>
      <td class=ul1 nowrap>
        Tahun:<br />
        <input type=text name='_honTahun' value='$_SESSION[_honTahun]' size=4 maxlength=4 /></td>
      <td class=ul1>
        Homebase Dosen:<br />
        <select name='_honProdi' onChange='this.form.submit()'>$optprodi</select>
        </td>
      <td class=ul1>
        Filter Nama Dosen:<br />
        <input type=text name='_honDosen' value='$_SESSION[_honDosen]' size=20 maxlength=50 />
        </td>
      <td class=ul1 valign=bottom align=right>
        <input type=submit name='btnSubmit' value='Kirim' />
        <input type=button name='btnReset' value='Reset Filter'
          onClick="location='?mnux=$_SESSION[mnux]&gos=&_honPage=1&_honDosen=&_honProdi='" />
        &raquo;
        <input type=button name='btnTambah' value='+Dosen'
          onClick="javascript:TambahHondok()" />
        </td>
      </tr>
  </form>
  </table>
  
  <script>
  function TambahHondok() {
    var _rnd = randomString();
    lnk = "$_SESSION[mnux].dosen.php?_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  function EditHondok(_honDosenID, _honID, _honTahunID) {
    var _rnd = randomString();
    lnk = "../$_SESSION[mnux].dosen.php?gos=fnEditHondok&_honDosenID="+_honDosenID+"&_md=0&_honID="+_honID+"&_honTahunID="+_honTahunID+"&_rnd="+_rnd;
    win2 = window.open(lnk, "", "width=700, height=600, scrollbars, status");
    if (win2.opener == null) childWindow.opener = self;
  }
  </script>
ESD;
}

function DftrHondok() {
  $_jmlMinggu = _headerTabelHondok();
  $whr_prd = ($_SESSION['_honProdi'] == '')? '' : "and d.Homebase = '$_SESSION[_honProdi]' ";
  $s = "select hd.*,
      d.Nama, d.Gelar, d.Homebase
    from honordosen hd
      left outer join dosen d on d.Login = hd.DosenID and d.KodeID = '".KodeID."'
    where hd.Bulan = '$_SESSION[_honBulan]'
      and hd.Tahun = '$_SESSION[_honTahun]'
      $whr_prd
    group by hd.DosenID
    order by d.Homebase, d.Nama";
  $r = _query($s); $n = 0;
  $lebar = ($_jmlMinggu * 100) + 4;
  while ($w = _fetch_array($r)) {
    $n++;
    echo <<<ESD
    <tr><td class=inp>$n</td>
        <td class=ul>
          <sup>$w[DosenID] &minus; Gelar: $w[Gelar]</sup><br />
          $w[Nama]
          </td>
        <td class=ul colspan=7 style='padding:0; margin:0'>
          <iframe name='frm_$w[HonorDosenID]' src="$_SESSION[mnux].minggu.php?_detDosenID=$w[DosenID]&_detTahun=$_SESSION[_honTahun]&_detBulan=$_SESSION[_honBulan]" width=100% frameborder=0 height=24 scrolling=no>
          </iframe>
          </td>
        </tr>
ESD;
  }
  echo "</table></p>";
}
function _headerTabelHondok() {
  $minggu = '';
  $s = "select MingguID, Nama, Def
    from minggu
    where NA = 'N'
    order by MingguID";
  $r = _query($s);
  $_jmlMinggu = _num_rows($r);
  while ($w = _fetch_array($r)) {
    $minggu .= "<th class=ttl width=100px><abbr title='$w[Nama]'>$w[MingguID]</th>";
  }
  echo <<<ESD
  <table class=box cellspacing=1 width=880 align=center>
  <tr><th class=ttl width=20>#</th>
      <th class=ttl width=180>Dosen</th>
      $minggu
      <th class=ttl width=20></th>
      </tr>
ESD;
  return $_jmlMinggu;
}
?>
