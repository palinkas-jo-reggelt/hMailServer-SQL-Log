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
	
	$nextid_sql = $pdo->prepare("SELECT id FROM hm_log_msg WHERE id > ".$msgid." ORDER BY id ASC LIMIT 1;");
	$nextid_sql->execute();
	$nextid = $nextid_sql->fetchColumn();
	if ($nextid) {$next = "<a href='./msg.php?msgid=".$nextid."'>Next Message</a>";} else {$next = "Next Message";}
	
	$lastid_sql = $pdo->prepare("SELECT id FROM hm_log_msg WHERE id < ".$msgid." ORDER BY id DESC LIMIT 1;");
	$lastid_sql->execute();
	$lastid = $lastid_sql->fetchColumn();
	if ($lastid) {$last = "<a href='./msg.php?msgid=".$lastid."'>Previous Message</a>";} else {$last = "Previous Message";}

	echo "
	<br><br>
	<div class='section'>
		<div style='float:left;width:50%;'>
			<h2>Msg ID: ".$msgid."</h2>
		</div>
		<div style='float:right;text-align:right;'>
			<h2>".$last." | ".$next."</h2>
		</div>
		<div class='clear'></div>
		<div style='border:1px solid black;border-radius:5px;padding:10px;'>";

	$sql = $pdo->prepare("SELECT * FROM hm_log_msg WHERE id = ".$msgid.";");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		$envelopeTo = $row['envelopeTo'];
		echo "
			<div class='secmsgleft'><b>Subject:</b></div><div class='secmsgright'>".$row['subject']."&nbsp;</div><div class='clear'></div>
			<div class='secmsgleft'><b>Envelope From:</b></div><div class='secmsgright'>".$row['envelopeFrom']."&nbsp;</div><div class='clear'></div>
			<div class='secmsgleft'><b>From Header:</b></div><div class='secmsgright'>".htmlspecialchars($row['headerFrom'])."&nbsp;</div><div class='clear'></div>
			<div class='secmsgleft'><b>envelopeTo:</b></div><div class='secmsgright'>".$row['envelopeTo']."&nbsp;</div><div class='clear'></div>
			<div class='secmsgleft'><b>To Header:</b></div><div class='secmsgright'>".htmlspecialchars($row['headerTo'])."&nbsp;</div><div class='clear'></div>
			<div class='secmsgleft'><b>Status Code:</b></div><div class='secmsgright'>".getStatusCodeDescription($row['statuscode'])."&nbsp;</div><div class='clear'></div>";
	}

	$awstatssql = $pdo->prepare("
		SELECT * FROM (SELECT *, timestamp AS msgts, INET6_NTOA(ip) AS ipm, id AS amsgid FROM hm_log_msg) a
		LEFT JOIN (SELECT *, timestamp AS awsts FROM hm_log_awstats) b ON a.envelopeFrom = b.envelopeFrom AND a.envelopeTo = b.envelopeTo
		WHERE amsgid = ".$msgid." AND awsts < msgts + INTERVAL 30 SECOND AND awsts > msgts - INTERVAL 30 SECOND;
	");
	$awstatssql->execute();
	while($row = $awstatssql->fetch(PDO::FETCH_ASSOC)){
		echo "
			<div class='secmsgleft'><b>envelopeFrom Connection:</b></div><div class='secmsgright'>".$row['connsender']."</div><div class='clear'></div>
			<div class='secmsgleft'><b>envelopeTo Connection:</b></div><div class='secmsgright'>".$row['connrecipient']."</div><div class='clear'></div>";
	}

	$countAttr_sql = $pdo->prepare("SELECT * FROM hm_log_attr WHERE msgid = ".$msgid.";");
	$countAttr_sql->execute();

	function geoip($matches) {return "<a target='_blank' href='https://geoip.dynu.net/map/".$matches[0]."'>".$matches[0]."</a>";}

	while($rowAttr = $countAttr_sql->fetch(PDO::FETCH_ASSOC)){
		$attrValue = nl2br(preg_replace('/ /','&nbsp;',$rowAttr['value']));
		$attrValue = preg_replace_callback($regexIP,'geoip',$attrValue);
		if ($rowAttr['item']=="List-Unsubscribe") {
			echo "
			<div class='secmsgleft'><b>".$rowAttr['item'].":</b></div><div class='secmsgright'>".nl2br(htmlspecialchars($rowAttr['value']))." <span class='unsub'>[<a href='./unsubscribe.php?from=".urlencode($envelopeTo)."&msgid=".$msgid."&mailto=".urlencode($rowAttr['value'])."'>UNSUBSCRIBE</a>]</span></div><div class='clear'></div>";
		} else {
			echo "
			<div class='secmsgleft'><b>".$rowAttr['item'].":</b></div><div class='secmsgright'>".$attrValue."</div><div class='clear'></div>";
		}
	}

	echo "
		</div>
	</div>";

	$datasql = $pdo->prepare("
		SELECT * FROM (SELECT *, timestamp AS msgts, INET6_NTOA(ip) AS ipm, id AS amsgid FROM hm_log_msg) m
		JOIN (SELECT *, timestamp AS smtpts, INET6_NTOA(ip) AS ipa FROM hm_log_smtp) b ON m.ipm = b.ipa
		WHERE amsgid = ".$msgid." AND smtpts < msgts + INTERVAL 60 SECOND AND smtpts > msgts - INTERVAL 60 SECOND AND port REGEXP '25|465|587'
		ORDER BY smtpts DESC;
	");

	$datasql->execute();

	echo "
	<div class='section'>
		<h2>SMTP Events:</h2>
		<div style='border:1px solid black;border-radius:5px;padding:10px;'>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Timestamp</div>
					<div class='div-table-col'>IP</div>
					<div class='div-table-col'>Port</div>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col center'>Event</div>
					<div class='div-table-col center'>Reason</div>
					<div class='div-table-col center'>HELO</div>
					<div class='div-table-col center'>PTR</div>
					<div class='div-table-col center'>Status</div>
					<div class='div-table-col center'>Msg ID</div>
				</div>";

	while($datarow = $datasql->fetch(PDO::FETCH_ASSOC)){
		$ipSplit = explode(".",$datarow['ipa']);
		echo "
				<div class='div-table-row'>
					<div class='div-table-col center' data-column='Timestamp'>".date("y/m/d H:i:s", strtotime($datarow['timestamp']))."&nbsp;</div>
					<div class='div-table-col center' data-column='IP'><a href='./data.php?search=".$datarow['ipa']."'>".$datarow['ipa']."</a>&nbsp;</div>
					<div class='div-table-col center' data-column='Port'>".$datarow['port']."</a>&nbsp;</div>
					<div class='div-table-col' data-column='Country'><a href='https://geoip.dynu.net/map/".$datarow['ipa']."' target='_blank'>".$datarow['country']."</a>&nbsp;</div>
					<div class='div-table-col' data-column='Event'>".$datarow['event']."</a>&nbsp;</div>
					<div class='div-table-col' data-column='Reason'>".$datarow['reason']."&nbsp;</div>
					<div class='div-table-col' data-column='HELO'><a href='./data.php?search=".$datarow['helo']."'>".$datarow['helo']."</a>&nbsp;</div>
					<div class='div-table-col' data-column='PTR'><a href='./data.php?search=".$datarow['ptr']."'>".$datarow['ptr']."</a>&nbsp;</div>";
		if ($datarow['acc']==1) {$display_status="Accepted";} else {$display_status="Rejected";}
		echo "
					<div class='div-table-col center' data-column='Status'>".$display_status."</div>";
		if (!$datarow['msgid']) {
			echo "
					<div class='div-table-col center' data-column='Msg ID'>&nbsp;</div>";
		} else {
			echo "
					<div class='div-table-col center' data-column='Msg ID'><a href='./msg.php?msgid=".$datarow['msgid']."'>".$datarow['msgid']."</a></div>";
		}
		echo "
				</div>";
	}
			echo "
			</div>
		</div>
	</div>
	
	<div class='section'>
		<h2><input type='button' onclick='load(\"./iframeeml.php?msgid=".$msgid."\")' value='Raw EML File'/> <input type='button' onclick='load(\"./iframemsg.php?msgid=".$msgid."\")' value='Message Body'/></h2>
		<div style='border:1px solid black;border-radius:5px;padding:10px;'>
			<iframe id='my_iframe' src='./iframeeml.php?msgid=".$msgid."' style='width:100%;height:100vh;border:none;'></iframe>
		</div>
	</div>";

	include_once("foot.php");
?>