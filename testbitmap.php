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

	if (isset($_GET['fn'])) {$fn = trim($_GET['fn']);} else {$fn = "";}
	if (isset($_REQUEST['spam'])) {$spam = $_REQUEST['spam'];} else {$spam = null;}
	if (isset($_REQUEST['ham'])) {$ham = $_REQUEST['ham'];} else {$ham = null;}

	echo "
<!DOCTYPE html> 
<html>
<head>
<title>hMailServer SQL Log</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<style>
	body {
		font-family: consolas;
		font-size: 0.8em;
	}
</style>
</head>
<body>";



function getFlag($flagValue){
	switch ($flagValue) {
		case 1:  return "Seen"; break;
		case 2:  return "Deleted"; break;
		case 4:  return "Flagged"; break;
		case 8:  return "Answered"; break;
		case 16: return "Draft"; break;
		case 32: return "Recent"; break;
		case 64: return "VirusScan"; break;
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

// Get individual result
$flagValue = 36;
echo implode(" + ",get_bits($flagValue))."<br><br>";

// Get all results
// for ($i=1; $i<128; $i++) {
	// echo $i." >> ".implode(" + ",get_bits($i))."<br>";
// }










	echo "
</body>
</html>";

?>