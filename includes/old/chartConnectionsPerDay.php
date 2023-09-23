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
google.load("visualization", "1", {packages:["corechart", "line"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Hits');
	data.addRows([
<?php 
	$query = $pdo->prepare("
		SELECT 
			DATE(timestamp) AS daily, 
			DATE_FORMAT(timestamp, '%Y') AS year,
			(DATE_FORMAT(timestamp, '%c') - 1) AS month,
			DATE_FORMAT(timestamp, '%e') AS day,
			COUNT(ip) AS ipperday 
		FROM ".$Database['table_smtp']." a
		JOIN ".$Database['table_smtpa']." b ON a.id = b.id
		WHERE DATE(timestamp) < DATE(NOW()) AND acc=1 AND reason='Client_Connection'
		GROUP BY daily ASC
	");
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		echo "[new Date(".$row['year'].", ".$row['month'].", ".$row['day']."), ".$row['ipperday']."],";
	}
?>
	]);

	var chart = new google.visualization.LineChart(document.getElementById('chart_connections_per_day'));
	  chart.draw(data, {
		width: 350,
		height: 200,
		colors: ['#ff0000'],
		legend: 'none',
		trendlines: { 0: { 
			type: 'polynomial',
			degree: 1,
			visibleInLegend: true,
			}
		}
	  });
}	
</script>
