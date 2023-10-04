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

	echo "
	<div class='section'>
		<h3>Extra Items</h3>
		<div style='border:1px solid black;border-radius:5px;padding:10px;'>";

	// Autoban
	echo "
			<div class='secmsgleft'><b>Autoban:</b></div>
			<div class='secmsgright'><a href='./autoban.php'>View or delete autoban entries</a></div>
			<div class='clear'></div>";

	// Unread Messages Count
	echo "
			<div class='secmsgleft'><b>Unread:</b></div>
			<div class='secmsgright'><a href='./unread.php'>Unread message count by user</a></div>
			<div class='clear'></div>";

	$hMS = hMSAuthenticate();
	$logFolder = $hMS->Settings->Directories->LogDirectory;

	$results_array = array();
	if (is_dir($logFolder)) {
		if ($handle = opendir($logFolder)) {
			while(($file = readdir($handle)) !== FALSE) {
				$results_array[] = $file;
			}
			closedir($handle);
		}
	}

	// Log Search
	echo "
			<div class='secmsgleft'><b>hMailserver Log Search:</b></div>
			<div class='secmsgright'><a href='./logsearch.php'>Search Logs</a><br></div>
			<div class='clear'></div>";

	// WinDefAntiVirus Log
	echo "
			<div class='secmsgleft'><b>hMailserver WinDef Scanner Logs:</b></div>
			<div class='secmsgright'>";
	foreach($results_array as $value) {
		if (preg_match("/^WinDefAntiVirus.log/",$value)) {
			echo "<a href='./logviewer.php?logfile=".$value."'>".$value."</a><br>";
		}
	}
	echo "
			</div>
			<div class='clear'></div>";

	// hMailServer Service Logs
	echo "
			<div class='secmsgleft'><b>hMailserver Service Logs:</b></div>
			<div class='secmsgright'>";
	foreach($results_array as $value) {
		if (preg_match("/^hmailserver_\d.+\.log/",$value)) {
			echo "<a href='./logviewer.php?logfile=".$value."'>".$value."</a><br>";
		}
	}
	echo "
			</div>
			<div class='clear'></div>";

	// hMailServer Error Logs
	echo "
			<div class='secmsgleft'><b>hMailserver Error Logs:</b></div>
			<div class='secmsgright'>";
	foreach($results_array as $value) {
		if (preg_match("/^ERROR_hmailserver_\d.+\.log/",$value)) {
			echo "<a href='./logviewer.php?logfile=".$value."'>".$value."</a><br>";
		}
	}
	echo "
			</div>
			<div class='clear'></div>";

	// Event Logs
	echo "
			<div class='secmsgleft'><b>hMailserver Event Logs:</b></div>
			<div class='secmsgright'>";
	foreach($results_array as $value) {
		if (preg_match("/^hmailserver_events.*\.log/",$value)) {
			echo "<a href='./logviewer.php?logfile=".$value."'>".$value."</a><br>";
		}
	}
	echo "
			</div>
			<div class='clear'></div>";

	// SpamD Logs
	echo "
			<div class='secmsgleft'><b>Spamassassin spamd Logs:</b></div>
			<div class='secmsgright'>";
	foreach($results_array as $value) {
		if (preg_match("/^spamd.*\.log/",$value)) {
			echo "<a href='./logviewer.php?logfile=".$value."'>".$value."</a><br>";
		}
	}
	echo "
			</div>
			<div class='clear'></div>";

	echo "
		</div>
	</div>";

	include_once("foot.php");
?>