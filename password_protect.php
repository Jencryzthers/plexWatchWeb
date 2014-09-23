<?php
include 'config/users.php';
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {
	function showLoginPasswordProtect($error_msg) {
	?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		  <title>PlexWatch 0.3.1 - Access Control</title>
		  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
			<!-- Le styles -->
			<link href="css/plexwatch.css" rel="stylesheet">
			<link href="css/plexwatch-tables.css" rel="stylesheet">
			<link href="css/font-awesome.min.css" rel="stylesheet" >
			<link href="css/xcharts.css" rel="stylesheet" >
			<style type="text/css">
			  body {
				padding-top: 60px;
				padding-bottom: 40px;
			  }
			  .sidebar-nav {
				padding: 9px 0;
			  }
			</style>
		  </head>
		<body>
		  <style>
			input { border: 1px solid black; }
		  </style>
		  
		  <script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-36811158-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
		 <script>
		 var isNS = (navigator.appName == "Netscape") ? 1 : 0;
		  if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
		  function mischandler(){
		   return false;
		 }
		  function mousehandler(e){
			var myevent = (isNS) ? e : event;
			var eventbutton = (isNS) ? myevent.which : myevent.button;
			if((eventbutton==2)||(eventbutton==3)) return false;
		 }
		 document.oncontextmenu = mischandler;
		 document.onmousedown = mousehandler;
		 document.onmouseup = mousehandler;
		  </script>

		  
			<div style="width:300px; margin-left:auto; margin-right:auto; text-align:center">
				<form method="post">
					<center><div  style="margin-left: 80px" class="logo" ></div></center>
					<br/><br/><br/>
						<br/>
					Login:<br /><input id="pmsIp" type="text" name="access_login" /><br />
					Password:<br /> <input type="password" name="access_password" />
						<font color="red"><?php echo $error_msg.'<br /><br />'?></font>										
					<input style="margin-left: 20px" class="btn btn-medium btn-primary" type="submit" name="Submit" value="Login" />
					<br/><br/>You are trying to reach a secured page.<br/>
					Your <a href="http://www.whatsmyip.us" style="color:white;"><b>IP</b></a> is: <script type="text/javascript" src="http://www.whatsmyip.us/showipsimple.php"> </script>
				</form>
			  <br />
			</div>
		</body>
		</html>
	<?php
		  die();
	}	
}

if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) {
    showLoginPasswordProtect("<br/><br/> Incorrect password.");
  }
  else {   
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');    
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
  }
}
else {
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
       if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }
}
?>