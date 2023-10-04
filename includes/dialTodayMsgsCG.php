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

	echo "
					<canvas data-type='radial-gauge'
						data-width='".$datawidth."'
						data-height='".$dataheight."'
						data-units='M/d'
						data-font-units-size='25'
						data-value='".$hitsM."'
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
						data-max-value='".$gaugeMaxM."'
						data-major-ticks='0,".$gauge25M.",".$gauge50M.",".$gauge75M.",".$gauge100M.",".$gaugeMaxM."'
						data-minor-ticks='10'
						data-stroke-ticks='true'
						data-color-units='#333'
						data-highlights='[
							{ \"from\": ".$gauge75M.", \"to\": ".$gauge100M.", \"color\": \"orange\" },
							{ \"from\": ".$gauge100M.", \"to\": ".$gaugeMaxM.", \"color\": \"red\" }
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