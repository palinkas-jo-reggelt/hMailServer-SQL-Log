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

	if (isset($_GET['msgid'])) {$msgid = trim($_GET['msgid']);} else {$msgid = "";}

	$sql = $pdo->prepare("SELECT message FROM hm_log_msg WHERE id = ".$msgid.";");
	$sql->execute();

	function link_repair($matches) {return "link.php?link=".urlencode($matches[0]);}

	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo preg_replace_callback('/((?:https?:\/\/)[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~\%:\/?#[\]@!\$&\'\(\)\*\+,;=.]+)/',"link_repair",$row['message']);
	}
?>