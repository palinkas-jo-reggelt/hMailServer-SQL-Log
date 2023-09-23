<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/functions.php");

	//Get guage max
	$sqlmax = $pdo->prepare("
		SELECT	
			ROUND(((COUNT(timestamp)) * 1.2), -1) AS dailymax,
			DATE(timestamp) AS daily
		FROM ".$Database['table_msg']." 
		WHERE DATE(timestamp) < DATE(NOW())
		GROUP BY daily
		ORDER BY dailymax DESC
		LIMIT 1;
	");
	$sqlmax->execute();
	$redTo = $sqlmax->fetchColumn();

	//Set guage color marker points
	if (!$sqlmax->rowCount() > 0){$redTo = 100;}
	$redFrom = ($redTo / 1.2);
	$yellowTo = $redFrom;
	$yellowFrom = ($yellowTo * 0.75);

	//Get current (today's) bans
	$sql = $pdo->prepare("SELECT COUNT(*)	FROM ".$Database['table_msg']." WHERE DATE(timestamp) = DATE(NOW());");
	$sql->execute();
	$hits = $sql->fetchColumn();

	echo "
<script type='text/javascript'>
	google.charts.load('current', {'packages':['gauge']});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Label', 'Value'],
			['IPs', ".$hits."]
		]);
		var options = { 
			width: 100, height: 100, 
			min: 0, max: ".$redTo.", 
			redFrom: ".$redFrom.", redTo: ".$redTo.", 
			yellowFrom: ".$yellowFrom.", yellowTo: ".$yellowTo.", 
			minorTicks: 10
		};
		var chart = new google.visualization.Gauge(document.getElementById('dialTodayLoggedMessages'));
		chart.draw(data, options);
	}
</script>";
?>
