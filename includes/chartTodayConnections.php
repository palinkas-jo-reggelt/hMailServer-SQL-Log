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
	include_once($_SERVER['DOCUMENT_ROOT']."/statsCurrentData.php");

	// $query = $pdo->prepare("
		// SELECT reason, COUNT(*) as numhits 
		// FROM hm_log_smtp a
		// JOIN hm_log_smtpa b ON a.id = b.id
		// WHERE acc=1 AND DATE(timestamp) = DATE(NOW())
		// GROUP BY reason
		// ORDER BY reason ASC;
	// ");
	// $query->execute();

	// $dataArr = array();
	// $labelArr = array();
	// while($row = $query->fetch(PDO::FETCH_ASSOC)){
		// array_push($dataArr, $row['numhits']);
		// array_push($labelArr, "'".$row['reason']."'");
	// }

	echo "
	<script>
		new Chart('chart_today_con', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: [".$labelArrART."],
				datasets: [
					{
						data: [".$dataArrART."],
						backgroundColor: 'red',
						borderColor: 'red',
						datalabels: {
							color: 'white',
							font: {
								size: 10
							},
							anchor: 'end',
							align: 'left',
						},
					},
				]
			},
			options: {
				indexAxis: 'y',
				scaleShowValues: true,
				scales: {
					x: {
						type: 'logarithmic',
						display: false
					},
					y: {
						ticks: {
							autoSkip: false,
							font: {
								size: 10,
							},
						},
					},
				},
				elements: {
					point:{
						radius: 0
					}
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