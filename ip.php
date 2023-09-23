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
	include_once("head.php");

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$display_pagination = 1;
	} else {
		$page = 1;
		$total_pages = 1;
		$display_pagination = 0;
	}
	if (isset($_GET['search'])) {$search = trim($_GET['search']);} else {$search = "";}
	if (isset($_GET['clear'])) {header("Location: ip.php");}
	if (isset($_GET['port'])) {$port = trim($_GET['port']);} else {$port = "";}
	if (isset($_GET['reason'])) {$reason = trim($_GET['reason']);} else {$reason = "";}
	if (isset($_GET['acc'])) {$acc = trim($_GET['acc']);} else {$acc = "";}
	if (isset($_GET['event'])) {$event = trim($_GET['event']);} else {$event = "";}
	if (isset($_GET['dateFrom'])) {$dateFrom = $_GET['dateFrom'];} else {$dateFrom = "";}
	if (isset($_GET['dateTo'])) {$dateTo = $_GET['dateTo'];} else {$dateTo = "";}

	if (isset($_GET['sort1'])) {
		$sort1_val = $_GET['sort1'];
		$sort1_page = "&sort1=".$sort1_val;
		$sort1_hidden = "<input type='hidden' name='sort1' value='".$sort1_val."'>";
		if ($_GET['sort1'] == "accdesc") {$sort1_sql = "hitsacc DESC"; $sort1_ph = "&#8595; Acc";}
		else if ($_GET['sort1'] == "rejdesc") {$sort1_sql = "hitsrej DESC"; $sort1_ph = "&#8595; Rej";}
		else if ($_GET['sort1'] == "newest") {$sort1_sql = "DATE(lasthit) ASC"; $sort1_ph = "&#8593; Date";}
		else if ($_GET['sort1'] == "oldest") {$sort1_sql = "DATE(lasthit) DESC"; $sort1_ph = "&#8595; Date";}
		else if ($_GET['sort1'] == "countryasc") {$sort1_sql = "country ASC"; $sort1_ph = "&#8593; Country";}
		else if ($_GET['sort1'] == "countrydesc") {$sort1_sql = "country DESC"; $sort1_ph = "&#8595; Country";}
		else if ($_GET['sort1'] == "ipasc") {$sort1_sql = "ipaddress ASC"; $sort1_ph = "&#8593; IP";}
		else if ($_GET['sort1'] == "ipdesc") {$sort1_sql = "ipaddress DESC"; $sort1_ph = "&#8595; IP";}
		else {unset($_GET['sort1']); $sort1_sql = ""; $sort1_ph = "Sort";}
	} else {
		$sort1_val = "";
		$sort1_sql = "";
		$sort1_ph = "Sort";
		$sort1_page = "";
	}
	if (isset($_GET['sort2'])) {
		$sort2_val = $_GET['sort2'];
		$sort2_page = "&sort2=".$sort2_val;
		$sort2_hidden = "<input type='hidden' name='sort1' value='".$sort2_val."'>";
		if ($_GET['sort2'] == "accdesc") {$sort2_sql = "hitsacc DESC"; $sort2_ph = "&#8595; Acc";}
		else if ($_GET['sort2'] == "rejdesc") {$sort2_sql = "hitsrej DESC"; $sort2_ph = "&#8595; Rej";}
		else if ($_GET['sort2'] == "newest") {$sort2_sql = ", DATE(lasthit) ASC"; $sort2_ph = "&#8593; Date";}
		else if ($_GET['sort2'] == "oldest") {$sort2_sql = ", DATE(lasthit) DESC"; $sort2_ph = "&#8595; Date";}
		else if ($_GET['sort2'] == "countryasc") {$sort2_sql = ", country ASC"; $sort2_ph = "&#8593; Country";}
		else if ($_GET['sort2'] == "countrydesc") {$sort2_sql = ", country DESC"; $sort2_ph = "&#8595; Country";}
		else if ($_GET['sort2'] == "ipasc") {$sort2_sql = ", ipaddress ASC"; $sort2_ph = "&#8593; IP";}
		else if ($_GET['sort2'] == "ipdesc") {$sort2_sql = ", ipaddress DESC"; $sort2_ph = "&#8595; IP";}
		else {unset($_GET['sort2']); $sort2_sql = ""; $sort2_ph = "Sort";}
	} else {
		$sort2_val = "";
		$sort2_sql = "";
		$sort2_ph = "Sort";
		$sort2_page = "";
	}
	if ((isset($_GET['sort1'])) || (isset($_GET['sort2']))) {
		$orderby = "ORDER BY ";
	} else {
		$orderby = "ORDER BY lasthit DESC";
	}

	if ($search=="") {$search_ph="";} else {$search_ph=$search;}
	if ($reason=="") {$reason_ph="Reason";} else {$reason_ph=$reason;}
	if ($port=="") {$port_ph="Port";} else {$port_ph=$port;}
	if ($acc=="") {$acc_ph="Status";} else {if ($acc==0) {$acc_ph="Rejected";} else {$acc_ph="Accepted";}}
	if ($event=="") {$event_ph="Event";} else {$event_ph=$event;}

	echo "
	<div class='section'>
		<h3>IP Log</h3>
		<span style='font-size:0.8em;'>The IP Log counts hits per IP along with select last-action information. This log is intended to be permanent and count hits forever. A 'hit' is any log entry recorded to a given IP. The underlying data may be expired without affecting the IP Log.</span>
		<div style='line-height:24px;'>
			<form autocomplete='off' id='myForm' action='ip.php' method='GET'><br>
				<select name='sort1' onchange='this.form.submit()'>
					<option value='".$sort1_val."'>".$sort1_ph."</option>
					<option value='accdesc'>&#8595; Acc</option>
					<option value='rejdesc'>&#8595; Rej</option>
					<option value='newest'>&#8593; Date</option>
					<option value='oldest'>&#8595; Date</option>
					<option value='countryasc'>&#8593; Country</option>
					<option value='countrydesc'>&#8595; Country</option>
					<option value='ipasc'>&#8593; IP</option>
					<option value='ipdesc'>&#8595; IP</option>
				</select>";
	
	if (isset($_GET['sort1'])) {
		echo "
				<select name='sort2' onchange='this.form.submit()'>
					<option value='".$sort2_val."'>".$sort2_ph."</option>
					<option value='accdesc'>&#8595; Acc</option>
					<option value='rejdesc'>&#8595; Rej</option>
					<option value='newest'>&#8593; Date</option>
					<option value='oldest'>&#8595; Date</option>
					<option value='countryasc'>&#8593; Country</option>
					<option value='countrydesc'>&#8595; Country</option>
					<option value='ipasc'>&#8593; IP</option>
					<option value='ipdesc'>&#8595; IP</option>
				</select>";
	}
	echo "
				<select name='port' onchange='this.form.submit()'>
					<option value='".$port."'>".$port_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(lastport) AS port_title FROM hm_log_ip;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
					<option value=".$row['port_title'].">".$row['port_title']."</option>";
	}
	echo "
				</select>
				<select name='event' onchange='this.form.submit()'>
					<option value='".$event."'>".$event_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(lastevent) AS event_title FROM hm_log_ip;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
					<option value=".$row['event_title'].">".$row['event_title']."</option>";
	}
	echo "
				</select>
				<select name='reason' onchange='this.form.submit()'>
					<option value='".$reason."'>".$reason_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(lastreason) AS reason_title FROM hm_log_ip;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
					<option value=".$row['reason_title'].">".$row['reason_title']."</option>";
	}
	echo "
				</select>
				<select name='acc' onchange='this.form.submit()'>
					<option value='".$acc."'>".$acc_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(lastacc) AS acc_title FROM hm_log_ip;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		if ($row['acc_title'] == 0) {$acc_title = "Rejected";} else {$acc_title = "Accepted";}
		echo "
					<option value=".$row['acc_title'].">".$acc_title."</option>";
	}
	echo "
				</select>
				<input type='text' id='dateFrom' name='dateFrom' placeholder='Starting Date...' value='".$dateFrom."'>
				<input type='text' id='dateTo' name='dateTo' placeholder='Ending Date...' value='".$dateTo."'>
				<input type='text' size='20' name='search' placeholder='Search Term...' value='".$search_ph."'>
				<input type='submit' name='submit2' value='Search' >
				<button class='button' type='submit' name='clear'>Reset</button>
			</form>
		</div>
	</div>
	
	<div class='section'>";

	if ($search==""){$search_SQL = "";} else {$search_SQL = " AND (INET6_NTOA(ipaddress) LIKE '".$search."%' OR country LIKE '%".$search."%')";}
	if ($port==""){$port_SQL = "";} else {$port_SQL = " AND lastport='".$port."'";}
	if ($event==""){$event_SQL = "";} else {$event_SQL = " AND lastevent='".$event."'";}
	if ($reason==""){$reason_SQL = "";} else {$reason_SQL = " AND lastreason='".$reason."'";}
	if ($acc==""){$acc_SQL = "";} else {$acc_SQL = " AND lastacc='".$acc."'";}
	if ($dateFrom==""){$dateFrom_SQL = "";} else {$dateFrom_SQL = " AND DATE(lasthit) >= '".$dateFrom."'";}
	if ($dateTo==""){$dateTo_SQL = "";} else {$dateTo_SQL = " AND DATE(lasthit) <= '".$dateTo."'";}

	$offset = ($page-1) * $no_of_records_per_page;
	
	$total_pages_sql = $pdo->prepare("
		SELECT Count( * ) AS count 
		FROM hm_log_ip 
		WHERE LENGTH(ipaddress) > 0".$search_SQL.$port_SQL.$event_SQL.$reason_SQL.$acc_SQL.$dateFrom_SQL.$dateTo_SQL."
	");
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql = $pdo->prepare("
		SELECT *, INET6_NTOA(ipaddress) AS ip FROM hm_log_ip 
		WHERE LENGTH(ipaddress) > 0".$search_SQL.$port_SQL.$event_SQL.$reason_SQL.$acc_SQL.$dateFrom_SQL.$dateTo_SQL."
		".$orderby.$sort1_sql.$sort2_sql."
		LIMIT ".$offset.", ".$no_of_records_per_page
	);
	$sql->execute();

	if ($search==""){$search_res="";} else {$search_res=" for search term \"<b>".$search."</b>\"";}
  	if ($port==""){$port_res="";} else {$port_res=" via port \"<b>".$port."</b>\"";}
  	if ($reason==""){$reason_res="";} else {$reason_res=" with reason \"<b>".$reason."</b>\"";}
	if ($acc==""){$acc_res="";} else {$acc_res=" having status \"<b>".$acc_ph."</b>\"";}
	if ($event==""){$event_res="";} else {$event_res=" from event \"<b>".$event."</b>\"";}
	if ((!$dateFrom=="") && (!$dateTo=="")){
		$date_res=" between \"<b>".$dateFrom."</b>\" and \"<b>".$dateTo."</b>\"";
	} elseif ((!$dateFrom=="") && ($dateTo=="")) {
		$date_res=" after \"<b>".$dateFrom."</b>\"";
	} elseif (($dateFrom=="") && (!$dateTo=="")) {
		$date_res=" before \"<b>".$dateTo."</b>\"";
	} else {
		$date_res="";
	}

	if ($total_pages < 2){
		$pagination = "";
	} else {
		$pagination = "(Page: ".number_format($page)." of ".number_format($total_pages).")";
	}

	if ($total_rows == 1){$singular = '';} else {$singular= 's';}
	if ($total_rows == 0){
		if ($search == "" && $reason == "" && $acc == ""){
			echo "Please enter a search term";
		} else {
			echo "No results ".$search_res.$reason_res.$acc_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.$port_res.$event_res.$reason_res.$acc_res.$date_res.": ".number_format($total_rows)." Record".$singular." ".$pagination."<br></span>
		<div class='div-table'>
			<div class='div-table-row-header'>
				<div class='div-table-col'>IP</div>
				<div class='div-table-col'>Port</div>
				<div class='div-table-col'>Country</div>
				<div class='div-table-col center'>Acc</div>
				<div class='div-table-col center'>Rej</div>
				<div class='div-table-col'>Last</div>
				<div class='div-table-col center'>Reason</div>
				<div class='div-table-col center'>Event</div>
				<div class='div-table-col center'>Status</div>
			</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			echo "
			<div class='div-table-row'>
				<div class='div-table-col center' data-column='IP'><a href='./data.php?search=".$row['ip']."'>".$row['ip']."</a>&nbsp;</div>
				<div class='div-table-col center' data-column='Port'>".$row['lastport']."&nbsp;</div>
				<div class='div-table-col' data-column='Country'><a href='https://geoip.dynu.net/map/".$row['ip']."' target='_blank'>".$row['country']."</a>&nbsp;</div>
				<div class='div-table-col center' data-column='Acc Hits'>".number_format($row['hitsacc'])."&nbsp;</div>
				<div class='div-table-col center' data-column='Rej Hits'>".number_format($row['hitsrej'])."&nbsp;</div>
				<div class='div-table-col center' data-column='Last Hit'>".date("y/m/d H:i:s", strtotime($row['lasthit']))."&nbsp;</div>
				<div class='div-table-col' data-column='Last Reason'>".$row['lastreason']."&nbsp;</div>
				<div class='div-table-col' data-column='Last Event'>".$row['lastevent']."&nbsp;</div>";
				if ($row['lastacc']==1) {$display_status="Accepted";} else {$display_status="Rejected";}
				echo "
				<div class='div-table-col center' data-column='Active'>".$display_status."</div>
			</div>";
		}
		echo "
		</div>"; // End table

		if ($search==""){$search_page = "";} else {$search_page = "&search=".$search;}
		if ($reason==""){$reason_page = "";} else {$reason_page = "&reason=".$reason;}
		if ($acc==""){$acc_page = "";} else {$acc_page = "&acc=".$acc;}
		if ($port==""){$port_page = "";} else {$port_page = "&port=".$port;}
		
		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page.$port_page.$reason_page.$acc_page.$sort1_page.$sort2_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page.$port_page.$reason_page.$acc_page.$sort1_page.$sort2_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page.$port_page.$reason_page.$acc_page.$sort1_page.$sort2_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$port_page.$search_page.$reason_page.$acc_page.$sort1_page.$sort2_page."\">Last</a></li>";}
			echo "
			</ul>
		</span>";
		}
	}

	echo "
	</div> <!-- end of section -->";

	include_once("foot.php");
?>