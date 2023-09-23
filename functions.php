<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	if ($Database['driver'] == 'mysql') {
		$pdo = new PDO("mysql:host=".$Database['host'].";port=".$Database['port'].";dbname=".$Database['dbname'], $Database['username'], $Database['password']);
	} elseif ($Database['driver'] == 'odbc') {
		$pdo = new PDO("odbc:Driver={".$Database['dsn']."};Server=".$Database['host'].";Port=".$Database['port'].";Database=".$Database['dbname'].";User=".$Database['username'].";Password=".$Database['password'].";");
	} else {
		echo "Configuration Error - No database driver specified";
	}

	// Regex for IPv4 + IPv6
	$regexIP = "/(((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3})|((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))))/";

	function hMSAuthenticate(){
		global $hMSAdminPass;
		$hMS = new COM("hMailServer.Application");
		$hMS->Authenticate("Administrator", $hMSAdminPass);
		return $hMS;
	}

	function isAccountLocal($email){
		if ($email) {
			$hMS = hMSAuthenticate();
			$Domain = explode("@", $email)[1];
			try {
				$hMSDomain = $hMS->Domains->ItemByName($Domain);
				$hMSAccount = $hMSDomain->Accounts->ItemByAddress($email);
				return true;
			} catch (Exception $ex) {
				return false;
				exit;
			}
		} else {
			return false;
		}
	}

	function getForwardingAddress($email){
		if ($email) {
			$hMS = hMSAuthenticate();
			$Domain = explode("@", $email)[1];
			try {
				$hMSDomain = $hMS->Domains->ItemByName($Domain);
				$hMSAccount = $hMSDomain->Accounts->ItemByAddress($email);
				if ($hMSAccount->ForwardEnabled){
					$forwardAddress = $hMSAccount->ForwardAddress;
				}
				if (isset($forwardAddress)) {
					if ($hMSDomain->PlusAddressingEnabled) {
						if (strpos($forwardAddress, $hMSDomain->PlusAddressingCharacter)) {
							$paForwardingAddress = explode($hMSDomain->PlusAddressingCharacter,$forwardAddress)[0]."@".$Domain;
							return $paForwardingAddress;
						} else {
							return $forwardAddress;
						}
					} else {
						return $forwardAddress;
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

	function getPlusAddress($email) {
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

	function getMessageFilename($msgid) {
		global $pdo;
		global $Database;
		global $TimeZone;
		global $msgSearchInterval;

		$idarr = array();

		// Fill variables needed to search for message
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
				if (isset($matches[0])){
					$headerFrom = $matches[0];
				} else {
					$headerFrom = $row['envelopeFrom'];
				}
			} else {
				$headerFrom = $row['envelopeFrom'];
			}
			$headerTo = $row['headerTo'];
			if (!strlen($row['headerTo'])>0) {
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

		$fnarr = array();

		// First, try to find message from messageID
		foreach ($idarr as $hMSmessageID) {
			$sqlID = $pdo->prepare("SELECT messagefilename FROM hm_messages WHERE messageid = ".$hMSmessageID.";");
			$sqlID->execute();
			while($row = $sqlID->fetch(PDO::FETCH_ASSOC)){
				if (!in_array($row['messagefilename'], $fnarr, true)){
					array_push($fnarr, $row['messagefilename']);
				}
			}
		}
		if (count($fnarr)>0) {
			return $fnarr;
			exit();
		}

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
			if (!in_array($row['messagefilename'], $fnarr, true)){
				array_push($fnarr, $row['messagefilename']);
			}
		}
		if (count($fnarr)>0) {
			return $fnarr;
			exit();
		}

		// Next, try to find message using both envelopeFrom and all recipients
		if ($headerTo) {
			$thx = explode(",",$headerTo);
			$thxarr = array();
			foreach ($thx as $thx_item) {
				preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/',$thx_item,$matches);
				if (isset($matches[0])){
					if (!in_array($matches[0], $thxarr, true)){
						array_push($thxarr, $matches[0]);
					}
				}
			}
		}
		
		if (isset($thxarr)) {
			if (count($thxarr) > 0) {
				for ($i = 0; $i < count($thxarr); $i++) {
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
						if (!in_array($row['messagefilename'], $fnarr, true)){
							array_push($fnarr, $row['messagefilename']);
						}
					}
				}
			}
		}
		if (count($fnarr)>0) {
			return $fnarr;
			exit();
		}

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
			if (!in_array($row['messagefilename'], $fnarr, true)){
				array_push($fnarr, $row['messagefilename']);
			}
		}
		if (count($fnarr)>0) {
			return $fnarr;
			exit();
		}
	}

	function getMessageFilenameSpam($msgID) {
		global $pdo;
		global $Database;
		$sql = $pdo->prepare("SELECT * FROM hm_log_msg WHERE id = ".$msgID.";");
		$sql->execute();
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$timestamp = $row['timestamp'];
			$envelopeFrom = $row['envelopeFrom'];
		}
		$sqla = $pdo->prepare("
			SELECT * 
			FROM hm_messages
			WHERE messagefrom = '".$envelopeFrom."' AND messagecreatetime < '".$timestamp."' + INTERVAL 30 SECOND AND messagecreatetime > '".$timestamp."' - INTERVAL 30 SECOND;
		");
		$sqla->execute();
		while($row = $sqla->fetch(PDO::FETCH_ASSOC)){
			return $row['messagefilename'];
		}
	}

	function getStatusCodeDescription($statusCode){
		switch ($statusCode) {
			case "250":
				return "250 Requested mail action okay; completed";
				break;
			case "251":
				return "251 User not local; will forward";
				break;
			case "530":
				return "530 Authentication required";
				break;
			case "550":
				return "550 Requested action not taken: mailbox unavailable";
				break;
			case "551":
				return "551 User not local";
				break;
			case "554":
				return "554 Transaction has failed";
				break;
			case "600":
				return "600 Status Code Unknown: Error in recording status code into database";
				break;
			default:
			return $statusCode;
		}		
	}

	function sendUnsubscribeMessage($from, $mailtoAddress, $mailtoSubject, $mailtoBody, $msgid){
		global $postMaster;
		$hMS = hMSAuthenticate();
		try {
			$hMSmsg = new COM("hMailServer.Message");
			$hMSmsg->From = $from;
			$hMSmsg->FromAddress = $from;
			$hMSmsg->AddRecipient($mailtoAddress, $mailtoAddress);
			$hMSmsg->Subject = $mailtoSubject;
			$hMSmsg->Body = $mailtoBody;
			$hMSmsg->Save();
			
			$hMSmsgPM = new COM("hMailServer.Message");
			$hMSmsgPM->From = $from;
			$hMSmsgPM->FromAddress = $from;
			$hMSmsgPM->AddRecipient($postMaster, $postMaster);
			$hMSmsgPM->Subject = "Unsubscribe from SQL Log";
			$hMSmsgPM->Body = $from." unsubscribed on message ID: ".$msgid."\n\nMessage: https://log.wap.dynu.net/msg.php?msgid=".$msgid."\nTo: ".$mailtoAddress."\nSubject: ".$mailtoSubject."\nBody:\n".$mailtoBody;
			$hMSmsgPM->Save();
			
			return true;
		} catch (Exception $ex) {
			return false;
		}
	}

	function redirect($url) {
		if (!headers_sent()) {    
			header('Location: '.$url);
			exit;
		} else {
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>'; exit;
		}
	}

	function getGeoIP($ip) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.minetools.eu/ping/play.desnia.net/25565",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

	}

	function linear_regression( $x, $y ) {
		// https://halfelf.org/2017/linear-regressions-php/
		$n     = count($x);     // number of items in the array
		$x_sum = array_sum($x); // sum of all X values
		$y_sum = array_sum($y); // sum of all Y values
		$xx_sum = 0;
		$xy_sum = 0;
		for($i = 0; $i < $n; $i++) {
			$xy_sum += ($x[$i] * $y[$i]);
			$xx_sum += ($x[$i] * $x[$i]);
		}
		$slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
		$intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;
		return array( 
			'slope'     => $slope,
			'intercept' => $intercept,
		);
	}

	function getFlag($flagValue){
		switch ($flagValue) {
			case 1:  return "<span style='background-color: rgba(0, 255, 0, 0.15);'>&nbsp;Seen&nbsp;</span>"; break;
			case 2:  return "<span style='background-color: rgba(255, 0, 0, 0.15);'>&nbsp;Deleted&nbsp;</span>"; break;
			case 4:  return "<span style='background-color: rgba(0, 0, 255, 0.15);'>&nbsp;Flagged&nbsp;</span>"; break;
			case 8:  return "<span style='background-color: rgba(255, 150, 0, 0.15);'>&nbsp;Answered&nbsp;</span>"; break;
			case 16: return "<span style='background-color: rgba(255, 255, 0, 0.15);'>&nbsp;Draft&nbsp;</span>"; break;
			case 32: return "<span style='background-color: rgba(0, 150, 255, 0.15);'>&nbsp;Recent&nbsp;</span>"; break;
			case 64: return "<span style='background-color: rgba(102, 51, 0, 0.15);'>&nbsp;VirusScan&nbsp;</span>"; break;
			default: return "NoFlag";
		}		
	}

	function get_bits($decimal) {
		$scan = 1;
		$flags = array();
		$result = array();
		while ($decimal >= $scan){
			if ($decimal & $scan) $flags[] = $scan;
			$scan<<=1; 
		}
		foreach ($flags as $flag) {
			$result[] = getFlag($flag);
		}
		return $result;
	}

	// https://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes#2510459
	function formatBytes($size, $precision = 1) {
		$base = log($size, 1024);
		$suffixes = array('Bytes', 'KB', 'MB', 'GB', 'TB');   
		return round(pow(1024, $base - floor($base)), $precision).' '.$suffixes[floor($base)];
	}
?>
