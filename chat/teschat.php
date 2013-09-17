<?

  session_start();
  include_once "../db.mysql.php";
  include_once "../connectdb.php";

$_SESSION['username'] = "inu"; // Must be already set

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
	<link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
	<link type="text/css" rel="stylesheet" media="all" href="css/screen.css" />
	
	<!--[if lte IE 7]>
	<link type="text/css" rel="stylesheet" media="all" href="chat/css/screen_ie.css" />
	<![endif]-->
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/chat.js"></script>
  

</head>

<body>
<a href="javascript:void(0)" onClick="javascript:chatWith('chika')">Chat</a>
</body>
