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

			if (!(isset($spam) || isset($ham))) {
				echo "
			Select training option:<br><br>
			<form id='train' action='./testbutton.php' method='GET'>
				<button class='button' id='spam' type='submit' name='spam'>SPAM</button> 
				<button class='button' id='ham' type='submit' name='ham'>HAM</button>
			</form>

			<br><br><br><br><br>


<!--button type='button' class='button'>
    <span class='button__text'>Save Changes</span>
</button-->

 
	<script>
		$(document).ready(function() {
			$(\"#spam\").click(function() {
			  $(this).html(
				`<div style='float:left;'><span class=\"button--loading\"></span></div> <div style='margin-left:14px;'>Learning SPAM...</div>`
			  );
			});
		});
		$(document).ready(function() {
			$(\"#ham\").click(function() {
			  $(this).html(
				`<div style='float:left;'><span class=\"button--loading\"></span></div> <div style='margin-left:14px;'>Learning HAM...</div>`
			  );
			});
		});
	</script>









";

			} else {
			sleep(2);
				echo "<span class='success'>landing page</span>";
			}
			
	echo "
</body>
</html>";

?>
