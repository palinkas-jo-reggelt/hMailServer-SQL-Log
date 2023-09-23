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

	if (isset($_GET['link'])) {$link = trim(urldecode($_GET['link']));} else {$link = "";}

	echo "
	<!DOCTYPE html> 
	<html>
	<head>
	<title>hMailServer SQL Log</title>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<meta http-equiv='Content-Style-Type' content='text/css'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' type='text/css' media='all' href='./css/stylesheet.css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet'> 
	</head>
	<body>
		<div class='wrapper'>
			<div class='section'>
				<center>
					<h3>Dead Link</h3>
					This page exists to prevent you from clicking on potentially dangerous links.<br><br>
					If you REALLY <b>REALLY</b> want to go to this link, go ahead and click it. But you were warned!<br><br>
					<div class='deadlink'>
						<a href='".$link."' target='_blank'>".$link."</a>
					</div>
				</center>
			</div>
		</div>
	</body>
	</html>";

?>