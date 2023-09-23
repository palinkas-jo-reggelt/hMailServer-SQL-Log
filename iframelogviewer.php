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
	include_once("functions_log.php");

	if (!empty($_GET['logfile'])) {$logfile = trim($_GET['logfile']);} else {$logfile = "";}
	if (!empty($_GET['LogTypes'])) {$Types = unserialize($_GET['LogTypes']);} else {$Types = array('SMTP');}
	if (!empty($_GET['LogType'])) {$RawType = true;} else {$RawType = false;}
	if (!empty($_GET['LogFilter'])) {$Filter = trim($_GET['LogFilter']);} else {$Filter = null;}

	// echo "<br><br>".$LogTypes."<br><br>";


	$hMS = hMSAuthenticate();
	$Path = $hMS->Settings->Directories->LogDirectory;
	$Filename = $Path.'\\'.$logfile;

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

	if ((preg_match("/^hmailserver_\d.*\.log$/",$logfile)) && ($RawType)) {
		if (file_exists($Filename)) {
			$rawFile = fopen($Filename, "r");
			while(! feof($rawFile)) {
				$line = fgets($rawFile);
				$line = cleanString($line);
				$line = cleanNonUTF8($line);
				echo "<div class='logline'>".$line."</div>";
			}
		}
	} elseif ((preg_match("/^hmailserver_\d.*\.log$/",$logfile)) && (!$RawType)) {

		$AllTypes = in_array('ALL', $Types);

		if (file_exists($Filename)) {
			$Filesize = filesize($Filename);
			$File = fopen($Filename, 'r');

			if ($File) {
				while (($Line = fgets($File)) !== false) {
					$Unfiltered = $Line;
					$Filtered = $AllTypes ? $Unfiltered : filter_result_type($Unfiltered, $Types);
					if (!is_null($Filter)) {
						$Filtered = filter_result($Filtered, $Filter, false);
						$Filtered = preg_replace("/\w*?$Filter\w*/i", "{em}$0{/em}", $Filtered);
					}

					if (!is_null($Filtered)) parse($Filtered);
				}
				fclose($File);
				$out = events();
			} else {
				$out = "Error opening log file";
			}
		} else {
			$out = "Log file not found";
		}

		// header('Content-Type: application/json');
		// print_r($out);

		if (is_array($out) || is_object($out)) {
			foreach ($out as $header) {
				echo "
	<div class='logGroupContainer'>
		<div class='logGroupHeader'>".implode(" - ",$header[0])."</div>
		<div class='logGroupDataContainer'>";
				foreach ($header[1] as $arrdata) {
					if (preg_match("/SMTPD|SMTPC|POP3D|POP3C|IMAPD/",$header[0][0])) {
						if (preg_match("/SENT/",$arrdata[1])) {$color = "rgba(255, 255, 0, .25) !important";} elseif (preg_match("/RECEIVED/",$arrdata[1])) {$color = "rgba(0, 255, 0, .25) !important";} else {$color = "none";} 
						echo "
			<div style='background:".$color.";'>
				<div class='logGroupDataDate'>".$arrdata[0]."</div>
				<div class='logGroupDataOutput'>
					<div style='float:left;width:65px;'>
						<div style='float:left;'>".explode(": ",$arrdata[1])[0]."</div>
						<div style='float:right;'>:</div>
						<div class='clear'></div>
					</div>
					<div style='margin-left:70px;'>".explode(": ",$arrdata[1])[1]."</div>
					<div class='clear'></div>
				</div>
			</div>";
					} else {
						echo "
			<div class='logGroupDataDate'>".$arrdata[0]."</div>
			<div class='logGroupDataOutput'>".$arrdata[1]."</div>";
					}
					echo "
			<div class='clear'></div>";
				}
			echo "
		</div>
	</div>";
			}
		} else {
			echo "No results found";
		}

	} elseif (preg_match("/^WinDefAntiVirus.log$/",$logfile)) {

		if (file_exists($Filename)) {
			$Filesize = filesize($Filename);
			$File = fopen($Filename, 'r');

			if ($File) {
				while (($Line = fgets($File)) !== false) {
					parse_windef($Line);
				}
				fclose($File);
				$out = events();
			} else {
				$out = "Error opening log file";
			}
		} else {
			$out = "Log file not found";
		}

		if (is_array($out) || is_object($out)) {
			foreach (array_reverse($out) as $header) {
				echo "
	<div class='logGroupContainer'>
		<div class='logGroupHeader'>".$header[0][0]."</div>
		<div class='logGroupDataContainer'>";
				foreach ($header[1] as $arrdata) {
					$notice = $arrdata[1];
					$notice = preg_replace("/\[CLEAN\]/","<span style='background-color: rgba(0, 128, 0, 0.15);'>$0</span>",$notice);
					$notice = preg_replace("/\[VIRUS\]/","<span style='background-color: rgba(255, 0, 0, 0.15);'>$0</span>",$notice);
					$notice = preg_replace("/\[(NOFND|ERROR|FLOCK)\]/","<span style='background-color: rgba(255, 255, 0, 0.15);'>$0</span>",$notice);
					echo "
			<div class='logGroupDataDate'>".$arrdata[0]."</div>
			<div class='logGroupDataOutput'>".$notice." ".$arrdata[2]." ".$arrdata[3]." ".$arrdata[4]." ".$arrdata[5]."</div>
			<div class='clear'></div>";
				}
			echo "
		</div>
	</div>";
			}
		} else {
			echo "No results found";
		}


	} else {

		if (file_exists($Filename)) {
			$rawFile = fopen($Filename, "r");
			while(! feof($rawFile)) {
				$line = fgets($rawFile);
				$line = cleanString($line);
				$line = cleanNonUTF8($line);
			
				$line = preg_replace("/(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d{3})/","<b>$0</b>",$line);
				$line = preg_replace("/\[CLEAN\]/","<span style='background-color: rgba(0, 128, 0, 0.15);'>$0</span>",$line);
				$line = preg_replace("/\[VIRUS\]/","<span style='background-color: rgba(255, 0, 0, 0.15);'>$0</span>",$line);
				$line = preg_replace("/\[(NOFND|ERROR|FLOCK)\]/","<span style='background-color: rgba(255, 255, 0, 0.15);'>$0</span>",$line);

				echo "<div class='logline'>".$line."</div>";
			}
		}
	}

	echo "
</body>
</html>";

?>