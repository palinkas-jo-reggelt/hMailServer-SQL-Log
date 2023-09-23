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
	include_once("head.php");

	if (isset($_GET['logfile'])) {$logfile = trim($_GET['logfile']);} else {$logfile = "";}
	if (isset($_GET['LogTypes'])) {$LogTypes = $_GET['LogTypes'];} else {if (isset($LogType)) {$LogTypes = array();} else {$LogTypes = array('SMTPD');}}
	if (isset($_GET['LogType'])) {$LogType = trim($_GET['LogType']);} else {$LogType = null;}
	if (isset($_GET['LogFilter'])) {$LogFilter = trim($_GET['LogFilter']);} else {$LogFilter = "";}
	if (isset($_GET['clear'])) {
		$LogTypes = array(); 
		if (isset($LogType)) {unset($LogType);}
	}

	if (in_array('ALL',$LogTypes)) {$ALLChecked = "checked ";} else {$ALLChecked = "";}
	if (in_array('SMTPD',$LogTypes)) {$SMTPDChecked = "checked ";} else {$SMTPDChecked = "";}
	if (in_array('SMTPC',$LogTypes)) {$SMTPCChecked = "checked ";} else {$SMTPCChecked = "";}
	if (in_array('POP3D',$LogTypes)) {$POP3DChecked = "checked ";} else {$POP3DChecked = "";}
	if (in_array('POP3C',$LogTypes)) {$POP3CChecked = "checked ";} else {$POP3CChecked = "";}
	if (in_array('IMAPD',$LogTypes)) {$IMAPDChecked = "checked ";} else {$IMAPDChecked = "";}
	if (in_array('DEBUG',$LogTypes)) {$DEBUGChecked = "checked ";} else {$DEBUGChecked = "";}
	if (in_array('TCPIP',$LogTypes)) {$TCPIPChecked = "checked ";} else {$TCPIPChecked = "";}
	if (in_array('APPLICATION',$LogTypes)) {$APPLICATIONChecked = "checked ";} else {$APPLICATIONChecked = "";}
	if (in_array('ERROR',$LogTypes)) {$ERRORChecked = "checked ";} else {$ERRORChecked = "";}
	if (isset($LogType)) {$RAWChecked = "checked "; $SMTPDChecked = "";} else {$RAWChecked = "";}
	
	echo "
	<div class='section'>
		<h2>".$logfile."</h2>";

	if (preg_match("/^hmailserver_\d.*\.log$/",$logfile)) {

		echo "
		<form action='".$_SERVER['PHP_SELF']."' method='get' class='form' id='log-parser'>
			Show logs only for:
			<input type='checkbox' ".$ALLChecked."name='LogTypes[]' value='ALL' id='checkAll'><label for='checkAll'>All</label>
			<input type='checkbox' ".$SMTPDChecked."name='LogTypes[]' value='SMTPD' id='LogType_0'><label for='LogType_0'>SMTP server (daemon)</label>
			<input type='checkbox' ".$SMTPCChecked."name='LogTypes[]' value='SMTPC' id='LogType_1'><label for='LogType_1'>SMTP client</label>
			<input type='checkbox' ".$POP3DChecked."name='LogTypes[]' value='POP3D' id='LogType_2'><label for='LogType_2'>POP3</label>
			<input type='checkbox' ".$POP3CChecked."name='LogTypes[]' value='POP3C' id='LogType_3'><label for='LogType_3'>POP3 fetch</label>
			<input type='checkbox' ".$IMAPDChecked."name='LogTypes[]' value='IMAPD' id='LogType_4'><label for='LogType_4'>IMAP</label>
			<input type='checkbox' ".$DEBUGChecked."name='LogTypes[]' value='DEBUG' id='LogType_5'><label for='LogType_5'>Debug</label>
			<input type='checkbox' ".$TCPIPChecked."name='LogTypes[]' value='TCPIP' id='LogType_6'><label for='LogType_6'>TCP/IP</label>
			<input type='checkbox' ".$APPLICATIONChecked."name='LogTypes[]' value='APPLICATION' id='LogType_7'><label for='LogType_7'>Application</label>
			<input type='checkbox' ".$ERRORChecked."name='LogTypes[]' value='ERROR' id='LogType_8'><label for='LogType_8'>Errors</label>
			<input type='checkbox' ".$RAWChecked."name='LogType' value='RAW' id='checkRaw'><label for='checkRaw'>Unparsed (raw)</label>
			<br>Filter results by:
			<input type='hidden' name='logfile' value='".$logfile."'>
			<input type='text' name='LogFilter' value='' maxlength='50' class='small'>
			<input type='submit' value='Parse log'>
			<input type='submit' name='clear' value='Clear results'>
		</form>";
	}

	if (isset($LogType)) {$urlLogType = "&LogType=".$LogType;} else {$urlLogType = "";}
	echo "
		<br>
		<div style='border:1px solid black;border-radius:5px;padding:10px;'>
			<iframe id='my_iframe' src='./iframelogviewer.php?logfile=".$logfile."&LogTypes=".serialize($LogTypes).$urlLogType."&LogFilter=".$LogFilter."' style='width:100%;height:70vh;border:none;'></iframe>
		</div>
	</div>";

	include_once("foot.php");
?>