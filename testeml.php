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

	if (isset($_GET['msgid'])) {$msgid = trim($_GET['msgid']);} else {$msgid = "";}

	
	$maxid_sql = $pdo->prepare("SELECT MAX(id) FROM hm_log_msg;");
	$maxid_sql->execute();
	$maxid = $maxid_sql->fetchColumn();
	if ($msgid=="") {$msgid = $maxid;}
	if ($maxid==$msgid) {$max = "Last";} else {$max = "<a href='./testeml.php?msgid=".$maxid."'>Last</a>";}

	$minid_sql = $pdo->prepare("SELECT MIN(id) FROM hm_log_msg;");
	$minid_sql->execute();
	$minid = $minid_sql->fetchColumn();
	if ($minid==$msgid) {$min = "First";} else {$min = "<a href='./testeml.php?msgid=".$minid."'>First</a>";}

	$nextid_sql = $pdo->prepare("SELECT id FROM hm_log_msg WHERE id > ".$msgid." ORDER BY id ASC LIMIT 1;");
	$nextid_sql->execute();
	$nextid = $nextid_sql->fetchColumn();
	if ($nextid) {$next = "<a href='./testeml.php?msgid=".$nextid."'>Next</a>";} else {$next = "Next";}
	
	$lastid_sql = $pdo->prepare("SELECT id FROM hm_log_msg WHERE id < ".$msgid." ORDER BY id DESC LIMIT 1;");
	$lastid_sql->execute();
	$lastid = $lastid_sql->fetchColumn();
	if ($lastid) {$last = "<a href='./testeml.php?msgid=".$lastid."'>Previous</a>";} else {$last = "Previous";}

	echo "
		<!DOCTYPE html> 
		<html>
		<head>
		<title>MsgID: ".$msgid."</title>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<meta http-equiv='Content-Style-Type' content='text/css'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<style>
			.line {
				font-family: consolas;
				font-size: 0.8em;
				line-height: 5px;
			}
			.warning {
				font-family: consolas;
				font-size: 0.8em;
				background-color: yellow;
			}
			pre {
				font-family: Consolas, monospace;
				margin: 0;
				padding: 0;
			}
		</style>
		</head>
		<body>";

	function getMessageFilenameTest($msgid) {
		global $pdo;
		global $Database;
		global $TimeZone;
		global $msgSearchInterval;
		
		$idarr = array();

		$sql = $pdo->prepare("
			SELECT * FROM hm_log_msg a
			LEFT JOIN hm_log_attr b ON a.id = b.msgid
			WHERE a.id = ".$msgid.";");
		$sql->execute();
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$localTimestamp = $row['timestamp'];
			$utcTimestamp = ((new DateTime($row['timestamp'], new DateTimeZone($TimeZone)))->setTimezone(new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
			$envelopeFrom = $row['envelopeFrom'];
			$envelopeTo = $row['envelopeTo'];
			if (strlen($row['headerFrom'])>0) {
				preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/',$row['headerFrom'],$matches);
				if (isset($matches[0])){$headerFrom = $matches[0];}
			} else {
				$headerFrom = $row['envelopeFrom'];
			}
			$headerTo = $row['headerTo'];
			if (!strlen($row['headerTo'])>2) {
				preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/',$row['headerTo'],$matches);
				if (isset($matches[0])){
					$toaddr = $matches[0];
				} else {
					$toaddr = $row['envelopeTo'];
				}
			} else {
				$toaddr = $row['envelopeTo'];
			}
			if ($row['item']=='hMailServer ID') {
				if (!in_array($row['value'], $idarr, true)){
					array_push($idarr, $row['value']);
				}
			}
		}

		echo "<b>function getMessageFilenameTest(\$msgid)<br></b>";
		echo "<br><u>FILL VARIABLES</u><br>";
		echo "local timestamp: ".$localTimestamp."<br>";
		echo "utc timestamp: ".$utcTimestamp."<br>";
		echo "envelpe from: ".$envelopeFrom."<br>";
		echo "envelope to: ".$envelopeTo."<br>";
		echo "header from: ".htmlspecialchars($headerFrom)."<br>";
		echo "header to: ".htmlspecialchars($headerTo)."<br>";
		foreach ($idarr as $hMSmessageID) {echo "hmailserver message id: ".$hMSmessageID."<br>";}
		echo "<br><u>ATTEMPT TO GET FILENAME</u><br>";

		$fnarr = array();
	
		// First, try to get using message ID
		foreach ($idarr as $hMSmessageID) {
			$sqlID = $pdo->prepare("SELECT messagefilename FROM hm_messages WHERE messageid = ".$hMSmessageID.";");
			$sqlID->execute();
			while($row = $sqlID->fetch(PDO::FETCH_ASSOC)){
				if ($row['messagefilename']){
					echo "Filename found using message ID (1st try): ".$row['messagefilename']."<br>";
				}
				if (!in_array($row['messagefilename'], $fnarr, true)){
					array_push($fnarr, $row['messagefilename']);
				}
			}
		}
		// if (count($fnarr)>0) {
			// return $fnarr;
			// exit();
		// }

		// Next, try to find message using both envelopeFrom and envelopeTo
		$sqla = $pdo->prepare("
			SELECT * 
			FROM hm_message_metadata a
			JOIN hm_messages b ON a.metadata_messageid = b.messageid
			WHERE 
				(metadata_from REGEXP '".$headerFrom."' OR messagefrom = '".$envelopeFrom."') AND 
				(metadata_to REGEXP '".$toaddr."' OR metadata_cc REGEXP '".$envelopeTo."') AND 
				((metadata_dateutc < '".$utcTimestamp."' + INTERVAL ".$msgSearchInterval." SECOND AND metadata_dateutc > '".$utcTimestamp."' - INTERVAL ".$msgSearchInterval." SECOND) OR
				(messagecreatetime < '".$localTimestamp."' + INTERVAL ".$msgSearchInterval." SECOND AND messagecreatetime > '".$localTimestamp."' - INTERVAL ".$msgSearchInterval." SECOND));
		");
		$sqla->execute();
		while($row = $sqla->fetch(PDO::FETCH_ASSOC)){
			if ($row['messagefilename']){
				echo "Filename found using env add's (2nd try): ".$row['messagefilename']."<br>";
			}
			if (!in_array($row['messagefilename'], $fnarr, true)){
				array_push($fnarr, $row['messagefilename']);
			}
		}
		// if (count($fnarr)>0) {
			// return $fnarr;
			// exit();
		// }

		// Next, try to find message using both envelopeFrom and all recipients
		if ($headerTo) {
			$thx = explode(",",$headerTo);
			$thxarr = array();
			echo "Make array from TO header:";
			foreach ($thx as $thx_item) {
				preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/',$thx_item,$matches);
				if (isset($matches[0])){
					echo $matches[0]."<br>";
					if (!in_array($matches[0], $thxarr, true)){
						array_push($thxarr, $matches[0]);
					}
				}
			}
		}
		
		if (isset($thxarr)) {
			if (count($thxarr) > 0) {
				for ($i = 0; $i < count($thxarr); $i++) {
					echo "account to try: ".$thxarr[$i]."<br>";
					$sqla = $pdo->prepare("
						SELECT * 
						FROM hm_message_metadata a
						JOIN hm_messages b ON a.metadata_messageid = b.messageid
						WHERE 
							(metadata_from REGEXP '".$headerFrom."' OR messagefrom = '".$envelopeFrom."') AND 
							(metadata_to REGEXP '".$thxarr[$i]."' OR metadata_cc REGEXP '".$thxarr[$i]."') AND 
							((metadata_dateutc < '".$utcTimestamp."' + INTERVAL ".$msgSearchInterval." SECOND AND metadata_dateutc > '".$utcTimestamp."' - INTERVAL ".$msgSearchInterval." SECOND) OR
							(messagecreatetime < '".$localTimestamp."' + INTERVAL ".$msgSearchInterval." SECOND AND messagecreatetime > '".$localTimestamp."' - INTERVAL ".$msgSearchInterval." SECOND));
					");
					$sqla->execute();
					while($row = $sqla->fetch(PDO::FETCH_ASSOC)){
						if ($row['messagefilename']){
							echo "Filename found using header to array (3rd try): ".$row['messagefilename']."<br>";
						}
						if (!in_array($row['messagefilename'], $fnarr, true)){
							array_push($fnarr, $row['messagefilename']);
						}
					}
				}
			}
		}
		// if (count($fnarr)>0) {
			// return $fnarr;
			// exit();
		// }

		// Lastly, try the Hail Mary (envelopeFrom only)
		$sqla = $pdo->prepare("
			SELECT * 
			FROM hm_message_metadata a
			JOIN hm_messages b ON a.metadata_messageid = b.messageid
			WHERE 
				(metadata_from REGEXP '".$headerFrom."' OR messagefrom = '".$envelopeFrom."') AND 
				((metadata_dateutc < '".$utcTimestamp."' + INTERVAL ".$msgSearchInterval." SECOND AND metadata_dateutc > '".$utcTimestamp."' - INTERVAL ".$msgSearchInterval." SECOND) OR
				(messagecreatetime < '".$localTimestamp."' + INTERVAL ".$msgSearchInterval." SECOND AND messagecreatetime > '".$localTimestamp."' - INTERVAL ".$msgSearchInterval." SECOND));
		");
		$sqla->execute();
		while($row = $sqla->fetch(PDO::FETCH_ASSOC)){
			if ($row['messagefilename']){
				echo "Filename found using sender only (4th try): ".$row['messagefilename']."<br>";
			}
			if (!in_array($row['messagefilename'], $fnarr, true)){
				array_push($fnarr, $row['messagefilename']);
			}
		}
		if (count($fnarr)>0) {
			return $fnarr;
			exit();
		}
	}

	function getPlusAddressTest($email) {
		if ($email) {
			$hMS = hMSAuthenticate();
			$Domain = explode("@", $email)[1];
			try {
				$hMSDomain = $hMS->Domains->ItemByName($Domain);
				if ($hMSDomain->PlusAddressingEnabled) {
					if (strpos($email, $hMSDomain->PlusAddressingCharacter)) {
						$plusAddress = explode($hMSDomain->PlusAddressingCharacter,$email)[0]."@".$Domain;
						return $plusAddress;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} catch (Exception $ex) {
				return false;
				exit;
			}
		} else {
			return false;
		}
	}


	echo "<br>Msg ID: ".$msgid." | ".$min." | ".$last." | ".$next." | ".$max."<br><br><br>";


	$fnArray = getMessageFilenameTest($msgid);
	echo "<br><br><b>START SCRIPT<br></b>";
	echo "output of filename function on \$msgid: <br>";
	if (is_array($fnArray) && count($fnArray)>0) {foreach ($fnArray as $fn) {echo $fn."<br>";}} else {echo "<b>NO MESSAGE FOUND!</b>";}
	echo "<br><br>";

	$addressFail = false;

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

			if ($spamAccount) {
				if (!in_array($spamAccount, $addresses, true)){
					array_push($addresses, $spamAccount);
				}
			}

			echo "<u>BEGIN TESTING FILENAME AGAINST ADDRESSES</u><br>".$fn."<br><br>";
			for ($i = 0; $i < count($addresses); $i++) {
				echo "account to test: ".$addresses[$i]."<br>";
				if (isAccountLocal($addresses[$i])) {
					echo "account IS <b>LOCAL</b>: ".$addresses[$i]."<br>";
					$domain = explode("@", $addresses[$i])[1];
					$user = explode("@", $addresses[$i])[0];
					$filename = $dataFolder."\\".$domain."\\".$user."\\".$fn2c."\\".$fn;
					if (file_exists($filename)) {
						echo "MESSAGE FOUND IN ACCOUNT: ".$addresses[$i]."<br><br>";
						$rawEML = fopen($filename, "r");
						while(! feof($rawEML)) {
							$line = fgets($rawEML);
							echo "<pre><code>".htmlentities($line)."</code></pre>";
						}
						$addressFail = false;
						echo "
		</body>
		</html>";
						exit;
						// break;
					} else {
						echo "message not found in ".$addresses[$i]."<br><br>";
						$addressFail = true;
					}
				} else {
					echo "account is not local<br><br>";
					$addressFail = true;
				}
			}
		}
	} else {
		echo "no filename found from function - moving on to check spam account<br>";
		$addressFail = true;
	}


	if ($addressFail) {
		echo "<span class='warning'>Raw EML file not found. If incoming mail, it was likely deleted due to spam rules or by user. If outgoing mail, it was likely not saved to sent messages folder.</span>";
	}

	include_once("foot.php");

?>