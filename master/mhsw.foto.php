<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com

$mhswbck = GetSetVar('mhswbck');

function TampilkanHeader($w) {
  $foto = FileFotoMhsw($w, $w['Foto']);
  echo "<p><table class=box cellspacing=2 cellpadding=4 width=600>

  <tr><td class=inp width=100>NPM</td>
      <td class=ul><b>$w[MhswID]</td>
      <td rowspan=7 class=box width=124 style='padding: 2pt' align=center valign=middle>
      <img src='$foto' height=120 /></td>
      </tr>

  <tr><td class=inp>Nama</td>
      <td class=ul><b>$w[Nama]</td></tr>
  <tr><td class=inp>Program</td>
      <td class=ul><b>$w[ProgramID]</td></tr>
  <tr><td class=inp>Program Studi</td>
      <td class=ul><b>$w[ProdiID]</td></tr>
  <tr><td class=inp>File Foto</td>
      <td class=ul>$w[Foto]</td></tr>
  <tr><td class=inp>Pilihan</td>
      <td class=ul>
        <input type=button name='Kembali' value='Kembali ke Data Mhsw'
          onClick=\"location='?mnux=master/mhsw.edt&mhswid=$w[MhswID]'\" />
      </td></tr>
  </table></p>";
}
function TampilkanUploadFoto($w) {
  $MaxFileSize = 500000;
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=600>
  <form action='index.php' enctype='multipart/form-data' method=POST>
  <input type=hidden name='MAX_FILE_SIZE' value='$MaxFileSize' />
  <input type=hidden name='mnux' value='$_SESSION[mnux]'>
  <input type=hidden name='gos' value='aplodFoto'>
  <input type=hidden name='mhswid' value='$w[MhswID]'>
  <tr><td class=inp width=100>File Foto</td>
    <td class=ul><input type=file name='foto' size=35></td></tr>
  <tr><td class=ul colspan=2 align=center>
    <input type=submit name='Upload' value='Upload File Foto'></td></tr>
  </form></table></p>";
}
function aplodFoto() {
  $MhswID = $_REQUEST['mhswid'];
  $upf = $_FILES['foto']['tmp_name'];
  $arrNama = explode('.', $_FILES['foto']['name']);
  $tipe = $_FILES['foto']['type'];
  $arrtipe = explode('/', $tipe);
  $extensi = $arrtipe[1];
  $dest = "foto/" . $MhswID . '.' . $extensi;
  //echo $dest;
  if (move_uploaded_file($upf, $dest)) {
    $s = "update mhsw set Foto='$dest' where MhswID='$MhswID' ";
    $r = _query($s);
  }
  else echo ErrorMsg("Gagal Upload Foto",
    "Tidak dapat meng-upload file foto.<br />
    Periksa file yg di-upload, karena besar file dibatasi cuma: <b>$_REQUEST[MAX_FILE_SIZE]</b> byte.");
  //print_r($_FILES);
}

$gos = (empty($_REQUEST['gos']))? 'donothing' : $_REQUEST['gos'];
$gos();
$w = GetFields('mhsw', 'MhswID', $_REQUEST['mhswid'], '*');

// *** Main ***
TampilkanHeader($w);
TampilkanUploadFoto($w);
?>
