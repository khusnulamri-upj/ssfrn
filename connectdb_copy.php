<?php
  // file: connectdb.php
  // author: E. Setio Dewo, Maret 2003

  $db_username = "upj_sisfokampus";
  $db_hostname = "localhost";
  $db_password = "PenSil3010";
  $db_name = "upj_sisfokampus";

  $con = _connect($db_hostname, $db_username, $db_password);
  $db  = _select_db($db_name, $con);

?>
