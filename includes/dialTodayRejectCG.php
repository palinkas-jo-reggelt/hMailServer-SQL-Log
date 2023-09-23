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
	include_once($_SERVER['DOCUMENT_ROOT']."/statsCurrentData.php");

	// https://bernii.github.io/gauge.js/
	
/*
	//Get guage max
	$sqlmax = $pdo->prepare("
		SELECT	
			ROUND(((COUNT(ip)) * 1.2), -2) AS dailymax,
			DATE(timestamp) AS daily
		FROM hm_log_smtp a
		JOIN hm_log_smtpa b ON a.id = b.id
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
*/

	//Get current (today's) bans
	// $sql = $pdo->prepare("
		// SELECT	
			// COUNT(ip) AS hits
		// FROM (
			// SELECT * FROM (SELECT id AS ida, timestamp, ip FROM hm_log_smtp) a
			// JOIN (SELECT id AS idb, acc FROM hm_log_smtpa) b ON a.ida = b.idb
			// WHERE '".date('Y-m-d')." 00:00:00' <= timestamp AND acc=0
		// ) AS A 
		// WHERE timestamp <= '".date('Y-m-d')." 23:59:59' AND acc=0;
	// ");
	// $sql->execute();
	// $hitsR = $sql->fetchColumn();
	echo "
						<canvas data-type='radial-gauge'
							data-width='".$datawidth."'
							data-height='".$dataheight."'
							data-units='R/d'
							data-font-units-size='25'
							data-value='".$hitsR."'
							data-value-dec='0'
							data-value-int='0'
							data-font-value='consolas'
							data-font-value-weight='bold'
							data-font-numbers-size='".$tickFontSize."'
							data-font-value-size='".$valueFontSize."'
							data-color-value-text='#333'
							data-color-value-box-background='#fff'
							data-color-value-box-rect='#fff'
							data-color-value-box-rect-end='#fff'
							data-color-value-box-rect-shadow='#fff'
							data-min-value='0'
							data-max-value='".$gaugeMaxR."'
							data-major-ticks='0,".$gauge25R.",".$gauge50R.",".$gauge75R.",".$gauge100R.",".$gaugeMaxR."'
							data-minor-ticks='10'
							data-stroke-ticks='true'
							data-color-units='#333'
							data-highlights='[
								{ \"from\": ".$gauge75R.", \"to\": ".$gauge100R.", \"color\": \"orange\" },
								{ \"from\": ".$gauge100R.", \"to\": ".$gaugeMaxR.", \"color\": \"red\" }
							]'
							data-color-plate='#fff'
							data-border-shadow-width='0'
							data-borders='false'
							data-color-needle='red'
							data-color-needle-end='red'
							data-needle-shadow='true'
							data-needle-end='85'
							data-needle-type='arrow'
							data-needle-width='2'
							data-needle-circle-size='7'
							data-needle-circle-outer='true'
							data-needle-circle-inner='false'
							data-animation-duration='1500'
							data-animation-rule='linear'
							data-animation-target='needle'
							data-animation-rule='bounce'
							data-animation-duration='1500'
						></canvas>";

?>