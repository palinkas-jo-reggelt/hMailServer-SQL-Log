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

	// https://stephanwagner.me/create-world-map-charts-with-svgmap
/*
	$mapjson = array();
	$sql = $pdo->prepare("
		SELECT 
			country, 
			countrycode, 
			SUM(hitsacc) AS acc, 
			SUM(hitsrej) AS rej, 
			ROUND((SUM(hitsrej) / SUM(hitsacc) * 100),0) AS ratio 
		FROM hm_log_ip 
		WHERE countrycode <> '' 
		GROUP BY countrycode;
	");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		array_push($mapjson,
					$row['countrycode'].": {
						link: 'ip.php?search=".urlencode($row['country'])."',
						acc: ".$row['acc'].",
						rej: ".$row['rej'].",
						ratio: ".$row['ratio']."
						},
					");
	} 
*/

	echo "
		<script>
			var svgMapDataHits = {
				data: {
					acc: {
						name: 'Accepted',
						format: '{0}',
						thousandSeparator: ','
					},
					rej: {
						name: 'Rejected',
						format: '{0}',
						thousandSeparator: ','
					},
					ratio: {
						name: 'Ratio',
						format: '{0} %',
						thresholdMax: 100,
						thresholdMin: 0
					}
				},
				applyData: 'ratio',
				values: ".$mapjson."
			};
		</script>
		<script>
			new svgMap({
				colorMax: '#ffe6e6',
				colorMin: '#b30000',
				colorNoData: '#f2f2f2',
				targetElementID: 'hitsmap',
				data: svgMapDataHits,
				mouseWheelZoomEnabled: true,
				mouseWheelZoomWithKey: true
			});
		</script>";

?>