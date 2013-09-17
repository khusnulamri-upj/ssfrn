<?php
// Author: Emanuel Setio Dewo
// Oktober 2005

function ResetLogin() {
  $_SESSION['mdlid'] = 0;
  $_SESSION['_Login'] = '';
  $_SESSION['_Nama'] = '';
  $_SESSION['_TabelUser'] = '';
  $_SESSION['_LevelID'] = 0;
  $_SESSION['_Session'] = '';
  $_SESSION['_Superuser'] = 'N';
  $_SESSION['_KodeID'] = '';
  $_SESSION['_ProdiID'] = '';
  $_SESSION['KodeID'] = KodeID;
}

// Checking host
$_KodeID = GetSetVar('KodeID', KodeID);
if ($_KodeID != KodeID) {
  ResetLogin();
  $_KodeID = KodeID;
  $_SESSION['KodeID'] = KodeID;
  die("<h1>Error</h1>
    Terjadi kesalahan host. Anda telah beralih ke sistem lain.
    <hr size=1 color=silver />
    Opsi: <a href='index.php?KodeID=$_SESSION[KodeID]'>Kembali</a>");
}

$mnux = GetSetVar('mnux', $_defmnux);
if (empty($mnux)) {
  $mnux = $_defmnux;
  $_SESSION['mnux'] = $_defmnux;
}

if (empty($_SESSION['_Session']) && empty($mnux)) {
  $mnux = $_defmnux;
  $_SESSION['mnux'] = $_defmnux;
  $_SESSION['mdlid'] = 0;
}

?>
