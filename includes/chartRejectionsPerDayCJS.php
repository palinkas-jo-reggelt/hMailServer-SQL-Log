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
		// WHERE DATE(timestamp) < DATE(NOW()) AND acc=0
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

	$trendarrayRPD = linear_regression($iterArrRPD, $hitsArrRPD);
	$trendlineArrRPD = array();
	for ($j = 0; $j < count($hitsArrRPD); $j++) {
		$numberRPD = ($trendarrayRPD['slope'] * $iterArrRPD[$j]) + $trendarrayRPD['intercept'];
		array_push($trendlineArrRPD,"{x: '".$dateArrRPD[$j]."', y: ".$numberRPD."}");
	}

	echo "
	<script>
		new Chart('chart_rejections_per_day', {
			type: 'line',
			data: {
				datasets: [
					{
						data: [".implode(",",$dataArrRPD)."],
						backgroundColor: 'black',
						borderColor: 'black',
					},
					{
						data: [".implode(",",$trendlineArrRPD)."],
						backgroundColor: 'red',
						borderColor: 'red',
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