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

	echo "
	<script>
		new Chart('chart_rejections_per_hour', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: [".implode(",",$labelArrRPH)."],
				datasets: [
					{
						data: [".implode(",",$dataArrRPH)."],
						backgroundColor: 'black',
						borderColor: 'black',
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