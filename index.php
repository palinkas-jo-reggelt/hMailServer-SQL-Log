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
	include_once("statsData.php");
	include_once("statsCurrentData.php");
	
	// DIALS 
	$dataheight = 100;
	$datawidth = 100;
	$tickFontSize = "30";
	$valueFontSize ="50";
	echo "
	<div class='section'>
		<h2>Today's Activity:</h2>
		<div style='float:left;width:calc(100%/3*2);'>
			<div style='float:left;width:50%;'>
				<center>
					Connections<br>
					<a href='./data.php?dateFrom=".date('Y-m-d')."&acc=1&reason=Client_Connection'>";
	include("./includes/dialTodayConnectCG.php");
	echo "
					</a>
				</center>
			</div>
			<div style='float:right;width:50%;'>
				<center>
					Rejections<br>
					<a href='./data.php?dateFrom=".date('Y-m-d')."&acc=0'>";
	include("./includes/dialTodayRejectCG.php");
	echo "
					</a>
				</center>
			</div>
			<div class='clear'></div>
		</div>
		
		<div style='float:right;width:calc(100%/3);'>
			<center>
				Messages<br>
				<a href='./messages.php?dateFrom=".date('Y-m-d')."'>";
	include("./includes/dialTodayMsgsCG.php");
	echo "
				</a>
			</center>
		</div>
		<div class='clear'></div>
		<span style='font-size:0.6em;'>Red/Yellow border represents daily max drawn from available data. The Yellow Zone represents 75% to 100% of daily max. The Red Zone represents 100% to 125% of daily max. Therefore, if a dial is in the Red Zone, it is a new record daily high.</span>
	</div>";

	// TODAY REASONS CHARTS

	// $queryca = $pdo->prepare("
		// SELECT COUNT(DISTINCT(reason)) as countacc 
		// FROM hm_log_smtp a
		// JOIN hm_log_smtpa b ON a.id = b.id
		// WHERE acc=1 AND DATE(timestamp) = DATE(NOW());
	// ");
	// $queryca->execute();
	// $countAcc_rows = $queryca->fetchColumn();
	
	// $querycr = $pdo->prepare("
		// SELECT COUNT(DISTINCT(reason)) as countacc 
		// FROM hm_log_smtp a
		// JOIN hm_log_smtpa b ON a.id = b.id
		// WHERE acc=0 AND DATE(timestamp) = DATE(NOW());
	// ");
	// $querycr->execute();
	// $countRej_rows = $querycr->fetchColumn();
	
	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>Accepted Today:</h2>
			<div class='chartcanvas2' style='height:".((16*$countAcc_rows)+18)."px'>
				<canvas id='chart_today_con' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Today's client connections marked \"Accepted\" by reason (log scale).</span>
		</div>
		<div class='secright'>
			<h2>Rejected Today:</h2>
			<div class='chartcanvas2' style='height:".((16*$countRej_rows)+18)."px'>
				<canvas id='chart_today_rej' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Today's client connections marked \"Rejected\" by reason (log scale).</span>
		</div>
		<div class='clear'></div>
	</div>";

	include("./includes/chartTodayConnections.php");
	include("./includes/chartTodayRejections.php");

	// ALL-TIME PER DAY CONNECTION / REJECTION CHARTS

	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>SMTP Connections Per Day:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_connections_per_day' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Client connections to the server per day from available data.</span>
		</div>
		<div class='secright'>
			<h2>SMTP Rejections Per Day:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_rejections_per_day' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Counted reject reasons per day from available data. Reject reasons are not unique. There can be several or none for any given SMTP connection.</span>
		</div>
		<div class='clear'></div>
	</div>";

	// ALL-TIME PER HOUR CONNECTION / REJECTION CHARTS

	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>Connections Averaged Per Hour:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_connections_per_hour' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Client connections to the server averaged per hour from available data.</span>
		</div>
		<div class='secright'>
			<h2>Rejections Averaged Per Hour:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_rejections_per_hour' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Counted reject reasons averaged per hour from available data. Reject reasons are not unique. There can be several or none for any given SMTP connection.</span>
		</div>
		<div class='clear'></div>
	</div>";

	include("./includes/chartConnectionsPerDayCJS.php");
	include("./includes/chartRejectionsPerDayCJS.php");
	include("./includes/chartConnectionsPerHourCJS.php");
	include("./includes/chartRejectionsPerHourCJS.php");

	// Map

	echo "
	<div class='section'>
		<h2>Good Boy Map:</h2>
		<div id='hitsmap'></div>
		<span style='font-size:0.6em;'>Rejection ratio shows who's been good or bad. The higher the ratio of accepts to rejects shows darker, and vice verse. Darker red shows who behaves better.</span>";
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
					<div class='div-table-col'>Envelope From</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	echo $EnvFromRows;

	// $messageSum_sql = $pdo->prepare("SELECT COUNT(*) FROM hm_log_msg;");
	// $messageSum_sql->execute();
	// $messageSum = $messageSum_sql->fetchColumn();
	
	// $activeSender_sql = $pdo->prepare("
		// SELECT envelopeFrom, COUNT(envelopeFrom) AS count
		// FROM hm_log_msg
		// GROUP BY envelopeFrom
		// ORDER BY count DESC
		// LIMIT 10;
	// ");
	// $activeSender_sql->execute();
	// while($sender_row = $activeSender_sql->fetch(PDO::FETCH_ASSOC)){
		// echo "
				// <div class='div-table-row'>
					// <div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=".urlencode($sender_row['envelopeFrom'])."'>".$sender_row['envelopeFrom']."</a></div>
					// <div class='div-table-col right' data-column='Count'>".number_format($sender_row['count'])."</div>
					// <div class='div-table-col right' data-column='Percent'>".number_format(($sender_row['count'] / $messageSum * 100), 1)."%</div>
				// </div>";
	// }
	echo "
			</div>
			<span style='font-size:0.6em;'>Most active senders drawn from available data.</span>
		</div>
		<div class='secright'>
			<h2>Most Active Recipients</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Envelope To</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	echo $EnvToRows;

	// $activeRecipient_sql = $pdo->prepare("
		// SELECT envelopeTo, COUNT(envelopeTo) AS count
		// FROM hm_log_msg
		// GROUP BY envelopeTo
		// ORDER BY count DESC
		// LIMIT 10;
	// ");
	// $activeRecipient_sql->execute();
	// while($recipient_row = $activeRecipient_sql->fetch(PDO::FETCH_ASSOC)){
		// echo "
				// <div class='div-table-row'>
					// <div class='div-table-col truncate' data-column='To'><a href='./messages.php?to=".urlencode($recipient_row['envelopeTo'])."'>".$recipient_row['envelopeTo']."</a></div>
					// <div class='div-table-col right' data-column='Count'>".number_format($recipient_row['count'])."</div>
					// <div class='div-table-col right' data-column='Percent'>".number_format(($recipient_row['count'] / $messageSum * 100), 1)."%</div>
				// </div>";
	// }
	echo "
			</div>
			<span style='font-size:0.6em;'>Most active recipients drawn from available data.</span>
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
	echo $ReasonAccRows;

	// $sql_reasons_sumA = $pdo->prepare("SELECT COUNT(*) FROM hm_log_smtpa WHERE acc=1;");
	// $sql_reasons_sumA->execute();
	// $reasons_sumR = $sql_reasons_sumA->fetchColumn();
	
	// $sql_reasonsA = $pdo->prepare("
		// SELECT reason, COUNT(reason) AS count
		// FROM hm_log_smtpa 
		// WHERE acc = 1
		// GROUP BY reason
		// ORDER BY reason ASC;
	// ");
	// $sql_reasonsA->execute();
	// while($reason_rowA = $sql_reasonsA->fetch(PDO::FETCH_ASSOC)){
		// echo "
				// <div class='div-table-row'>
					// <div class='div-table-col' data-column='Reason'><a href='./data.php?reason=".$reason_rowA['reason']."'>".$reason_rowA['reason']."</a></div>
					// <div class='div-table-col right' data-column='Count'>".number_format($reason_rowA['count'])."</div>
					// <div class='div-table-col right' data-column='Percent'>".number_format(($reason_rowA['count'] / $reasons_sumR * 100), 1)."%</div>
				// </div>";
	// }
	echo "
			</div>
			<span style='font-size:0.6em;'>Accepted reasons enumerated drawn from available data.</span>
		</div>
		<div class='secright'>
			<h2>Log Reasons: Rejected</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Reason</div>
					<div class='div-table-col'>Count</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	echo $ReasonRejRows;

	// $sql_reasons_sumR = $pdo->prepare("SELECT COUNT(*) FROM hm_log_smtpa WHERE acc=0;");
	// $sql_reasons_sumR->execute();
	// $reasons_sumR = $sql_reasons_sumR->fetchColumn();
	
	// $sql_reasonsR = $pdo->prepare("
		// SELECT reason, COUNT(reason) AS count
		// FROM hm_log_smtpa 
		// WHERE acc = 0
		// GROUP BY reason
		// ORDER BY reason ASC;
	// ");
	// $sql_reasonsR->execute();
	// while($reason_rowR = $sql_reasonsR->fetch(PDO::FETCH_ASSOC)){
		// echo "
				// <div class='div-table-row'>
					// <div class='div-table-col' data-column='Reason'><a href='./data.php?reason=".$reason_rowR['reason']."'>".$reason_rowR['reason']."</a></div>
					// <div class='div-table-col right' data-column='Count'>".number_format($reason_rowR['count'])."</div>
					// <div class='div-table-col right' data-column='Percent'>".number_format(($reason_rowR['count'] / $reasons_sumR * 100), 1)."%</div>
				// </div>";
	// }
	echo "
			</div>
			<span style='font-size:0.6em;'>Rejected reasons enumerated drawn from available data.</span>
		</div>
		<div class='clear'></div>
	</div>";

	// TOP 10's

	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>Top 10 IPs:</h2>";

	// $sql_total = $pdo->prepare("SELECT SUM(hitsacc + hitsrej) AS hits FROM hm_log_ip;");
	// $sql_total->execute();
	// $all_rows = $sql_total->fetchColumn();

	// $sql = $pdo->prepare("
		// SELECT 
			// TRIM(BOTH '\"' FROM country) AS trimcountry, 
			// (hitsacc + hitsrej) AS hits,
			// INET6_NTOA(ipaddress) AS ip
		// FROM hm_log_ip 
		// ORDER BY hits DESC
		// LIMIT 10;
	// ");
	// $sql->execute();
	echo "
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>IP Address</div>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	echo $TopTenIPsRows;
	// while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		// echo "
				// <div class='div-table-row'>
					// <div class='div-table-col' data-column='IP'><a href='./data.php?search=".urlencode($row['ip'])."'>".$row['ip']."</a></div>
					// <div class='div-table-col' data-column='Country'>".$row['trimcountry']."</div>
					// <div class='div-table-col center' data-column='Hits'>".number_format($row['hits'])."</div>
					// <div class='div-table-col center' data-column='Percent'>".round(($row['hits'] / $all_rows * 100),2)."%</div>
				// </div>";
	// }
	echo "
			</div>
			<span style='font-size:0.6em;'>Top 10 IP activity drawn from available data.</span>
		</div>";

	echo "
		<div class='secright'>
			<h2>Top 10 countries:</h2>";

	// $sql_total = $pdo->prepare("SELECT SUM(hitsacc + hitsrej) AS hits FROM hm_log_ip;");
	// $sql_total->execute();
	// $all_hits = $sql_total->fetchColumn();

	// $sql = $pdo->prepare("
		// SELECT 
			// TRIM(BOTH '\"' FROM country) AS trimcountry,
			// SUM(hitsacc + hitsrej) AS sumhits
		// FROM hm_log_ip 
		// GROUP BY country 
		// ORDER BY sumhits DESC
		// LIMIT 10
	// ");
	// $sql->execute();
	echo "
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>";
	echo $TopTenCountriesRows;

	// while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		// echo "
				// <div class='div-table-row'>
					// <div class='div-table-col' data-column='Country'><a href='./data.php?search=".urlencode($row['trimcountry'])."'>".$row['trimcountry']."</a></div>
					// <div class='div-table-col center' data-column='Hits'>".number_format($row['sumhits'])."</div>
					// <div class='div-table-col center' data-column='Percent'>".round(($row['sumhits'] / $all_hits * 100),2)."%</div>
				// </div>";
	// }
	echo "
			</div>
			<span style='font-size:0.6em;'>Top 10 country activity drawn from available data.</span>
		</div>
		<div class='clear'></div>
	</div>";

	include_once("foot.php");
?>