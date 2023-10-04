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