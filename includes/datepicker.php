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

	$minDate_sql = $pdo->prepare("SELECT DATE(timestamp) FROM hm_log_smtp ORDER BY DATE(timestamp) ASC LIMIT 1;");
	$minDate_sql->execute();
	$minDateDB = $minDate_sql->fetchColumn();
	$minDx = explode("-",$minDateDB);
	$minDate = $minDx[0].", ".($minDx[1]-1).", ".$minDx[2];

	$maxDate_sql = $pdo->prepare("SELECT DATE(timestamp) FROM hm_log_smtp ORDER BY DATE(timestamp) DESC LIMIT 1;");
	$maxDate_sql->execute();
	$maxDateDB = $maxDate_sql->fetchColumn();
	$maxDx = explode("-",$maxDateDB);
	$maxDate = $maxDx[0].", ".($maxDx[1]-1).", ".$maxDx[2];

	echo "
<script type='text/javascript'>
	$(function () {
		$('#dateFrom').datepicker({
			dateFormat: 'yy-mm-dd',
			minDate: new Date(".$minDate."),
			maxDate: new Date(".$maxDate."),
			onSelect: function (selected) {
				$('#dateTo').datepicker('option', 'minDate', selected);
			}
		});
		$('#dateTo').datepicker({
			dateFormat: 'yy-mm-dd',
			minDate: new Date(".$minDate."),
			maxDate: new Date(".$maxDate."),
			onSelect: function (selected) {
				$('#dateFrom').datepicker('option', 'maxDate', selected);
			}
		});
	});
</script>
";
?>