<?
  session_start();
  include_once "../dwo.lib.php";
  include_once "../db.mysql.php";
  include_once "../connectdb.php";

function getOnlineStaff(){
	$idleTime = 100; // idle time in seconds
	
	$time = time()-$idleTime;
	$s = "delete from session where sessionTime <= $time";
	$q = _query($s);
	
	$s = "select COUNT(sessionId) as _onlineusr from session where sessionTime > $time and sessionId != '".$_SESSION['_Session']."' and user != '".$_SESSION['_Login']."'";
	$q = _query($s);
	$w = _fetch_array($q);
	
	return $w[_onlineusr];
}
  
$onlineStaffNum = getOnlineStaff();

echo "Online Staff (<b>$onlineStaffNum</b>)";
?>