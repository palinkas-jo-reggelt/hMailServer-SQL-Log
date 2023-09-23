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

	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		if (!(($_COOKIE['username'] === $user_name) && ($_COOKIE['password'] === md5($pass_word)))) {
			redirect("login.php?ruri=".urlencode($_SERVER["REQUEST_URI"]));
		}
	} else {
		redirect("login.php?ruri=".urlencode($_SERVER["REQUEST_URI"]));
	}

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


	// DatePicker
	echo "
<link rel='stylesheet' href='./css/jquery-ui.css'>
<script src='./js/jquery.min.js'></script>
<script src='./js/jquery-ui.min.js'></script>";
	if (preg_match('/(data|ip|messages)\.php/', $_SERVER['PHP_SELF'])) {
		include("./includes/datepicker.php"); 
	}
	if (preg_match('/logons\.php/', $_SERVER['PHP_SELF'])) {
		include("./includes/datepicker_logon.php"); 
	}


	// Canvas Gauge Dials
	if (preg_match('/index\.php$/', $_SERVER['PHP_SELF'])) {
		echo "
<script src='./js/gauge.min.js'></script>";
	}


	// Charts.js Charts
	if (preg_match('/index\.php$/', $_SERVER['PHP_SELF'])) {
		echo "
<script src='./js/chart.js'></script>
<script src='./js/chartjs-plugin-datalabels.min.js'></script>";
	}


	// Map tools 
	if (preg_match('/(index|index-old)\.php$/', $_SERVER['PHP_SELF'])) {
		echo "
<script src='./js/svg-pan-zoom.min.js'></script>
<link href='./css/svgMap.css' rel='stylesheet'>
<script src='./js/svgMap.js'></script>";
	}


	// iFrame Toggle
	if (preg_match('/msg\.php/', $_SERVER['PHP_SELF'])) {
		echo "
<script>
	function load(page) {
		document.getElementById('my_iframe').src = page;
	}
</script>";
	}


	// Style Options in config.php 
	if (preg_match('/(index|indexnewdials|unsubscribe|unread)\.php$/', $_SERVER['PHP_SELF'])) {
		echo "
<style>.wrapper {max-width: 900px;}</style>";
	} else {
		echo "
<style>.wrapper {max-width: ".$viewport_width."px;}</style>";
	}


	// Start Page
	echo "
</head>
<body>";

	include_once("header.php");

	echo "
<div class='wrapper'>";

?>