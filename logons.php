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
	if (isset($_GET['clear'])) {header("Location: logons.php");}
	if (isset($_GET['port'])) {$port = trim($_GET['port']);} else {$port = "";}
	if (isset($_GET['reason'])) {$reason = trim($_GET['reason']);} else {$reason = "";}
	if (isset($_GET['acc'])) {$acc = trim($_GET['acc']);} else {$acc = "";}
	if (isset($_GET['dateFrom'])) {$dateFrom = $_GET['dateFrom'];} else {$dateFrom = "";}
	if (isset($_GET['dateTo'])) {$dateTo = $_GET['dateTo'];} else {$dateTo = "";}

	if (isset($_GET['sort1'])) {
		$sort1_val = $_GET['sort1'];
		$sort1_page = "&sort1=".$sort1_val;
		if ($_GET['sort1'] == "reasonasc") {$sort1_sql = "reason ASC"; $sort1_ph = "&#8593; Reason";}
		else if ($_GET['sort1'] == "reasondesc") {$sort1_sql = "reason DESC"; $sort1_ph = "&#8595; Reason";}
		else if ($_GET['sort1'] == "newest") {$sort1_sql = "DATE(timestamp) ASC"; $sort1_ph = "&#8593; Date";}
		else if ($_GET['sort1'] == "oldest") {$sort1_sql = "DATE(timestamp) DESC"; $sort1_ph = "&#8595; Date";}
		else if ($_GET['sort1'] == "countryasc") {$sort1_sql = "country ASC"; $sort1_ph = "&#8593; Country";}
		else if ($_GET['sort1'] == "countrydesc") {$sort1_sql = "country DESC"; $sort1_ph = "&#8595; Country";}
		else if ($_GET['sort1'] == "ipasc") {$sort1_sql = "ip ASC"; $sort1_ph = "&#8593; IP";}
		else if ($_GET['sort1'] == "ipdesc") {$sort1_sql = "ip DESC"; $sort1_ph = "&#8595; IP";}
		else if ($_GET['sort1'] == "userasc") {$sort1_sql = "username ASC"; $sort1_ph = "&#8593; Username";}
		else if ($_GET['sort1'] == "userdesc") {$sort1_sql = "username DESC"; $sort1_ph = "&#8595; Username";}
		else if ($_GET['sort1'] == "hitsasc") {$sort1_sql = "count ASC"; $sort1_ph = "&#8593; Hits";}
		else if ($_GET['sort1'] == "hitsdesc") {$sort1_sql = "count DESC"; $sort1_ph = "&#8595; Hits";}
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
		if ($_GET['sort2'] == "reasonasc") {$sort2_sql = ", reason ASC"; $sort2_ph = "&#8593; Reason";}
		else if ($_GET['sort2'] == "reasondesc") {$sort2_sql = ", reason DESC"; $sort2_ph = "&#8595; Reason";}
		else if ($_GET['sort2'] == "newest") {$sort2_sql = ", DATE(timestamp) ASC"; $sort2_ph = "&#8593; Date";}
		else if ($_GET['sort2'] == "oldest") {$sort2_sql = ", DATE(timestamp) DESC"; $sort2_ph = "&#8595; Date";}
		else if ($_GET['sort2'] == "countryasc") {$sort2_sql = ", country ASC"; $sort2_ph = "&#8593; Country";}
		else if ($_GET['sort2'] == "countrydesc") {$sort2_sql = ", country DESC"; $sort2_ph = "&#8595; Country";}
		else if ($_GET['sort2'] == "ipasc") {$sort2_sql = ", ip ASC"; $sort2_ph = "&#8593; IP";}
		else if ($_GET['sort2'] == "ipdesc") {$sort2_sql = ", ip DESC"; $sort2_ph = "&#8595; IP";}
		else if ($_GET['sort1'] == "userasc") {$sort2_sql = ", username ASC"; $sort2_ph = "&#8593; Username";}
		else if ($_GET['sort1'] == "userdesc") {$sort2_sql = ", username DESC"; $sort2_ph = "&#8595; Username";}
		else if ($_GET['sort2'] == "hitsasc") {$sort2_sql = ", count ASC"; $sort2_ph = "&#8593; Hits";}
		else if ($_GET['sort2'] == "hitsdesc") {$sort2_sql = ", count DESC"; $sort2_ph = "&#8595; Hits";}
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
		$orderby = "ORDER BY timestamp DESC";
	}

	if (isset($_GET['group'])) {
		$group_val = $_GET['group'];
		$group_page = "&group=".$group_val;
		if ($_GET['group'] == "username") {$group_sql = "GROUP BY username"; $group_ph = "By Username";}
		else if ($_GET['group'] == "ip") {$group_sql = "GROUP BY INET6_NTOA(ip)"; $group_ph = "By IP";}
		else if ($_GET['group'] == "country") {$group_sql = "GROUP BY country"; $group_ph = "By Country";}
		else if ($_GET['group'] == "port") {$group_sql = "GROUP BY port"; $group_ph = "By Port";}
		else if ($_GET['group'] == "reason") {$group_sql = "GROUP BY reason"; $group_ph = "By Reason";}
		else if ($_GET['group'] == "date") {$group_sql = "GROUP BY dts"; $group_ph = "By Date";}
		else {unset($_GET['group']); $group_sql = "GROUP BY id"; $group_ph = "Group";}
	} else {
		$group_val = "";
		$group_sql = "GROUP BY id";
		$group_ph = "Group";
		$group_page = "";
		$group_table = ""; 
	}

	if ($search=="") {$search_ph="";} else {$search_ph=$search;}
	if ($reason=="") {$reason_ph="Reason";} else {$reason_ph=$reason;}
	if ($port=="") {$port_ph="Port";} else {$port_ph=$port;}
	if ($acc=="") {$acc_ph="Status";} else {if ($acc==0) {$acc_ph="Rejected";} else {$acc_ph="Accepted";}}

	echo "
	<div class='section'>
		<h3>Logons</h3>
		<span style='font-size:0.8em;'>The logons page displays logon attempts. This is the fastest growing data set due to constant IMAP connections. Its highly advised to expire logon data frequently.</span>
		<div style='line-height:24px;'>
			<form autocomplete='off' id='myForm' action='logons.php' method='GET'><br>
				<select name='group' onchange='this.form.submit()'>
					<option value='".$group_val."'>".$group_ph."</option>
					<option value='username'>By Username</option>
					<option value='ip'>By IP</option>
					<option value='country'>By Country</option>
					<option value='port'>By Port</option>
					<option value='reason'>By Reason</option>
					<option value='date'>By Date</option>
				</select>
				<select name='sort1' onchange='this.form.submit()'>
					<option value='".$sort1_val."'>".$sort1_ph."</option>
					<option value='reasonasc'>&#8593; Reason</option>
					<option value='reasondesc'>&#8595; Reason</option>
					<option value='newest'>&#8593; Date</option>
					<option value='oldest'>&#8595; Date</option>
					<option value='countryasc'>&#8593; Country</option>
					<option value='countrydesc'>&#8595; Country</option>
					<option value='ipasc'>&#8593; IP</option>
					<option value='ipdesc'>&#8595; IP</option>
					<option value='userasc'>&#8593; Username</option>
					<option value='userdesc'>&#8595; Username</option>";
	if ($group_val) {
		echo "
					<option value='hitsasc'>&#8593; Hits</option>
					<option value='hitsdesc'>&#8595; Hits</option>";
	}
	echo "
				</select>";
	if (isset($_GET['sort1'])) {
		echo "
				<select name='sort2' onchange='this.form.submit()'>
					<option value='".$sort2_val."'>".$sort2_ph."</option>
					<option value='reasonasc'>&#8593; Reason</option>
					<option value='reasondesc'>&#8595; Reason</option>
					<option value='newest'>&#8593; Date</option>
					<option value='oldest'>&#8595; Date</option>
					<option value='countryasc'>&#8593; Country</option>
					<option value='countrydesc'>&#8595; Country</option>
					<option value='ipasc'>&#8593; IP</option>
					<option value='ipdesc'>&#8595; IP</option>
					<option value='userasc'>&#8593; Username</option>
					<option value='userdesc'>&#8595; Username</option>";
		if ($group_val) {
			echo "
					<option value='hitsasc'>&#8593; Hits</option>
					<option value='hitsdesc'>&#8595; Hits</option>";
		}
		echo "
				</select>";
	}
	echo "
				<select name='port' onchange='this.form.submit()'>
					<option value='".$port."'>".$port_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(port) AS port_title FROM hm_log_logon;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
					<option value=".$row['port_title'].">".$row['port_title']."</option>";
	}
	echo "
				</select>
				<select name='reason' onchange='this.form.submit()'>
					<option value='".$reason."'>".$reason_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(reason) AS reason_title FROM hm_log_logon;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
					<option value=".$row['reason_title'].">".$row['reason_title']."</option>";
	}
	echo "
				</select>
				<select name='acc' onchange='this.form.submit()'>
					<option value='".$acc."'>".$acc_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(acc) AS acc_title FROM hm_log_logon;");
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

	if ($search==""){$search_SQL = "";} else {$search_SQL = " AND (INET6_NTOA(ip) LIKE '".$search."%' OR username LIKE '%".$search."%' OR country LIKE '%".$search."%')";}
	if ($port==""){$port_SQL = "";} else {$port_SQL = " AND port='".$port."'";}
	if ($reason==""){$reason_SQL = "";} else {$reason_SQL = " AND reason='".$reason."'";}
	if ($acc==""){$acc_SQL = "";} else {$acc_SQL = " AND acc='".$acc."'";}
	if ($dateFrom==""){$dateFrom_SQL = "";} else {$dateFrom_SQL = " AND DATE(timestamp) >= '".$dateFrom."'";}
	if ($dateTo==""){$dateTo_SQL = "";} else {$dateTo_SQL = " AND DATE(timestamp) <= '".$dateTo."'";}

	$offset = ($page-1) * $no_of_records_per_page;
	
	if ($group_val) {
		if ($group_val=="date") {$distinctColumn = "DATE(timestamp)";} else {$distinctColumn = $group_val;}
		$total_pages_sql = $pdo->prepare("
			SELECT COUNT(DISTINCT(".$distinctColumn.")) 
			FROM hm_log_logon
			WHERE LENGTH(ip) > 0 ".$search_SQL.$port_SQL.$reason_SQL.$acc_SQL.$dateFrom_SQL.$dateTo_SQL.";
		");
	} else {
		$total_pages_sql = $pdo->prepare("
			SELECT Count( * ) AS count 
			FROM hm_log_logon 
			WHERE LENGTH(ip) > 0".$search_SQL.$port_SQL.$reason_SQL.$acc_SQL.$dateFrom_SQL.$dateTo_SQL.";
		");
	}
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql = $pdo->prepare("
		SELECT *, COUNT(id) AS count, INET6_NTOA(ip) AS ipa, DATE(timestamp) AS dts
		FROM hm_log_logon 
		WHERE LENGTH(id) > 0" .$search_SQL.$port_SQL.$reason_SQL.$acc_SQL.$dateFrom_SQL.$dateTo_SQL."
		".$group_sql."
		".$orderby.$sort1_sql.$sort2_sql."
		LIMIT ".$offset.", ".$no_of_records_per_page
	);
	$sql->execute();

	if ($search==""){$search_res="";} else {$search_res=" for search term \"<b>".$search."</b>\"";}
  	if ($port==""){$port_res="";} else {$port_res=" via port \"<b>".$port."</b>\"";}
  	if ($reason==""){$reason_res="";} else {$reason_res=" with reason \"<b>".$reason."</b>\"";}
	if ($acc==""){$acc_res="";} else {$acc_res=" having status \"<b>".$acc_ph."</b>\"";}
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
			echo "No results ".$search_res.$port_res.$reason_res.$acc_res.$date_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.$port_res.$reason_res.$acc_res.$date_res.": ".number_format($total_rows)." Record".$singular." ".$pagination."<br></span>
		<div class='div-table'>
			<div class='div-table-row-header'>
				<div class='div-table-col'>Timestamp</div>
				<div class='div-table-col'>IP</div>
				<div class='div-table-col'>Port</div>
				<div class='div-table-col'>Country</div>
				<div class='div-table-col center'>Reason</div>
				<div class='div-table-col center'>Username</div>
				<div class='div-table-col center'>Status</div>";
		if ($group_val) {
		echo "
				<div class='div-table-col center'>Hits</div>";
		}
		echo "
			</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			echo "
			<div class='div-table-row'>
				<div class='div-table-col center' data-column='Timestamp'>".date("y/m/d H:i:s", strtotime($row['timestamp']))."</div>
				<div class='div-table-col center' data-column='IP'><a href='./data.php?search=".$row['ipa']."'>".$row['ipa']."</a>&nbsp;</div>
				<div class='div-table-col center' data-column='Port'>".$row['port']."</a>&nbsp;</div>
				<div class='div-table-col' data-column='Country'><a href='./data.php?search=".$row['country']."'>".$row['country']."</a>&nbsp;</div>
				<div class='div-table-col' data-column='Reason'>".$row['reason']."&nbsp;</div>
				<div class='div-table-col' data-column='HELO'><a href='./logons.php?search=".$row['username']."'>".$row['username']."</a>&nbsp;</div>";
			if ($row['acc']==1) {$display_status="Accepted";} else {$display_status="Rejected";}
			echo "
				<div class='div-table-col center' data-column='Status'>".$display_status."</div>";
			if ($group_val) {
				echo "
				<div class='div-table-col right' data-column='Hits'><b>".number_format($row['count'])."</b>&nbsp;</div>";
			}
			echo "
			</div>";
		}
		echo "
		</div>"; // End table

		if ($search==""){$search_page = "";} else {$search_page = "&search=".$search;}
		if ($reason==""){$reason_page = "";} else {$reason_page = "&reason=".$reason;}
		if ($acc==""){$acc_page = "";} else {$acc_page = "&acc=".$acc;}
		if ($port==""){$port_page = "";} else {$port_page = "&port=".$port;}
		if ($dateFrom==""){$dateFrom_page = "";} else {$dateFrom_page = "&dateFrom=".$dateFrom;}
		if ($dateTo==""){$dateTo_page = "";} else {$dateTo_page = "&dateTo=".$dateTo;}
		
		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page.$port_page.$reason_page.$acc_page.$sort1_page.$sort2_page.$dateFrom_page.$dateTo_page.$group_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page.$port_page.$reason_page.$acc_page.$sort1_page.$sort2_page.$dateFrom_page.$dateTo_page.$group_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page.$port_page.$reason_page.$acc_page.$sort1_page.$sort2_page.$dateFrom_page.$dateTo_page.$group_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$port_page.$search_page.$reason_page.$acc_page.$sort1_page.$sort2_page.$dateFrom_page.$dateTo_page.$group_page."\">Last</a></li>";}
			echo "
			</ul>
		</span>";
		}
	}

?>

	</div> <!-- end of section -->

<?php include("foot.php") ?>