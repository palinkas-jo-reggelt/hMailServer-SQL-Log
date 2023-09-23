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
			ROUND(((COUNT(ip)) * 1.2), -2) AS dailymax,
			DATE(timestamp) AS daily
		FROM ".$Database['table_smtp']." a
		JOIN ".$Database['table_smtpa']." b ON a.id = b.id
		WHERE acc=0 AND DATE(timestamp) < DATE(NOW())
		GROUP BY daily
		ORDER BY dailymax DESC
		LIMIT 1;
	");
	$sqlmax->execute();
	$gaugeMaxR = $sqlmax->fetchColumn();

	//Set guage color marker points
	if (!$sqlmax->rowCount() > 0){$gaugeMaxR = 100;}
	$gauge100R = ($gaugeMaxR / 1.25);
	$gauge75R = ($gauge100R * 0.75);
	$gauge50R = ($gauge100R * 0.5);
	$gauge25R = ($gauge100R * 0.25);

	//Get current (today's) bans
	$sql = $pdo->prepare("
		SELECT	
			COUNT(ip) AS hits
		FROM (
			SELECT * FROM (SELECT id AS ida, timestamp, ip FROM ".$Database['table_smtp'].") a
			JOIN (SELECT id AS idb, acc FROM ".$Database['table_smtpa'].") b ON a.ida = b.idb
			WHERE '".date('Y-m-d')." 00:00:00' <= timestamp AND acc=0
		) AS A 
		WHERE timestamp <= '".date('Y-m-d')." 23:59:59' AND acc=0;
	");
	$sql->execute();
	$hitsR = $sql->fetchColumn();
	echo "
<script>
	function drawR() {
		var optsR = {
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
				{strokeStyle: '#30B32D', min: 0, max: ".$gauge75R."}, // Green
				{strokeStyle: '#FFDD00', min: ".$gauge75R.", max: ".$gauge100R."}, // Yellow
				{strokeStyle: '#F03E3E', min: ".$gauge100R.", max: ".$gaugeMaxR."}  // Red
			],
			".$staticLabels."staticLabels: {
				".$staticLabels."font: '".$staticLabelsfont."',  // Specifies font
				".$staticLabels."labels: [".$gauge25R.", ".$gauge50R.", ".$gauge75R.", ".$gauge100R."],  // Print labels at these values
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
		var targetR = document.getElementById('dialTodayReject'); // your canvas element
		var gaugeR = new Gauge(targetR).setOptions(optsR); // create sexy gauge!
		gaugeR.maxValue = ".$gaugeMaxR."; // set max gauge value
		gaugeR.setMinValue(0);  // Prefer setter over gauge.minValue = 0
		gaugeR.animationSpeed = 32; // set animation speed (32 is default value)
		gaugeR.set(".$hitsR."); // set actual value
	}
</script>";
?>