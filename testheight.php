<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	include_once("config.php");
	include_once("functions.php");
	
	echo "
<!DOCTYPE html> 
<html>
<head>

<title>hMailServer Super Log</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='stylesheet' type='text/css' media='all' href='./css/stylesheet.css'>
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet'>";

	echo "
<script src='./js/chart.js'></script>
<script src='./js/chartjs-plugin-datalabels.min.js'></script>";

	echo "
</head>
<body>";

	include_once("header.php");

	echo "
<div class='wrapper'>";

?>

	<div class='section'>
		<div class='secleft'>
			<h2>Accepted Today:</h2>
			<div class='chartcanvas2' style='height:<?php echo ((16*6)+18) ?>px'>
				<canvas id='chart_today_con' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Today's client connections marked "Accepted" by reason (log scale).</span>
		</div>
		<div class='secright'>
			<h2>Rejected Today:</h2>
			<div class='chartcanvas2' style='height:<?php echo ((16*1)+18) ?>px'>
				<canvas id='chart_today_rej' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Today's client connections marked "Rejected" by reason (log scale).</span>
		</div>
		<div class='clear'></div>
	</div>
	<div class='section'>
		<div class='secleft'>
			<h2>SMTP Connections Per Day:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_connections_per_day' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Client connections to the server per day from available data.</span>
		</div>
		<div class='secright'>
			<h2>SMTP Rejections Per Day:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_rejections_per_day' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Counted reject reasons per day from available data. Reject reasons are not unique. There can be several or none for any given SMTP connection.</span>
		</div>
		<div class='clear'></div>
	</div>
	<div class='section'>
		<div class='secleft'>
			<h2>Connections Averaged Per Hour:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_connections_per_hour' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Client connections to the server averaged per hour from available data.</span>
		</div>
		<div class='secright'>
			<h2>Rejections Averaged Per Hour:</h2>
			<div class='chartcanvas'>
				<canvas id='chart_rejections_per_hour' style='width:100%;height:100%;'></canvas>
			</div>
			<span style='font-size:0.6em;'>Counted reject reasons averaged per hour from available data. Reject reasons are not unique. There can be several or none for any given SMTP connection.</span>
		</div>
		<div class='clear'></div>
	</div>
	<script>
		new Chart('chart_connections_per_day', {
			type: 'line',
			data: {
				datasets: [
					{
						data: [{x: '2023-02-12', y: 470},{x: '2023-02-13', y: 478},{x: '2023-02-14', y: 440},{x: '2023-02-15', y: 423},{x: '2023-02-16', y: 428},{x: '2023-02-17', y: 555},{x: '2023-02-18', y: 448},{x: '2023-02-19', y: 468},{x: '2023-02-20', y: 479},{x: '2023-02-21', y: 502},{x: '2023-02-22', y: 595},{x: '2023-02-23', y: 1619},{x: '2023-02-24', y: 673},{x: '2023-02-25', y: 629},{x: '2023-02-26', y: 715},{x: '2023-02-27', y: 505},{x: '2023-02-28', y: 516},{x: '2023-03-01', y: 497},{x: '2023-03-02', y: 514},{x: '2023-03-03', y: 457},{x: '2023-03-04', y: 484},{x: '2023-03-05', y: 417},{x: '2023-03-06', y: 399},{x: '2023-03-07', y: 508},{x: '2023-03-08', y: 479},{x: '2023-03-09', y: 451},{x: '2023-03-10', y: 523},{x: '2023-03-11', y: 428}],
						backgroundColor: 'red',
						borderColor: 'red',
					},
					{
						data: [{x: '2023-02-12', y: 564.12068965517},{x: '2023-02-13', y: 562.28106185003},{x: '2023-02-14', y: 560.44143404488},{x: '2023-02-15', y: 558.60180623974},{x: '2023-02-16', y: 556.76217843459},{x: '2023-02-17', y: 554.92255062945},{x: '2023-02-18', y: 553.0829228243},{x: '2023-02-19', y: 551.24329501916},{x: '2023-02-20', y: 549.40366721401},{x: '2023-02-21', y: 547.56403940887},{x: '2023-02-22', y: 545.72441160372},{x: '2023-02-23', y: 543.88478379858},{x: '2023-02-24', y: 542.04515599343},{x: '2023-02-25', y: 540.20552818829},{x: '2023-02-26', y: 538.36590038314},{x: '2023-02-27', y: 536.526272578},{x: '2023-02-28', y: 534.68664477285},{x: '2023-03-01', y: 532.84701696771},{x: '2023-03-02', y: 531.00738916256},{x: '2023-03-03', y: 529.16776135742},{x: '2023-03-04', y: 527.32813355227},{x: '2023-03-05', y: 525.48850574713},{x: '2023-03-06', y: 523.64887794198},{x: '2023-03-07', y: 521.80925013684},{x: '2023-03-08', y: 519.96962233169},{x: '2023-03-09', y: 518.12999452655},{x: '2023-03-10', y: 516.2903667214},{x: '2023-03-11', y: 514.45073891626}],
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
	</script>
	<script>
		new Chart('chart_rejections_per_day', {
			type: 'line',
			data: {
				datasets: [
					{
						data: [{x: '2023-02-12', y: 234},{x: '2023-02-13', y: 178},{x: '2023-02-14', y: 178},{x: '2023-02-15', y: 144},{x: '2023-02-16', y: 153},{x: '2023-02-17', y: 182},{x: '2023-02-18', y: 182},{x: '2023-02-19', y: 222},{x: '2023-02-20', y: 227},{x: '2023-02-21', y: 238},{x: '2023-02-22', y: 334},{x: '2023-02-23', y: 1368},{x: '2023-02-24', y: 351},{x: '2023-02-25', y: 386},{x: '2023-02-26', y: 471},{x: '2023-02-27', y: 235},{x: '2023-02-28', y: 235},{x: '2023-03-01', y: 215},{x: '2023-03-02', y: 147},{x: '2023-03-03', y: 266},{x: '2023-03-04', y: 474},{x: '2023-03-05', y: 441},{x: '2023-03-06', y: 390},{x: '2023-03-07', y: 488},{x: '2023-03-08', y: 464},{x: '2023-03-09', y: 442},{x: '2023-03-10', y: 451},{x: '2023-03-11', y: 417}],
						backgroundColor: 'black',
						borderColor: 'black',
					},
					{
						data: [{x: '2023-02-12', y: 212.40147783251},{x: '2023-02-13', y: 221.83470169677},{x: '2023-02-14', y: 231.26792556103},{x: '2023-02-15', y: 240.70114942529},{x: '2023-02-16', y: 250.13437328955},{x: '2023-02-17', y: 259.5675971538},{x: '2023-02-18', y: 269.00082101806},{x: '2023-02-19', y: 278.43404488232},{x: '2023-02-20', y: 287.86726874658},{x: '2023-02-21', y: 297.30049261084},{x: '2023-02-22', y: 306.7337164751},{x: '2023-02-23', y: 316.16694033935},{x: '2023-02-24', y: 325.60016420361},{x: '2023-02-25', y: 335.03338806787},{x: '2023-02-26', y: 344.46661193213},{x: '2023-02-27', y: 353.89983579639},{x: '2023-02-28', y: 363.33305966065},{x: '2023-03-01', y: 372.7662835249},{x: '2023-03-02', y: 382.19950738916},{x: '2023-03-03', y: 391.63273125342},{x: '2023-03-04', y: 401.06595511768},{x: '2023-03-05', y: 410.49917898194},{x: '2023-03-06', y: 419.9324028462},{x: '2023-03-07', y: 429.36562671045},{x: '2023-03-08', y: 438.79885057471},{x: '2023-03-09', y: 448.23207443897},{x: '2023-03-10', y: 457.66529830323},{x: '2023-03-11', y: 467.09852216749}],
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
	</script>
	<script>
		new Chart('chart_connections_per_hour', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'],
				datasets: [
					{
						data: [28,39,43,77,84,67,50,39,44,64,62,81,84,92,91,101,69,74,49,36,48,29,30,23],
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
	</script>
	<script>
		new Chart('chart_rejections_per_hour', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'],
				datasets: [
					{
						data: [10,9,11,10,10,14,11,14,15,15,18,19,14,16,17,22,17,14,14,17,20,12,14,11],
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
	</script>
	<script>
		new Chart('chart_today_con', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: ['Client_Authenticated','Client_Connection','Incoming_Message','IP_RBL_Whitelisted','Record_PTR-HELO','WL-PTR'],
				datasets: [
					{
						data: [1,4419,8,7,50,6],
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
						barThickness: 12,
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
	</script>
	<script>
		new Chart('chart_today_rej', {
			type: 'bar',
			plugins: [ChartDataLabels],
			data: {
				labels: ['Spamhaus'],
				datasets: [
					{
						data: [11],
						backgroundColor: 'black',
						borderColor: 'black',
						datalabels: {
							color: 'white',
							font: {
								size: 10
							},
							anchor: 'end',
							align: 'left',
						},
						barThickness: 12,
					},
				]
			},
			options: {
				indexAxis: 'y',
				scaleShowValues: true,
				scales: {
					x: {
						type: 'logarithmic',
						display: false,
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
	</script>

<?php
	include_once("foot.php");
?>