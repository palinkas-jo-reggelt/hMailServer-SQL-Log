<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	include_once("./config.php");
	include_once("./functions.php");

	if (isset($_GET['msgid'])) {$msgid = trim($_GET['msgid']);} else {$msgid = "";}

	echo "
<!DOCTYPE html> 
<html>
<head>
<title>MsgID: ".$msgid."</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='stylesheet' href='./css/jquery-ui.smoothness.css'>
<script src='./js/jquery.min.js'></script>
<script src='./js/jquery-ui.min.js'></script>
<link rel='stylesheet' type='text/css' media='all' href='./css/iframe.css'>
</head>
<body>";

	$fnArray = getMessageFilename($msgid);
	$addressFail = false;
	
	$hMS = hMSAuthenticate();
	$logFolder = $hMS->Settings->Directories->DataDirectory;

	if (is_array($fnArray) && count($fnArray)>0) {
		foreach ($fnArray as $fn) {
			preg_match('/(?<={)(\w{2})/',$fn,$matches);
			$fn2c = $matches[0];
			$addresses=array();
			$usersql = $pdo->prepare("
				SELECT * FROM hm_log_msg a
				LEFT JOIN hm_log_attr b ON a.id = b.msgid
				WHERE a.id = ".$msgid.";
			");
			$usersql->execute();
			while($row = $usersql->fetch(PDO::FETCH_ASSOC)){
				if (!in_array($row['envelopeFrom'], $addresses, true)){
					array_push($addresses, $row['envelopeFrom']);
				}
				if (!in_array($row['envelopeTo'], $addresses, true)){
					array_push($addresses, $envelopeTo = $row['envelopeTo']);
				}
				$plusAddress = getPlusAddress($row['envelopeTo']);
				if ($plusAddress) {
					if (!in_array($plusAddress, $addresses, true)){
						array_push($addresses, $plusAddress);
					}
				}
				if (preg_match("/envelopeTo /",$row['item'])) {
					if (!in_array($row['value'], $addresses, true)){
						array_push($addresses, $row['value']);
					}
				}
				if ($row['headerTo']) {
					$thx = explode(",",$row['headerTo']);
					$thxarr = array();
					foreach ($thx as $thx_item) {
						preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/',$thx_item,$matches);
						if (isset($matches[0])){
							if (!in_array($matches[0], $addresses, true)){
								array_push($addresses, $matches[0]);
							}
						}
					}
				}
			}

			if(isset($envelopeTo)) {
				$forwardAddress = getForwardingAddress($envelopeTo);
				if ($forwardAddress) {
					if (!in_array($forwardAddress, $addresses, true)){
						array_push($addresses, $forwardAddress);
					}
				}
			}

			if (isset($spamAccount)) {
				if (!in_array($spamAccount, $addresses, true)){
					array_push($addresses, $spamAccount);
				}
			}
		
			// Loop through address array and see if they're local; if yes, try to find the message file
			for ($i = 0; $i < count($addresses); $i++) {
				if (isAccountLocal($addresses[$i])) {
					$domain = explode("@", $addresses[$i])[1];
					$user = explode("@", $addresses[$i])[0];
					$filename = $dataFolder."\\".$domain."\\".$user."\\".$fn2c."\\".$fn;
					if (file_exists($filename)) {

						$flag_sql = $pdo->prepare("SELECT messageflags FROM hm_messages WHERE messagefilename = '".$fn."';");
						$flag_sql->execute();
						$msgFlag = $flag_sql->fetchColumn();

						echo "
	<div class='pagehead'>
		".$filename."<br><br>";
						if ($msgFlag){$mfnote = implode(" + ",get_bits($msgFlag));} else {$mfnote = "No Flags";}
						echo "
		Message Flags: ".$mfnote."<br><br>";
						if ($useSpamassassin) {
							echo "
		Feed this message to Spamassassin for bayes training -OR- to test spam score:<br><br>
		<a href='./iframebayes.php?fn=".urlencode($filename)."&msgid=".$msgid."'><button id='train' class='button'>Train</button></a> 
		<a href='./iframechecksa.php?fn=".urlencode($filename)."'><button id='test' class='button'>Test</button></a>
		<!--a href='./iframemovetospam.php?fn=".urlencode($filename)."'><button id='movetospam' class='button'>Move to Spam</button></a-->";
						}
						echo "
	</div><br>";
						$rawEML = fopen($filename, "r");
						while(! feof($rawEML)) {
							$line = fgets($rawEML);
							echo "<pre><code>".htmlentities($line)."</code></pre>";
						}
						$addressFail = false;
						echo "
	<script>
		$(document).ready(function() {
			$(\"#train\").click(function() {
			  $(this).html(
				`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Loading...</div>`
			  );
			});
		});
		$(document).ready(function() {
			$(\"#test\").click(function() {
			  $(this).html(
				`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Preparing Report...</div>`
			  );
			});
		});
		$(document).ready(function() {
			$(\"#movetospam\").click(function() {
			  $(this).html(
				`<div class='spinleft'><span class='button--loading'></span></div> <div class='spinright'>Loading...</div>`
			  );
			});
		});
	</script>
</body>
</html>";
						exit;
						// break;
					} else {
						$addressFail = true;
					}
				} else {
					$addressFail = true;
				}
			}
		}
	} else {
		$addressFail = true;
	}


	if ($addressFail) {
		echo "
			<span class='warning'>Raw EML file not found. It was likely deleted by spam rules or expunged by user or was not saved to sent messages folder.</span>";
	}

	echo "
</body>
</html>";
		

?>