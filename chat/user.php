<?
  session_start();
  include_once "../dwo.lib.php";
  include_once "../db.mysql.php";
  include_once "../connectdb.php";

$idleTime = 18000; // idle time in seconds
$time = time()-$idleTime;

$s = "select k.Nama as _user, l.Nama as _jabatan, s.user as _Login from session s
	left outer join karyawan k on s.user = k.Login
	left outer join level l on k.LevelID = l.LevelID
	where sessionTime > $time and sessionId != '".$_SESSION['_Session']."' and user != '".$_SESSION['_Login']."'";
$q = _query($s);
if (_num_rows($q) == 0){
	echo "<div align=center class=loadingUser >tidak ada staff yang sedang online</div>";
} else {
	while ($w = _fetch_array($q)){
		$name = str_replace(" ","%20",$w[_user]);
		echo "<div id='list_$w[_Login]' class=userList onMouseOver=hoverList('list_$w[_Login]') onMouseOut=outList('list_$w[_Login]') onclick=chatWith('$w[_Login]')>$w[_user] <sup>$w[_jabatan]</sup></div>";
	}
}

?>
<script>
function hoverList(id){
	$('#'+id).css('background-color','#8CBEF4');
	$('#'+id).css('color','#FFFFFF');
}
function outList(id){
	$('#'+id).css('background-color','#FFFFFF');
	$('#'+id).css('color','#666666');
}
</script>