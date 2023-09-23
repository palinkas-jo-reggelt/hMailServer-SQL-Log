<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	include_once("config.php");
	include_once("functions.php");

	if (isset($_GET['fn'])) {$fn = trim($_GET['fn']);} else {$fn = "";}
	if (isset($_REQUEST['run'])) {$run = $_REQUEST['run'];} else {$run = null;}

	echo "
<!DOCTYPE html> 
<html>
<head>
<title>hMailServer SQL Log</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='stylesheet' href='./css/jquery-ui.smoothness.css'>
<script src='./js/jquery.min.js'></script>
<script src='./js/jquery-ui.min.js'></script>
<link rel='stylesheet' type='text/css' media='all' href='./css/iframe.css'>
</head>
<body>";

	echo "<div class='pagehead'>Spamassasin Report on file: ".$fn."</div><br><br>";
	if ($fn) {
		if (file_exists($fn)) {
			$hMS = hMSAuthenticate();
			$SAHost = $hMS->Settings->AntiSpam->SpamAssassinHost;
			$SAPort = $hMS->Settings->AntiSpam->SpamAssassinPort;
			$script_command = "\"".$spamassassinPath."\spamc.exe\" -R < \"".$fn."\"";
			try {
				$shell = shell_exec($script_command);
				if (!isset($shell)){
					throw new Exception("UNKNOWN ERROR");
				}
				echo "
					<a href='./iframemovetospam.php?fn=".urlencode($fn)."'><button id='movetospam' class='button'>Move to Spam</button></a>
					<a href='./iframemovetoinbox.php?fn=".urlencode($fn)."'><button id='movetoinbox' class='button'>Move to Inbox</button></a>
					<br><br>";
				echo "<pre>".htmlentities($shell)."</pre>";
				echo "
					<script>
						$(document).ready(function() {
							$(\"#movetospam\").click(function() {
							  $(this).html(
								`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Moving to SPAM...</div>`
							  );
							});
						});
						$(document).ready(function() {
							$(\"#movetoinbox\").click(function() {
							  $(this).html(
								`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Moving to INBOX...</div>`
							  );
							});
						});
					</script>";
			} catch (Exception $ex) {
				echo "<span class='warning'>[ERROR] Running command \"".$script_command."\" resulted in an error!</span><br><br>";
				echo "<span class='warning'>[ERROR] ".$ex."</span>";
			}
		} else {
			echo "ERROR - EML file could not be found";
		}
	} else {
		echo "ERROR - EML filename not specified<br>";
	}

	echo "
</body>
</html>";

?>