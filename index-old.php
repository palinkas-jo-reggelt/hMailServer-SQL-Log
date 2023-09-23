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
	
	// DIALS 
	echo "
	<div class='section'>
		<h2>Today's Activity:</h2>
		<div style='float:left;width:66%;'>
			<div style='float:left;width:50%;'>
				<center>
					SMTP Connections
					<a href='./data.php?dateFrom=".date('Y-m-d')."&acc=1&reason=Client_Connection'><div id='dialtodayconnect'></div></a>
				</center>
			</div>
			<div style='float:right;width:50%;'>
				<center>
					SMTP Rejections
					<a href='./data.php?dateFrom=".date('Y-m-d')."&acc=0'><div id='dialtodayreject'></div></a>
				</center>
			</div>
			<div class='clear'></div>
		</div>
		
		<div style='float:right;width:34%;'>
			<center>
				Logged Messages
				<a href='./messages.php?dateFrom=".date('Y-m-d')."'><div id='dialTodayLoggedMessages'></div></a>
			</center>
		</div>
		<div class='clear'></div>
		<span style='font-size:0.6em;'>Red/Yellow border represents daily max drawn from available data. The Yellow Zone represents 75% to 100% of daily max. The Red Zone represents 100% to 120% of daily max. Therefore, if a dial is in the Red Zone, it is a new record daily high.</span>
	</div>";


	// CHARTS

	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>Connections Per Day:</h2>
			<div id='chart_connections_per_day'></div>
			<span style='font-size:0.6em;'>SMTP connections to the server per day from available data.</span>
		</div>
		<div class='secright'>
			<h2>Rejections Per Day:</h2>
			<div id='chart_rejects_per_day'></div>
			<span style='font-size:0.6em;'>SMTP counted reject reasons per day from available data. Reject reasons are not unique. There can be several or none for any given SMTP connection.</span>
		</div>
		<div class='clear'></div>
	</div>

	<div class='section'>
		<div class='secleft'>
			<h2>Connections Averaged Per Hour:</h2>
			<div id='chart_connections_per_hour'></div>
			<span style='font-size:0.6em;'>SMTP connections to the server averaged per hour from available data.</span>
		</div>
		<div class='secright'>
			<h2>Rejections Averaged Per Hour:</h2>
			<div id='chart_rejections_per_hour'></div>
			<span style='font-size:0.6em;'>SMTP counted reject reasons averaged per hour from available data. Reject reasons are not unique. There can be several or none for any given SMTP connection.</span>
		</div>
		<div class='clear'></div>
	</div>";

	// Map

	echo "
	<div class='section'>
		<h2>Rejection Ratio Map:</h2>
		<div id='hitsmap'></div>
		<span style='font-size:0.6em;'>Rejection ratio shows who's been good or bad. The higher the ratio of rejects to accepts shows darker, and vice verse. It does not count hits per country. Reject ratio can be greater than 100% as connections can have multiple reject reasons.</span>";
	include("./includes/mapData.php");
	echo "

	</div>";

	// envelopeFrom-envelopeTo Activity

	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>Most Active Senders</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>envelopeFrom</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";

	$messageSum_sql = $pdo->prepare("SELECT COUNT(*) FROM hm_log_msg;");
	$messageSum_sql->execute();
	$messageSum = $messageSum_sql->fetchColumn();
	
	$activeSender_sql = $pdo->prepare("
		SELECT envelopeFrom, COUNT(envelopeFrom) AS count
		FROM hm_log_msg
		GROUP BY envelopeFrom
		ORDER BY count DESC
		LIMIT 10;
	");
	$activeSender_sql->execute();
	while($sender_row = $activeSender_sql->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='envelopeTo'><a href='./messages.php?from=".urlencode($sender_row['envelopeFrom'])."'>".$sender_row['envelopeFrom']."</a></div>
					<div class='div-table-col right' data-column='Count'>".number_format($sender_row['count'])."</div>
					<div class='div-table-col right' data-column='Percent'>".number_format(($sender_row['count'] / $messageSum * 100), 1)."%</div>
				</div>";
	}
	echo "
			</div>
		</div>
		<div class='secright'>
			<h2>Most Active Recipients</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>envelopeTo</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";

	$activeRecipient_sql = $pdo->prepare("
		SELECT envelopeTo, COUNT(envelopeTo) AS count
		FROM hm_log_msg
		GROUP BY envelopeTo
		ORDER BY count DESC
		LIMIT 10;
	");
	$activeRecipient_sql->execute();
	while($recipient_row = $activeRecipient_sql->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='envelopeTo'><a href='./messages.php?to=".urlencode($recipient_row['envelopeTo'])."'>".$recipient_row['envelopeTo']."</a></div>
					<div class='div-table-col right' data-column='Count'>".number_format($recipient_row['count'])."</div>
					<div class='div-table-col right' data-column='Percent'>".number_format(($recipient_row['count'] / $messageSum * 100), 1)."%</div>
				</div>";
	}
	echo "
			</div>
		</div>
		<div class='clear'></div>
	</div>";

	// Reasons

	echo "
	<div class='section'>
		<!-- START OF REASONS -->
		<div class='secleft'>
			<h2>Log Reasons: Accepted</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Reason</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";

	$sql_reasons_sumA = $pdo->prepare("SELECT COUNT(*) FROM hm_log_smtpa WHERE acc=1;");
	$sql_reasons_sumA->execute();
	$reasons_sumR = $sql_reasons_sumA->fetchColumn();
	
	$sql_reasonsA = $pdo->prepare("
		SELECT reason, COUNT(reason) AS count
		FROM hm_log_smtpa 
		WHERE acc = 1
		GROUP BY reason
		ORDER BY reason ASC;
	");
	$sql_reasonsA->execute();
	while($reason_rowA = $sql_reasonsA->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=".$reason_rowA['reason']."'>".$reason_rowA['reason']."</a></div>
					<div class='div-table-col right' data-column='Count'>".number_format($reason_rowA['count'])."</div>
					<div class='div-table-col right' data-column='Percent'>".number_format(($reason_rowA['count'] / $reasons_sumR * 100), 1)."%</div>
				</div>";
	}
	echo "
			</div>
		</div>
		<div class='secright'>
			<h2>Log Reasons: Rejected</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Reason</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";

	$sql_reasons_sumR = $pdo->prepare("SELECT COUNT(*) FROM hm_log_smtpa WHERE acc=0;");
	$sql_reasons_sumR->execute();
	$reasons_sumR = $sql_reasons_sumR->fetchColumn();
	
	$sql_reasonsR = $pdo->prepare("
		SELECT reason, COUNT(reason) AS count
		FROM hm_log_smtpa 
		WHERE acc = 0
		GROUP BY reason
		ORDER BY reason ASC;
	");
	$sql_reasonsR->execute();
	while($reason_rowR = $sql_reasonsR->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=".$reason_rowR['reason']."'>".$reason_rowR['reason']."</a></div>
					<div class='div-table-col right' data-column='Count'>".number_format($reason_rowR['count'])."</div>
					<div class='div-table-col right' data-column='Percent'>".number_format(($reason_rowR['count'] / $reasons_sumR * 100), 1)."%</div>
				</div>";
	}
	echo "
			</div>
		</div>
		<div class='clear'></div>
	</div>";

	// TABLES

	echo "
	<div class='section'>
		<!-- START OF TOP TEN IP ADDRESSES -->
		<div class='secleft'>
			<h2>Top 10 IPs:</h2>";

	$sql_total = $pdo->prepare("SELECT SUM(hitsacc + hitsrej) AS hits FROM ".$Database['table_ip']);
	$sql_total->execute();
	$all_rows = $sql_total->fetchColumn();

	$sql = $pdo->prepare("
		SELECT 
			TRIM(BOTH '\"' FROM country) AS trimcountry, 
			(hitsacc + hitsrej) AS hits,
			INET6_NTOA(ipaddress) AS ip
		FROM hm_log_ip 
		ORDER BY hits DESC
		LIMIT 10;
	");
	$sql->execute();
	echo "
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>IP Address</div>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=".urlencode($row['ip'])."'>".$row['ip']."</a></div>
					<div class='div-table-col' data-column='Country'>".$row['trimcountry']."</div>
					<div class='div-table-col center' data-column='Hits'>".number_format($row['hits'])."</div>
					<div class='div-table-col center' data-column='Percent'>".round(($row['hits'] / $all_rows * 100),2)."%</div>
				</div>";
	}
	echo "
			</div> <!--End table-->
			<br>
		</div> <!--End secleft -->
		<!-- END OF TOP TEN IP ADDRESSES -->";

	echo "
		<!-- START OF TOP TEN COUNTRIES -->
		<div class='secright'>
			<h2>Top 10 countries:</h2>";

	$sql_total = $pdo->prepare("SELECT SUM(hitsacc + hitsrej) AS hits FROM ".$Database['table_ip']);
	$sql_total->execute();
	$all_hits = $sql_total->fetchColumn();

	$sql = $pdo->prepare("
		SELECT 
			TRIM(BOTH '\"' FROM country) AS trimcountry,
			SUM(hitsacc + hitsrej) AS sumhits
		FROM hm_log_ip 
		GROUP BY country 
		ORDER BY sumhits DESC
		LIMIT 10
	");
	$sql->execute();
	echo "
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=".urlencode($row['trimcountry'])."'>".$row['trimcountry']."</a></div>
					<div class='div-table-col center' data-column='Hits'>".number_format($row['sumhits'])."</div>
					<div class='div-table-col center' data-column='Percent'>".round(($row['sumhits'] / $all_hits * 100),2)."%</div>
				</div>";
	}
	echo "
			</div> <!--End table-->
		<br>
		</div> <!-- End secleft -->
		<!-- END OF TOP TEN COUNTRIES -->";

	echo "
		<div class='clear'></div>
	</div> <!-- END OF SECTION -->";

	include_once("foot.php");
?>