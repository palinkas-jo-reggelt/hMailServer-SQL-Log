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

?>