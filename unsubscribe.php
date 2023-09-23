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

	if (isset($_GET['from'])) {$from = trim($_GET['from']);} else {$from = "";}
	if (isset($_GET['msgid'])) {$msgid = $_GET['msgid'];} else {$msgid = "";}
	if (isset($_GET['mailto'])) {$mailto = trim($_GET['mailto']);} else {$mailto = "";}

	if (count(explode("?",$mailto)) > 1) {
		$exp = explode("?",$mailto);
		$mailtoAddress = $exp[0];
		if (count(explode("&",$exp[1])) > 1) {
			if (preg_match("/^subject=/", explode("&",$exp[1])[0])) {$mailtoSubject = preg_replace("/subject=/","",explode("&",$exp[1])[0]);}
			if (preg_match("/^body=/", explode("&",$exp[1])[0])) {$mailtoBody = preg_replace("/body=/","",explode("&",$exp[1])[0]);}
			if (preg_match("/^subject=/", explode("&",$exp[1])[1])) {$mailtoSubject = preg_replace("/subject=/","",explode("&",$exp[1])[1]);}
			if (preg_match("/^body=/", explode("&",$exp[1])[1])) {$mailtoBody = preg_replace("/body=/","",explode("&",$exp[1])[1]);}
		} else {
			if (preg_match("/^subject=/", $exp[1])) {$mailtoSubject = preg_replace("/subject=/","",$exp[1]);}
			if (preg_match("/^body=/", $exp[1])) {$mailtoBody = preg_replace("/body=/","",$exp[1]);}
		}
	} else {
		$mailtoAddress = $mailto;
	}
	
	if (!isset($mailtoAddress)){$mailtoAddress="";}
	if (!isset($mailtoSubject)){$mailtoSubject="";}
	if (!isset($mailtoBody)){$mailtoBody="";}

	echo "
	<br><br>
	<div class='section'>
		<h3>Unsubscriber</h3>
		<span style='font-size:0.8em;'>The Unsubscriber will unsubscribe mailto: links scraped by the logger. A confirmation will be sent to the PostMaster.</span>
		<br><br>
		<div style='border:1px solid black;border-radius:5px;padding:10px;'>
			<div class='secmsgleft'><b>Unsubscribee address:</b></div><div class='secmsgright'>".$from."</div><div class='clear'></div>
			<div class='secmsgleft'><b>Message ID:</b></div><div class='secmsgright'><a href='./msg.php?msgid=".$msgid."'>".$msgid."</a></div><div class='clear'></div>
			<div class='secmsgleft'><b>Unsubscribe mailto address:</b></div><div class='secmsgright'>".$mailtoAddress."</div><div class='clear'></div>";
	if ($mailtoSubject) {
	echo "
			<div class='secmsgleft'><b>Unsubscribe subject:</b></div><div class='secmsgright'>".$mailtoSubject."</div><div class='clear'></div>";
	}
	if ($mailtoBody) {
	echo "
			<div class='secmsgleft'><b>Unsubscribe body:</b></div><div class='secmsgright'><div style='padding:20px;border:1px solid black;border-radius:10px;'>".$mailtoBody."</div></div><div class='clear'></div>";
	}
	echo "
		</div>
		<br><br>";

	if (isAccountLocal($from)) {
		if (!isset($_GET['submit'])) {
			echo "
		Do you want unsubscribe ".$from."? 
		<form autocomplete='off' action='unsubscribe.php' method='GET'><br>
			<input type='hidden' name='from' value='".$from."'>
			<input type='hidden' name='msgid' value='".$msgid."'>
			<input type='hidden' name='mailto' value='".$mailto."'>
			<button class='button' type='submit' name='submit'>Send Unsubscribe</button>
		</form>";
		} else {
			$unsub = sendUnsubscribeMessage($from, $mailtoAddress, $mailtoSubject, $mailtoBody, $msgid);
			if ($unsub) {
				echo "
		<b>Unsubscribe message successfully sent</b>";
			} else {
				echo "
		<b>ERROR sending unsubscribe message. Use Github issues for help.</b>";
			}
		}
	} else {
		echo "<b>Unsubscribee address NOT a local account! Cannot be unsubscribed!</b>";
	}
	
	echo "
	</div>";
	
	include_once("foot.php");
?>