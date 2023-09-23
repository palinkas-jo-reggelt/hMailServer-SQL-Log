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
	include_once($_SERVER['DOCUMENT_ROOT']."/includes/dialsettings.php");

	// https://bernii.github.io/gauge.js/
	
	//Get guage max
	$sqlmax = $pdo->prepare("
		SELECT	
			ROUND(((COUNT(ip)) * 1.25), -2) AS dailymax,
			DATE(timestamp) AS daily
		FROM ".$Database['table_smtp']." a
		JOIN ".$Database['table_smtpa']." b ON a.id = b.id
		WHERE acc=1 AND reason='Client_Connection' AND DATE(timestamp) < DATE(NOW())
		GROUP BY daily
		ORDER BY dailymax DESC
		LIMIT 1;
	");
	$sqlmax->execute();
	$gaugeMaxC = $sqlmax->fetchColumn();

	//Set guage color marker points
	if (!$sqlmax->rowCount() > 0){$gaugeMaxC = 100;}
	$gauge100C = ($gaugeMaxC / 1.25);
	$gauge75C = ($gauge100C * 0.75);
	$gauge50C = ($gauge100C * 0.5);
	$gauge25C = ($gauge100C * 0.25);

	//Get current (today's) bans
	$sql = $pdo->prepare("
		SELECT 
			COUNT(ip) AS hits
		FROM (
			SELECT * FROM (SELECT id AS ida, timestamp, ip FROM ".$Database['table_smtp'].") a
			JOIN (SELECT id AS idb, acc, reason FROM ".$Database['table_smtpa'].") b ON a.ida = b.idb
			WHERE '".date('Y-m-d')." 00:00:00' <= timestamp AND acc=1 AND reason='Client_Connection'
		) AS x
		WHERE timestamp <= '".date('Y-m-d')." 23:59:59' AND acc=1 AND reason='Client_Connection';
	");
	$sql->execute();
	$hitsC = $sql->fetchColumn();
	echo "
<script>
	function drawC() {
		var optsC = {
			angle: ".$angle.", // The span of the gauge arc
			lineWidth: ".$lineWidth.", // The line thickness
			radiusScale: ".$radiusScale.", // Relative radius
			pointer: {
				length: ".$pointerlength.", // // Relative to gauge radius
				strokeWidth: ".$pointerstrokeWidth.", // The thickness
				color: '".$pointercolor."' // Fill color
			},
			limitMax: false,     // If false, max value increases automatically if value > maxValue
			limitMin: false,     // If true, the min value of the gauge will be fixed
			highDpiSupport: true,     // High resolution support
			staticZones: [
				{generateGradient: true},
				{strokeStyle: '#30B32D', min: 0, max: ".$gauge75C."}, // Green
				{strokeStyle: '#FFDD00', min: ".$gauge75C.", max: ".$gauge100C."}, // Yellow
				{strokeStyle: '#F03E3E', min: ".$gauge100C.", max: ".$gaugeMaxC."}  // Red
			],
			".$staticLabels."staticLabels: {
				".$staticLabels."font: '".$staticLabelsfont."',  // Specifies font
				".$staticLabels."labels: [".$gauge25C.", ".$gauge50C.", ".$gauge75C.", ".$gauge100C."],  // Print labels at these values
				".$staticLabels."color: '".$staticLabelscolor."',  // Optional: Label text color
				".$staticLabels."fractionDigits: ".$staticLabelsfractionDigits."  // Optional: Numerical precision. 0=round off.
			".$staticLabels."},
			renderTicks: {
				divisions: ".$Ticksdivisions.",
				divWidth: ".$TicksdivWidth.",
				divLength: ".$TicksdivLength.",
				divColor: '".$TicksdivColor."',
				subDivisions: ".$TickssubDivisions.",
				subLength: ".$TickssubLength.",
				subWidth: ".$TickssubWidth.",
				subColor: '".$TickssubColor."'
			}		  
		};
		var targetC = document.getElementById('dialTodayConnect'); // your canvas element
		var gaugeC = new Gauge(targetC).setOptions(optsC); // create sexy gauge!
		gaugeC.maxValue = ".$gaugeMaxC."; // set max gauge value
		gaugeC.setMinValue(0);  // Prefer setter over gauge.minValue = 0
		gaugeC.animationSpeed = 32; // set animation speed (32 is default value)
		gaugeC.set(".$hitsC."); // set actual value
	}
</script>";
?>