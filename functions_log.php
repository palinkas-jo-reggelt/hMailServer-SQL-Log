<?php

function filter_result($str, $findme, $type=true) {
	if ( ($pos = stripos($str, $findme)) !== false && (!$type || $pos < 3)) {
		return $str;
	}
}

function filter_result_type($str, $types) {
	foreach ($types as $v) {
		if (!is_null($result = filter_result($str, $v, true)))
			return $result;
	}
}

$events = array();
$datastore = array();

function parse($line){
	global $events;
	$line = cleanString($line);
	$line = cleanNonUTF8($line);
	$data = explode("\t", $line);
	switch($data[0]){
		case 'SMTPD':
		case 'SMTPC':
			parse_smtp($data);
			break;
		case 'POP3D':
		case 'POP3C':
		case 'IMAPD':
			parse_imap($data);
			break;
		case 'TCPIP':
		case 'DEBUG':
		case 'APPLICATION':
		case 'ERROR':
			parse_error($data);
	}
}

function parse_smtp($data){
	global $datastore,$events;

	if (!isset($events[$data[0] . $data[2]])) {
		$events[$data[0] . $data[2]][0] = array($data[0], $data[2], "<a href='https://geoip.dynu.net/map/".$data[4]."' target='_blank'>".$data[4]."</a>");
	}

	// AUTH LOGIN decoder.
	// First we get a SENT: 334 VXNlcm5hbWU6 RECEIVED: AUTH LOGIN
	// The next RECEIVED: line contains login username which is e-mail address, base64 encoded.

	if (isset($datastore[$data[0] . $data[2]]) && strpos($data[5],'RECEIVED: ') !== false) {
		// We got it.
		$base64 = substr($data[5], strrpos($data[5], ' ') + 1, strlen($data[5]));
		$data[5] = 'RECEIVED: <b>' . base64_decode($base64) . '</b>';
		unset($datastore[$data[0] . $data[2]]);
	} else if (strpos($data[5], 'RECEIVED: AUTH LOGIN ') !== false && strlen($data[5]) > 21) {
		// Got singel line AUTH LOGIN?
		$base64 = substr($data[5], strrpos($data[5], ' ') + 1, strlen($data[5]));
		$data[5] = substr($data[5], 0, strrpos($data[5], ' ') + 1) .' <b>' . base64_decode($base64) . '</b>';
	} else if (strpos($data[5], 'SENT: 334 VXNlcm5hbWU6') !== false) {
		// Wait for it.
		$datastore[$data[0] . $data[2]] = true;
	}

	$events[$data[0] . $data[2]][1][] = array($data[3], $data[5]);
}

function parse_imap($data){
	global $events;

	if (!isset($events[$data[0] . $data[2]])) {
		$events[$data[0] . $data[2]][0] = array($data[0], $data[2], "<a href='https://geoip.dynu.net/map/".$data[4]."' target='_blank'>".$data[4]."</a>");
	}

	$events[$data[0] . $data[2]][1][] = array($data[3], $data[5]);
}

function parse_error($data){
	global $events;

	if (!isset($events[$data[0] . $data[2]])) {
		$events[$data[0] . $data[2]][0] = array($data[0]);
	}

	$events[$data[0] . $data[2]][1][] = array($data[2], $data[3]);
}

function parse_windef($line){
	global $events;
	$line = cleanString($line);
	$line = cleanNonUTF8($line);
	$data = explode(" : ", $line);

	if (!isset($events[$data[2]])) {
		$events[$data[2]][0] = array($data[2]);
	}

	if (isset($data[4])) {$data4 = " : ".$data[4];} else {$data4 = "";}
	if (isset($data[5])) {$data5 = " : ".$data[5];} else {$data5 = "";}
	if (isset($data[6])) {$data6 = " : ".$data[6];} else {$data6 = "";}

	$events[$data[2]][1][] = array($data[0], $data[1]." : ", $data[3], $data4, $data5, $data6);
}

function events(){
	global $events;
	$out = array();
	foreach ($events as $data) {
		$out[] = $data;
	}
	if (empty($out)) $out = "No matched entries in the log file";
	return $out;
}

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

	// https://www.php.net/manual/en/function.mb-detect-encoding.php#91051
	// Unicode BOM is U+FEFF, but after encoded, it will look like this.
	define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
	define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
	define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
	define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
	define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));

	function detect_utf_encoding($fileName) {
		$text = file_get_contents($fileName);
		$first2 = substr($text, 0, 2);
		$first3 = substr($text, 0, 3);
		$first4 = substr($text, 0, 3);

		if ($first3 == UTF8_BOM) return 'UTF-8';
		elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
		elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
		elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
		elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
	}
?>