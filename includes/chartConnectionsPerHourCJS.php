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
	include_once($_SERVER['DOCUMENT_ROOT']."/statsData.php");

/*
	$query = $pdo->prepare("
		SELECT 
			hour, 
			ROUND(AVG(numhits), 0) AS avghits 
		FROM (
			SELECT 
				DATE(timestamp) AS day, 
				HOUR(timestamp) AS hour, 
				COUNT(*) as numhits 
			FROM hm_log_smtp a
			JOIN hm_log_smtpa b ON a.id = b.id
			WHERE acc=1 AND reason='Client_Connection'
			GROUP BY DATE(timestamp), HOUR(timestamp)
		) d 
		GROUP BY hour 
		ORDER BY hour ASC
	");
	$query->execute();
	$dataArr = array();
	$labelArr = array();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		array_push($dataArr, $row['avghits']);
		array_push($labelArr, "'".$row['hour']."'");
	}
*/

	echo "
	<script>
		new Chart('chart_connections_per_hour', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: [".implode(",",$labelArrCPH)."],
				datasets: [
					{
						data: [".implode(",",$dataArrCPH)."],
						backgroundColor: 'red',
						borderColor: 'red',
						datalabels: {
							color: 'white',
							font: {
								size: 10
							},
							anchor: 'end',
							align: 'bottom',
							rotation: -90,
						},
					},
				]
			},
			options: {
				elements: {
					point:{
						radius: 0
					}
				},
				scales: {
					x: {
						ticks: {
							autoSkip: true,
							autoSkipPadding: 50,
							maxRotation: 0,
							minRotation: 0
						}
					},
				},
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false,
						position: 'top',
					},
					title: {
						display: false
					}
				}
			},
		});
	</script>";

?>