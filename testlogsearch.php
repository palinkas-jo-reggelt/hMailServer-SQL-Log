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
	// include_once("functions.php");
	// include_once("functions_log.php");

	if (!empty($_GET['search'])) {$search = trim($_GET['search']);} else {$search = "";}

	function cleanString($str) {
		$search = array("\r\n", "'", '"', '<', '>', '[nl]', '{em}', '{/em}','\n');
		$replace = array('', '', '', '&lt;', '&gt;', '<br>', '<em>', '</em>','<br>');
		return str_replace($search, $replace, $str);
	}

	function cleanNonUTF8($str) {
		$regex = <<<'END'
	/
	  (
		(?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
		|   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
		|   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
		|   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
		){1,100}                        # ...one or more times
	  )
	| .                                 # anything else
	/x
	END;
		return preg_replace($regex, '$1', $str);
	}

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

	if (isset($search)) {
		$hMS = new COM("hMailServer.Application");
		$hMS->Authenticate("Administrator", $hMSAdminPass);
		$logFolder = $hMS->Settings->Directories->LogDirectory;

		$logfile_array = array();
		if (is_dir($logFolder)) {
			if ($handle = opendir($logFolder)) {
				while(($file = readdir($handle)) !== FALSE) {
					if (!preg_match("/^.$|^..$/",$file)) {
						$logfile_array[] = $file;
					}
				}
				closedir($handle);
			}
		}

		$lineIterator = 1;

		foreach ($logfile_array as $logFile) {

			// echo $logFile."<br>";
			
			$fileName = $logFolder.'\\'.$logFile;
			// echo $fileName."<br>";

			$logFileIterator = 0;
			$results = array();

			if (file_exists($fileName)) {
				$fileSize = filesize($fileName);
				$file = fopen($fileName, 'r');

				if ($file) {
					while (($line = fgets($file)) !== false) {
						$line = cleanString($line);
						$line = cleanNonUTF8($line);
						if (preg_match("/{$search}/",$line)) {
							$line = preg_replace("/\w*?$search\w*/i", "<span style='background:yellow;font-weight:bold;'>$0</span>", $line);
							$results[] = "<div class='logline'><div style='float:left'>".number_format($lineIterator).".</div><div style='margin-left:35px;'>".$line."</div></div>";
							$lineIterator++;
							$logFileIterator++;
						}
					}
					fclose($file);
				} else {
					echo "Error opening log file: ".$logFile."<br>";
				}
			} else {
				echo "Log file not found: ".$logFile."<br>";
			}
			
			if ($logFileIterator > 0) {
				echo "<div class='logGroupHeader'>".$logFile." : ".$logFileIterator." Results</div>";
				foreach ($results as $result) {
					echo $result;
				}
				echo "<br>";
			}

		}
	}


	echo "
</body>
</html>";





?>