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