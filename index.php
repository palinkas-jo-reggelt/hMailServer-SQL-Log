<?php
// Fill in variables
$m_host="root";
$m_dbuser="hmailserver";
$m_dbpass="supersecretpassword";
$m_db="hmailserver";

	$con=mysqli_connect($m_host,$m_dbuser,$m_dbpass,$m_db);
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
?>

<!DOCTYPE html> 
<html>
<head>
<title>hMailServer Connection Log</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$(function () {
    $("#date").datepicker({
        dateFormat: "yy-mm-dd",
		minDate: <?php
	$query = "SELECT DATE(timestamp) Date FROM hm_accrej ORDER BY DATE(timestamp) ASC LIMIT 1";
	$exec = mysqli_query($con,$query);
	while($row = mysqli_fetch_array($exec)){
		echo "'".$row['Date']."',";
	}
?>
		maxDate: new Date,
        onSelect: function (selected) {
        }
    });
});
</script>

<style>
body {
	background: #fefefe;
	font-family: "Roboto";
	font-size: 12pt;
	}

a:link, a:active, a:visited {
	color: #FF0000;
	text-transform: underline;
	}

a:hover {
	color: #FF0000;
	text-transform: none;
	}

.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    color: #000;
	background: #fefefe;
    z-index: 1;
    overflow: hidden;
    text-align:center;
	}

.header h1 {
	font-size:25px;
    font-weight:normal;
	margin:0 auto;
	}

.header h2 {
	font-size:15px;
    font-weight:normal;
	margin:0 auto;
	}

.wrapper {
	max-width: 920px;
	position: relative;
	margin: 30px auto 30px auto;
	padding-top: 20px;
	}

.clear {
	clear: both;
	}

.banner {
	width: 100%;
	}

.headlinks {
	max-width: 720px;
	position:relative;
	margin: 0px auto;
	}

.headlinkwidth {
	width: 100%;
	min-width: 300px;
	position:relative;
	margin: 0 auto;
	}

.headlinks a:link, a:active, a:visited {
	color: #FF0000;
	text-transform: underline;
	}

.headlinks a:hover {
	color: #FF0000;
	text-transform: none;
	}

.section {
	padding: 5px 0 15px 0;
	margin: 0;
	}

.section h2 {
	font-size:16px;
    font-weight:bold;
	text-align:left;
	}

.section h3 {
	font-size:16px;
    font-weight:bold;
	}

.secleft {
	float: left;
	width: 49%;
	padding-right: 3px;
	}

.secright {
	float: right;
	width: 49%;
	padding-left: 3px;
	}

table.section {
	border-collapse: collapse;
	border: 1px solid black;
	width: 100%;
	font-size: 10pt;
	}
	
table.section th, table.section td {
	border: 1px solid black;
	}

.footer {
	width: 100%;
	text-align: center;
	}
	
ul {
	list-style-type: none;
	padding: 0;
	}

li {
	padding: 0;
	display: inline;
	}
	
@media only screen and (max-width: 629px) {
	.secleft {
		float: none ;
		width: 100% ;
		padding: 0 0 10px 0;
		text-align: left;
	}
	.secright {
		float: none ;
		width: 100% ;
	}

}	
</style>
</head>
<body>

<div class="header">
	<div class="banner"><h1><a href="./">hMailServer Connection Log</a></h1></div>
</div>

<div class="wrapper">

<?php

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$display_pagination = 1;
	} else {
		$page = 1;
		$total_pages = 1;
		$display_pagination = 0;
	}
	if (isset($_GET['submit'])) {$button = $_GET ['submit'];} else {$button = "";}
	if (isset($_GET['search'])) {$search = mysqli_real_escape_string($con, preg_replace('/\s+/', ' ',trim($_GET['search'])));} else {$search = "";}
	if (isset($_GET['AR'])) {$AR = mysqli_real_escape_string($con, preg_replace('/\s+/', ' ',trim($_GET['AR'])));} else {$AR = "";}
	if (isset($_GET['Event'])) {$Event = mysqli_real_escape_string($con, preg_replace('/\s+/', ' ',trim($_GET['Event'])));} else {$Event = "";}
	if (isset($_GET['date'])) {
		if(preg_match("/^(20[1-9][0-9]\-(0[0-9]|1[0-2])\-(0[0-9]|[1-2][0-9]|3[0-1]))$/", ($_GET['date']))) {
			$date = (mysqli_real_escape_string($con, preg_replace('/\s+/', ' ',trim($_GET['date']))));
		} else {
			$date = "";
		}
	} else {
		$date = "";
	}
	echo "<div class='section'>";
	echo "<form autocomplete='off' action='index.php' method='GET'> ";
	echo    "<input type='text' id='date' name='date' placeholder='Date...' value='".$date."' />";
	echo	" ";
	echo	"<input type='text' size='20' name='search' placeholder='Search Term...' value='".$search."'>";
	echo	" ";
	echo	"<select name='AR'>";
	echo		"<option value=''>AR</option>";
	echo		"<option value='REJ'>REJ</option>";
	echo		"<option value='ACC'>ACC</option>";
	echo	"</select>";
	echo	" ";
	echo	"<select name='Event'>";
	echo		"<option value=''>Event</option>";
	$event_title_sql = "SELECT DISTINCT(event) AS event_title FROM hm_accrej";
	$event_result = mysqli_query($con,$event_title_sql);
	while ($row = mysqli_fetch_array($event_result)){
		echo "<option value=".$row['event_title'].">".$row['event_title']."</option>";
	}
	echo	"</select>";
	echo	" ";
	echo	"<input type='submit' name='submit' value='Search' >";
	echo "</form>";
	echo "</div>";
	echo "<div class='section'>";
  
	$no_of_records_per_page = 20;
	$offset = ($page-1) * $no_of_records_per_page;
	
	if ($AR=="REJ"){$AR_SQL = " AND accrej='REJECTED'";}
	elseif ($AR=="ACC"){$AR_SQL = " AND accrej='Accepted'";}
	else {$AR_SQL = "";}
	
	if ($date==""){$date_SQL = "";}
	else {$date_SQL = " AND DATE(timestamp)='".$date."'";}

	if ($Event==""){$Event_SQL = "";}
	else {$Event_SQL = " AND event='".$Event."'";}
	
	$total_pages_sql = "SELECT Count( * ) AS count FROM hm_accrej WHERE (timestamp LIKE '%{$search}%' OR stringport LIKE '%{$search}%' OR port LIKE '%{$search}%' OR event LIKE '%{$search}%' OR reason LIKE '%{$search}%' OR ipaddress LIKE '%{$search}%' OR country LIKE '%{$search}%' OR helo LIKE '%{$search}%')".$AR_SQL."".$date_SQL."".$Event_SQL."";
	$result = mysqli_query($con,$total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql = "SELECT DATE_FORMAT(timestamp, '%y/%m/%d %H:%i.%s') as TimeStamp, stringport, port, event, accrej, reason, country, ipaddress, helo FROM hm_accrej WHERE (timestamp LIKE '%{$search}%' OR stringport LIKE '%{$search}%' OR port LIKE '%{$search}%' OR event LIKE '%{$search}%' OR reason LIKE '%{$search}%' OR ipaddress LIKE '%{$search}%' OR country LIKE '%{$search}%' OR helo LIKE '%{$search}%')".$AR_SQL."".$date_SQL."".$Event_SQL." ORDER BY TimeStamp DESC LIMIT $offset, $no_of_records_per_page";
	$res_data = mysqli_query($con,$sql);
	
	if ($AR=="REJ"){$ARres=" with accept status \"<b>REJECTED</b>\"";} 
	elseif ($AR=="ACC"){$ARres=" with accept status \"<b>Accepted</b>\"";} 
	else {$ARres = "";} 

	if ($date==""){$dateres="";} 
	else {$dateres=" on \"<b>".$date."</b>\"";} 

	if ($search==""){$searchres="";}
	else {$searchres=" for search term \"<b>".$search."</b>\"";}

	if ($Event==""){$Eventres="";}
	else {$Eventres=" from event \"<b>".$Event."</b>\"";}

	if ($total_rows == 1){$singular = '';} else {$singular= 's';}
	if ($total_rows == 0){
		if ($search == "" && $date == ""){
			echo "Please enter a search term";
		} else {
			echo "Error: No results ".$searchres."".$ARres."".$dateres;
		}	
	} else {
		if ($search == "" && $date == ""){
			echo "Please enter a search term. <br /><br />";
			echo "All results".$ARres."".$dateres."".$Eventres.": ".number_format($total_rows)." hit".$singular." (Page: ".number_format($page)." of ".number_format($total_pages).")<br />";
		} else {
			echo "Results ".$searchres."".$ARres."".$dateres."".$Eventres.": ".number_format($total_rows)." Hit".$singular." (Page: ".number_format($page)." of ".number_format($total_pages).")<br />";
		}
		echo "<table class='section'>
			<tr>
				<th>Timestamp</th>
				<th>IP Address</th>
				<th>Protocol</th>
				<th>Port</th>
				<th>Event</th>
				<th>Acceptance</th>
				<th>Reason</th>
				<th>Country</th>
				<th>HELO</th>
			</tr>";
		while($row = mysqli_fetch_array($res_data)){
			echo "<tr>";
			echo "<td>".$row['TimeStamp']."</td>";
			echo "<td><a href=\"index.php?submit=Search&search=".$row['ipaddress']."\">".$row['ipaddress']."</a></td>";
			echo "<td>".$row['stringport']."</td>";
			echo "<td>".$row['port']."</td>";
			echo "<td>".$row['event']."</td>";
			echo "<td>".$row['accrej']."</td>";
			echo "<td>".$row['reason']."</td>";
			echo "<td><a href=\"https://ipinfo.io/".$row['ipaddress']."\"  target=\"_blank\">".$row['country']."</a></td>";
			echo "<td>".$row['helo']."</td>";
			echo "</tr>";
		}
		echo "</table>";
		if ($total_pages == 1){echo "";}
		else {
			echo "<ul>";
			if($page <= 1){echo "<li>First </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&AR=".$AR."&date=".$date."&Event=".$Event."&page=1\">First </a><li>";}
			if($page <= 1){echo "<li>Prev </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&AR=".$AR."&date=".$date."&Event=".$Event."&page=".($page - 1)."\">Prev </a></li>";}
			if($page >= $total_pages){echo "<li>Next </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&AR=".$AR."&date=".$date."&Event=".$Event."&page=".($page + 1)."\">Next </a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&AR=".$AR."&date=".$date."&Event=".$Event."&page=".$total_pages."\">Last</a></li>";}
			echo "</ul>";
		}
		if ($total_pages > 0){
			echo "<br />";
		}
	mysqli_close($con);
	}
	echo "<br />";
	echo "</div>";
?>

<br /><br />

<div class="footer">

</div>

</div> <!-- end WRAPPER -->
</body>
</html>