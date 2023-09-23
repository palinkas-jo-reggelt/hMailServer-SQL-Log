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
			ROUND(((COUNT(timestamp)) * 1.2), -1) AS dailymax,
			DATE(timestamp) AS daily
		FROM ".$Database['table_msg']." 
		WHERE DATE(timestamp) < DATE(NOW())
		GROUP BY daily
		ORDER BY dailymax DESC
		LIMIT 1;
	");
	$sqlmax->execute();
	$gaugeMaxM = $sqlmax->fetchColumn();

	//Set guage color marker points
	if (!$sqlmax->rowCount() > 0){$gaugeMaxM = 100;}
	$gauge100M = ($gaugeMaxM / 1.25);
	$gauge75M = ($gauge100M * 0.75);
	$gauge50M = ($gauge100M * 0.5);
	$gauge25M = ($gauge100M * 0.25);

	//Get current (today's) bans
	$sql = $pdo->prepare("SELECT COUNT(*) FROM ".$Database['table_msg']." WHERE DATE(timestamp) = DATE(NOW());");
	$sql->execute();
	$hitsM = $sql->fetchColumn();
	echo "
<script>
	function drawM() {
		var optsM = {
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
				{strokeStyle: '#30B32D', min: 0, max: ".$gauge75M."}, // Green
				{strokeStyle: '#FFDD00', min: ".$gauge75M.", max: ".$gauge100M."}, // Yellow
				{strokeStyle: '#F03E3E', min: ".$gauge100M.", max: ".$gaugeMaxM."}  // Red
			],
			".$staticLabels."staticLabels: {
				".$staticLabels."font: '".$staticLabelsfont."',  // Specifies font
				".$staticLabels."labels: [".$gauge25M.", ".$gauge50M.", ".$gauge75M.", ".$gauge100M."],  // Print labels at these values
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
		var targetM = document.getElementById('dialTodayMessages'); // your canvas element
		var gaugeM = new Gauge(targetM).setOptions(optsM); // create sexy gauge!
		gaugeM.maxValue = ".$gaugeMaxM."; // set max gauge value
		gaugeM.setMinValue(0);  // Prefer setter over gauge.minValue = 0
		gaugeM.animationSpeed = 32; // set animation speed (32 is default value)
		gaugeM.set(".$hitsM."); // set actual value
	}
</script>";
?>