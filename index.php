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
				</div>
				".$EnvFromRows."
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
				</div>
				".$EnvToRows."
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
				</div>
				".$ReasonAccRows."
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
				</div>
				".$ReasonRejRows."
			</div>
			<span style='font-size:0.6em;'>Rejected reasons enumerated drawn from available data.</span>
		</div>
		<div class='clear'></div>
	</div>";

	// TOP 10's

	echo "
	<div class='section'>
		<div class='secleft'>
			<h2>Top 10 IPs:</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>IP Address</div>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>
				".$TopTenIPsRows."
			</div>
			<span style='font-size:0.6em;'>Top 10 IP activity drawn from available data.</span>
		</div>";

	echo "
		<div class='secright'>
			<h2>Top 10 countries:</h2>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>
				".$TopTenCountriesRows."
			</div>
			<span style='font-size:0.6em;'>Top 10 country activity drawn from available data.</span>
		</div>
		<div class='clear'></div>
	</div>";

	include_once("foot.php");
?>