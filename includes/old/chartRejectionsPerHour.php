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
?>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('timeofday', 'Hour');
	data.addColumn('number', 'Avg Hits');
	data.addRows([
<?php 
	$query = $pdo->prepare("
		SELECT 
			hour, 
			ROUND(AVG(numhits), 1) AS avghits 
		FROM (
			SELECT 
				DATE(timestamp) AS day, 
				HOUR(timestamp) AS hour, 
				COUNT(*) as numhits 
			FROM ".$Database['table_smtp']." a
			JOIN ".$Database['table_smtpa']." b ON a.id = b.id
			WHERE acc=0
			GROUP BY DATE(timestamp), HOUR(timestamp)
		) d 
		GROUP BY hour 
		ORDER BY hour ASC
	");
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		echo "[[".$row['hour'].", 0, 0], ".$row['avghits']."],";
	}
?>
	]);

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_rejections_per_hour'));
	  chart.draw(data, {
		width: 350,
		height: 200,
		legend: 'none',
		colors: ['#000000']
	  });
}	
</script>
