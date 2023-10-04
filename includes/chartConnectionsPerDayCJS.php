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