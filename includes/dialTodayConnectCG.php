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

/*
	//Get guage max
	$sqlmax = $pdo->prepare("
		SELECT	
			ROUND(((COUNT(ip)) * 1.25), -2) AS dailymax,
			DATE(timestamp) AS daily
		FROM hm_log_smtp a
		JOIN hm_log_smtpa b ON a.id = b.id
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
*/

	//Get current (today's) bans
	// $sql = $pdo->prepare("
		// SELECT 
			// COUNT(ip) AS hits
		// FROM (
			// SELECT * FROM (SELECT id AS ida, timestamp, ip FROM hm_log_smtp) a
			// JOIN (SELECT id AS idb, acc, reason FROM hm_log_smtpa) b ON a.ida = b.idb
			// WHERE '".date('Y-m-d')." 00:00:00' <= timestamp AND acc=1 AND reason='Client_Connection'
		// ) AS x
		// WHERE timestamp <= '".date('Y-m-d')." 23:59:59' AND acc=1 AND reason='Client_Connection';
	// ");
	// $sql->execute();
	// $hitsC = $sql->fetchColumn();
	echo "
						<canvas data-type='radial-gauge'
							data-width='".$datawidth."'
							data-height='".$dataheight."'
							data-units='C/d'
							data-value='".$hitsC."'
							data-value-dec='0'
							data-value-int='0'
							data-font-value-weight='bold'
							data-font-value='consolas'
							data-font-numbers-size='".$tickFontSize."'
							data-font-value-size='".$valueFontSize."'
							data-font-units-size='25'
							data-color-value-text='black'
							data-color-value-box-background='#fff'
							data-color-value-box-rect='#fff'
							data-color-value-box-rect-end='#fff'
							data-color-value-box-rect-shadow='#fff'
							data-min-value='0'
							data-max-value='".$gaugeMaxC."'
							data-major-ticks='0,".$gauge25C.",".$gauge50C.",".$gauge75C.",".$gauge100C.",".$gaugeMaxC."'
							data-minor-ticks='10'
							data-stroke-ticks='true'
							data-color-units='#333'
							data-highlights='[
								{ \"from\": ".$gauge75C.", \"to\": ".$gauge100C.", \"color\": \"orange\" },
								{ \"from\": ".$gauge100C.", \"to\": ".$gaugeMaxC.", \"color\": \"red\" }
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