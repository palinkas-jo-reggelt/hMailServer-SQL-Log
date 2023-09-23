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
			ROUND(((COUNT(ip)) * 1.2), -2) AS dailymax,
			DATE(timestamp) AS daily
		FROM ".$Database['table_smtp']." a
		JOIN ".$Database['table_smtpa']." b ON a.id = b.id
		WHERE acc=0 AND DATE(timestamp) < DATE(NOW())
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
	$sql = $pdo->prepare("
		SELECT	
			COUNT(ip) AS hits
		FROM (
			SELECT * FROM (SELECT id AS ida, timestamp, ip FROM ".$Database['table_smtp'].") a
			JOIN (SELECT id AS idb, acc FROM ".$Database['table_smtpa'].") b ON a.ida = b.idb
			WHERE '".date('Y-m-d')." 00:00:00' <= timestamp AND acc=0
		) AS A 
		WHERE timestamp <= '".date('Y-m-d')." 23:59:59' AND acc=0;
	");
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
		var chart = new google.visualization.Gauge(document.getElementById('dialtodayreject'));
		chart.draw(data, options);
	}
</script>";
?>