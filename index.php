<?php
  // Sisfo Kampus versi 4
  // Author: Emanuel Setio Dewo
  // Email: setio.dewo@gmail.com
  // Start: Juli 2008
  session_start();
  include_once "dwo.lib.php";
  include_once "db.mysql.php";
  include_once "connectdb.php";
  include_once "parameter.php";
  include_once "cekparam.php";
  $mdlid = GetSetVar('mdlid');
  $loadTime = date('m d, Y H:i:s');
  
  function cekSession(){
  	$s = "select * from session where sessionId = '".$_SESSION['_Session']."' and user = '".$_SESSION['_Login']."'";
	$q = _query($s);
	$w = _fetch_array($q);
	if (mysql_num_rows($q) == 0){
		$s2 = "insert into session (sessionId,user,address,sessionTime) values ('".$_SESSION['_Session']."', '".$_SESSION['_Login']."', '".$_SERVER['REMOTE_ADDR']."', '".time()."')";
		$q2 = _query($s2);
	} else {
		$s2 = "update session set sessionTime = '".time()."' where sessionId = '".$w[sessionId]."'";
		$q2 = _query($s2);	
	}
	
  }
  
  //ADD AMR 06082013
  //print_r($_SESSION); 
 ?>
 
<HTML xmlns="http://www.w3.org/1999/xhtml">
  <HEAD><TITLE><?php echo $_Institution; ?></TITLE>
  <META http-equiv="cache-control" content="max-age=0">
  <META http-equiv="pragma" content="no-cache">
  <META http-equiv="expires" content="0" />
  <META http-equiv="content-type" content="text/html; charset=UTF-8">
  
  <META content="Emanuel Setio Dewo" name="author" />
  <META content="Sisfo Kampus" name="description" />
  
  <link rel="stylesheet" type="text/css" href="themes/<?=$_Themes;?>/index.css" />
  <link rel="stylesheet" type="text/css" href="themes/<?=$_Themes;?>/ddcolortabs.css" />
  
	<link type="text/css" rel="stylesheet" media="all" href="chat/css/chat.css" />
	<link type="text/css" rel="stylesheet" media="all" href="chat/css/screen.css" />
	
	<!--[if lte IE 7]>
	<link type="text/css" rel="stylesheet" media="all" href="chat/css/screen_ie.css" />
	<style>
	.footer {
		clear: both;
		text-align: center;
		padding: 4px;
		background: transparent url(themes/default/img/bot_bg.jpg) repeat-x scroll;
		border-top: 1px solid #DDD;
		border-bottom: 1px solid #DDD;
		bottom:0px;
		position:absolute;
		width:100%;
	}
	.chatboxcontent {
		width:225px;
		padding:7px;
	}
	</style>
	<![endif]-->
	
	<script type="text/javascript" src="chat/js/jquery-1.2.6.min.js"></script>
  
  <script type="text/javascript" language="javascript" src="include/js/dropdowntabs.js"></script>
  <!-- <script type="text/javascript" language="javascript" src="include/js/jquery.js"></script> -->
  <script type="text/javascript" language="javascript" src="floatdiv.js"></script>
  <script type="text/javascript" language="javascript" src="include/js/drag.js"></script>
  <link rel="stylesheet" type="text/css" href="themes/<?=$_Themes;?>/drag.css" />
  
  <link href="fb/facebox.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="fb/facebox.js" language='javascript' type="text/javascript"></script>
  
  <script type="text/javascript" language="javascript" src="include/js/boxcenter.js"></script>
  <script type="text/javascript" language="javascript" src="clock.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox() ;
	  $("input[type=button]").attr("class","buttons");
	  $("input[type=submit]").attr("class","buttons");
	  $("input[type=reset]").attr("class","buttons");
    })
  </script>
  <!--<script type="text/javascript" language="javascript" src="include/js/jquery.autocomplete.js"></script>-->
  <!--<script type="text/javascript" language="javascript" src="include/js/jtip.js"></script>-->

  </HEAD>
<BODY onLoad="setClock('<?php print $loadTime ?>'); setInterval('updateClock()', 1000 )">
<div id="main_container">
  <?php
    include "header.php";

    if (!empty($_SESSION['_Session'])) {
      $NamaLevel = GetaField('level', 'LevelID', $_SESSION['_LevelID'], 'Nama');

      if (!empty($_SESSION['mdlid'])) {
        $_strMDLID = GetaField('mdl', "MdlID", $_SESSION['mdlid'], "concat(MdlGrpID, ' &raquo; ', Nama)");
        echo "<div class=MenuDirectory>Menu: $_strMDLID</div>";
      }
      echo "<div class=NamaLogin>Login: <b>$_SESSION[_Nama]</b> ($NamaLevel) &raquo; <a href='?mnux=loginprc&gos=lout'>Logout</a></div>
			<div class=WaktuServer><b>Waktu server:</b> <span id='clock' title='".date('m d, Y H:i:s')."'>&nbsp;</span>&nbsp;</div>";
			echo '<script type="text/javascript" src="chat/js/chat.js"></script>';
			$_SESSION['username'] = $_SESSION[_Login]; // Must be already set
			$tombolChat = "<div id='onlineUser' onClick='javascript:openUser()'></div>";
			 cekSession();
      if (empty($_REQUEST['BypassMenu'])) include "menusis.php";
    } else {
		echo '<script>
			$("#userbox").css("display","none");
		</script>';
	}

    echo "<div class=isi>";

    if (file_exists($_SESSION['mnux'].'.php')) {
      // cek apakah berhak mengakses? Harus dicek 1 per 1 karena mungkin 1 modul tersedia bagi banyak level
      $sboleh = "select * from mdl where Script='$_SESSION[mnux]'";
      $rboleh = _query($sboleh); $ktm = -1;
      if (_num_rows($rboleh) > 0) {
        while ($wboleh = _fetch_array($rboleh)) {
          $pos = strpos($wboleh['LevelID'], ".$_SESSION[_LevelID].");
          if ($pos === false) {}
          else $ktm = 1;
        }
        if ($ktm <= 0) {
          echo ErrorMsg("Anda Tidak Berhak",
            "Anda tidak berhak mengakses modul ini.<br />
            Hubungi Sistem Administrator untuk memperoleh informasi lebih lanjut.
            <hr size=1>
            Pilihan: <a href='?mnux=&slnt=loginprc&slntx=lout'>Logout</a>");
        }
        else include_once $_SESSION['mnux'].'.php';
      } else include_once $_SESSION['mnux'].'.php';
      include_once "disconnectdb.php";
    }
    else echo ErrorMsg('Fatal Error', "Modul tidak ditemukan. Hubungi Administrator!!!<hr size=1 color=silver>
    Pilihan: <a href='?mnux=&KodeID=$_SESSION[KodeID]'>Kembali</a>");
    echo "</div>";
  ?>
  <div class="bottomspace"></div>
  </div>
  <div class='footer'>
  <center>Powered by <a href="http://www.sisfokampus.net" title="PT Sisfo Sukses Mandiri">Sisfo Kampus 2010</a></center>
  <? echo $tombolChat ?>
  </div>
  
  <!--
  <div id="divInfo" style="position:absolute">
    <a href="<?php echo 'http://'.$arrID['Website'];?>" rel='facebox' title="Website: <?php echo $arrID['Website'];?>"><img src="img/panel_kiri.gif" /></a>
  </div>
  <script>
  JSFX_FloatDiv("divInfo", 0, 100).flt();
  </script>
  -->
</BODY>

</HTML>
