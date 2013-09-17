<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 07/10/2008

// *** Parameters ***
$TahunID = GetSetvar('TahunID');
$ProdiID = GetSetVar('ProdiID');
$_SESSION['parsial'] = 200;
$_SESSION['KodePTI'] = GetaField('identitas', 'Kode', KodeID, 'KodeHukum');
$_SESSION['Timer'] = 1;

// *** Main ***
TampilkanJudul("Export Data ke DIKTI");
TampilkanHeaderExportDikti();
if (!empty($TahunID)) {
  $gos = (empty($_REQUEST['gos']))? 'ExportDikti' : $_REQUEST['gos'];
  $gos();
}

// *** Functions ***
function TampilkanHeaderExportDikti() {
  $optprodi = GetOption2('prodi', "concat(ProdiID, ' - ', Nama)", 'ProdiID',
    $_SESSION['ProdiID'], "KodeID='".KodeID."'", 'ProdiID');
  CheckFormScript('TahunID');
  echo <<<ESD
  <p>
  <table class=box cellspacing=1 align=center width=800>
  <form name='frmHeader' action='?' method=POST onSubmit='return CheckForm(this)'>
  <tr><td class=wrn width=2></td>
      <td class=inp width=80>Tahun Akd:</td>
      <td class=ul>
        <input type=text name='TahunID' value='$_SESSION[TahunID]' size=6 maxlength=6 />
        <input type=submit name='SetParam' value='Set' />
        </td>
      <td class=inp width=80>Program Studi:</td>
      <td class=ul nowrap>
        <select name='ProdiID' onChange='this.form.submit()'>$optprodi</select>
        <font color=red>*) Kosongkan jika ingin diproses semua
        </td>
      </tr>
  
  </form>
  </table>
  </p>
ESD;
}
function ExportDikti() {
  $arrDikti = array(
    "Aktivitas Dosen~dosen",
    "Aktivitas Mahasiswa~mhsw",
    "Master Dosen~masterdosen",
    "Master Mahasiswa~mastermhsw",
    "Nilai Mahasiswa~nilaimhsw",
    "Kelulusan Mahasiswa~lulusmhsw",
    "Kurikulum-Matakuliah~kmk"
  );
  //var iframeids=["FRAMEMSG","FRAMEDETAIL","FRAMEDETAIL1"]

  $_frm = array();
  for ($i = 0; $i < sizeof($arrDikti); $i++) {
    $_frm[] = "\"FRM_$i\"";
  }
  $__frm = implode(',', $_frm);

  echo <<<ESD
  <script>var iframeids=[$__frm];</script>
  <script src='putiframe.js' language='javascript' type='text/javascript'></script>
ESD;

  for ($i = 0; $i < sizeof($arrDikti); $i++) {
    $_a = explode('~', $arrDikti[$i]);
    $judul = $_a[0];
    $modul = $_a[1];
    echo <<<ESD
    <iframe id="FRM_$i" 
      src="$_SESSION[mnux].$modul.php?TahunID=$_SESSION[TahunID]&ProdiID=$_SESSION[ProdiID]"
      width=800 height=1 frameborder=0 align=center>
    Browser Anda tidak mendukung frame.
    </iframe>
ESD;
  }
}

?>

<table class=box cellspacing=1 width=800>
<tr>
    <td class=ul1><b><u>Catatan:</u></b></td>
    </tr>
<tr>
    <td>
  <ol align=left>
  <li>Proses akan menghasilkan file DBF yang kemudian disatukan dengan program Evaluasi.</li>
  <li>Lakukan reindex dari program Evaluasi terhadap file DBF hasil proses.</li>
  <li>Setelah itu Anda dapat menggunakan program Evaluasi seperti biasa.</li>
  </ol>
    </td>
    </tr>
</table>
<p></p>
