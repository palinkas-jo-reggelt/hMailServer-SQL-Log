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
	if (isset($_GET['msgid'])) {$msgid = trim($_GET['msgid']);} else {$msgid = "";}
	if (isset($_REQUEST['spam'])) {$spam = $_REQUEST['spam'];} else {$spam = null;}
	if (isset($_REQUEST['ham'])) {$ham = $_REQUEST['ham'];} else {$ham = null;}

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

	echo "
	<div class='pagehead'>Train spamassassin bayes on file: ".$fn."</div><br><br>";
	if ($fn) {
		if (file_exists($fn)) {
			if (!(isset($spam) || isset($ham))) {
				echo "
	Select training option:<br><br>
	<form action='./iframebayes.php' method='GET'>
		<input type='hidden' name='fn' value='".$fn."'>
		<input type='hidden' name='msgid' value='".$msgid."'>
		<button class='button' id='spam' type='submit' name='spam'>SPAM</button>
		<button class='button' id='ham' type='submit' name='ham'>HAM</button>
	</form>";
			} else {
				if (isset($spam)) {$spamham = "spam";}
				if (isset($ham)) {$spamham = "ham";}
				$hMS = hMSAuthenticate();
				$SAHost = $hMS->Settings->AntiSpam->SpamAssassinHost;
				$SAPort = $hMS->Settings->AntiSpam->SpamAssassinPort;
				$script_command = "\"".$spamassassinPath."\spamc.exe\" -d \"$SAHost\" -p \"$SAPort\" -x -L ".$spamham." < \"".$fn."\"";
				try {
					$feedBayes = exec($script_command);
					if (preg_match("/Message\ssuccessfully\sun\/learned/",$feedBayes)) {
						echo "
	<span class='success'>Message successfully fed to spamassassin for ".$spamham." training</span><br><br>";
					} else {
						echo "
	<span class='warning'>Error: Output of spamc operation:</span><br><br><span class='warning'>".$feedBayes."</span><br><br>";
					}
					if (isset($spam)) {
						echo "
	<a href='./iframemovetospam.php?fn=".urlencode($fn)."'><button id='movetospam' class='button'>Move to Spam</button></a>";
					}
					if (isset($ham)) {
						echo "
	<a href='./iframemovetoinbox.php?fn=".urlencode($fn)."'><button id='movetoinbox' class='button'>Move to Inbox</button></a>";
					}
					$sql = $pdo->prepare("UPDATE hm_log_msg SET spamadjusted = 1 WHERE id = ".$msgid.";");
					$sql->execute();

				}
				catch (Exception $e) {
					echo "<span class='warning'>ERROR!</span> ".$e->getMessage();
				}
			}
		} else {
			echo "
	<span class='warning'>ERROR - EML file could not be found</span>";
		}
	} else {
		echo "
	<span class='warning'>ERROR - EML filename not specified</span>";
	}

	echo "
	<script>
		$(document).ready(function() {
			$(\"#spam\").click(function() {
			  $(this).html(
				`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Learning SPAM...</div>`
			  );
			});
		});
		$(document).ready(function() {
			$(\"#ham\").click(function() {
			  $(this).html(
				`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Learning HAM...</div>`
			  );
			});
		});
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
	</script>
</body>
</html>";

?>