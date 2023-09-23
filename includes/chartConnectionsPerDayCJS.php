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

	// $query = $pdo->prepare("
		// SELECT DATE(timestamp) AS daily, COUNT(ip) AS hits 
		// FROM hm_log_smtp a
		// JOIN hm_log_smtpa b ON a.id = b.id
		// WHERE DATE(timestamp) < DATE(NOW()) AND acc=1 AND reason='Client_Connection' AND port REGEXP '25|587|465'
		// GROUP BY daily ASC
	// ");
	// $query->execute();

	// $dataArr = array();
	// $hitsArr = array();
	// $dateArr = array();
	// $iterArr = array();
	// $i=1;
	// while($row = $query->fetch(PDO::FETCH_ASSOC)){
		// $data = "{x: '".$row['daily']."', y: ".$row['hits']."}";
		// array_push($dataArr, $data);
		// array_push($hitsArr, $row['hits']);
		// array_push($dateArr, $row['daily']);
		// array_push($iterArr, $i);
		// $i++;
	// }

	$trendarrayCPD = linear_regression($iterArrCPD, $hitsArrCPD);
	$trendlineArrCPD = array();
	for ($j = 0; $j < count($hitsArrCPD); $j++) {
		$numberCPD = ($trendarrayCPD['slope'] * $iterArrCPD[$j]) + $trendarrayCPD['intercept'];
		array_push($trendlineArrCPD,"{x: '".$dateArrCPD[$j]."', y: ".$numberCPD."}");
	}

	echo "
	<script>
		new Chart('chart_connections_per_day', {
			type: 'line',
			data: {
				datasets: [
					{
						data: [".implode(",",$dataArrCPD)."],
						backgroundColor: 'red',
						borderColor: 'red',
					},
					{
						data: [".implode(",",$trendlineArrCPD)."],
						backgroundColor: 'black',
						borderColor: 'black',
					}
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
							display: false
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