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
	if (isset($_GET['clear'])) {header("Location: messages.php");}
	if (isset($_GET['status'])) {$status = trim($_GET['status']);} else {$status = "";}
	if (isset($_GET['dateFrom'])) {$dateFrom = $_GET['dateFrom'];} else {$dateFrom = "";}
	if (isset($_GET['dateTo'])) {$dateTo = $_GET['dateTo'];} else {$dateTo = "";}
	if (isset($_GET['from'])) {$from = $_GET['from'];} else {$from = "";}
	if (isset($_GET['to'])) {$to = $_GET['to'];} else {$to = "";}

	if ($search=="") {$search_ph="";} else {$search_ph=$search;}
	if ($status=="") {$status_ph="Status";} else {$status_ph=$status;}

	echo "
	<div class='section'>
		<h3>Messages</h3>
		<span style='font-size:0.8em;'>The Messages Log contains logged message metadata.
		Click on the subject or ID to view the message. You can search by date or by metadata or message body content.
		Its recommended to periodically expire message data. Bolded rows = spam action taken.</span>
		<div style='line-height:24px;'>
			<form autocomplete='off' id='myForm' action='messages.php' method='GET'><br>";
	echo "
				<select name='status' onchange='this.form.submit()'>
					<option value='".$status."'>".$status_ph."</option>";
	$sql = $pdo->prepare("SELECT DISTINCT(statuscode) AS status_title FROM hm_log_msg;");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
					<option value=".$row['status_title'].">".$row['status_title']."</option>";
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

	if ($search==""){$search_SQL = "";} else {$search_SQL = " AND (envelopeFrom LIKE '%".$search."%' OR envelopeTo LIKE '%".$search."%' OR subject REGEXP '".$search."' OR message REGEXP '".$search."')";}
	if ($dateFrom==""){$dateFrom_SQL = "";} else {$dateFrom_SQL = " AND DATE(timestamp) >= '".$dateFrom."'";}
	if ($dateTo==""){$dateTo_SQL = "";} else {$dateTo_SQL = " AND DATE(timestamp) <= '".$dateTo."'";}
	if ($status==""){$status_SQL = "";} else {$status_SQL = " AND statuscode = '".$status."'";}
	if ($from==""){$from_SQL = "";} else {$from_SQL = " AND envelopeFrom = '".$from."'";}
	if ($to==""){$to_SQL = "";} else {$to_SQL = " AND envelopeTo = '".$to."'";}

	$offset = ($page-1) * $no_of_records_per_page;
	$total_pages_sql = $pdo->prepare("
		SELECT COUNT(*) FROM hm_log_msg
		WHERE LENGTH(id) > 0 ".$search_SQL.$status_SQL.$dateFrom_SQL.$dateTo_SQL.$from_SQL.$to_SQL.";
	");
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql = $pdo->prepare("
		SELECT *, INET6_NTOA(ip) AS ipa FROM hm_log_msg
		WHERE LENGTH(id) > 0 ".$search_SQL.$status_SQL.$dateFrom_SQL.$dateTo_SQL.$from_SQL.$to_SQL."
		ORDER BY id DESC
		LIMIT ".$offset.", ".$no_of_records_per_page.";
	");
	$sql->execute();

	if ($search==""){
		$search_res="";
	} else {
		$search_res=" for search term \"<b>".$search."</b>\"";
	}

	if ($total_pages < 2){
		$pagination = "";
	} else {
		$pagination = "(Page: ".number_format($page)." of ".number_format($total_pages).")";
	}

	if ($total_rows == 1){$singular = '';} else {$singular= 's';}
	if ($total_rows == 0){
		if ($search == "" && $status == "" && $dateFrom == "" && $dateTo == ""){
			echo "Please enter a search term";
		} else {
			echo "No results ".$search_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.": ".number_format($total_rows)." Record".$singular." ".$pagination."<br></span>
		<div class='div-table'>
			<div class='div-table-row-header'>
				<div class='div-table-col'>Timestamp</div>
				<div class='div-table-col'>IP</div>
				<div class='div-table-col'>From</div>
				<div class='div-table-col'>To</div>
				<div class='div-table-col'>Subject</div>
				<div class='div-table-col'>Status</div>
				<div class='div-table-col'>ID</div>
			</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			if ($row['spamadjusted'] == 1) {$spamadjusted = " spamadjusted";} else {$spamadjusted = "";}
			echo "
			<div class='div-table-row".$spamadjusted."'>
				<div class='div-table-col center' data-column='Timestamp'>".date("y/m/d H:i:s", strtotime($row['timestamp']))."</div>
				<div class='div-table-col center' data-column='IP'><a href='./data.php?search=".$row['ipa']."'>".$row['ipa']."</a></div>
				<div class='div-table-col uriwidth truncate' data-column='From'><a href='./messages.php?search=".$row['envelopeFrom']."'>".$row['envelopeFrom']."</a>&nbsp;</div>
				<div class='div-table-col uriwidth truncate' data-column='To'><a href='./messages.php?search=".$row['envelopeTo']."'>".$row['envelopeTo']."</a>&nbsp;</div>
				<div class='div-table-col uriwidth truncate' data-column='Subject'><a href='./msg.php?msgid=".$row['id']."'>".$row['subject']."</a>&nbsp;</div>";
			if ($row['statuscode'] != 250) {
				echo "
				<div class='div-table-col center highlight' data-column='Status'>".$row['statuscode']."</div>";
			} else {
				echo "
				<div class='div-table-col center' data-column='Status'>".$row['statuscode']."</div>";
			}
			echo "
				<div class='div-table-col center' data-column='Message ID'><a href='./msg.php?msgid=".$row['id']."'>".$row['id']."</a></div>
			</div>";
		}
		echo "
		</div>"; // End table

		if ($search==""){$search_page = "";} else {$search_page = "&search=".$search;}
		if ($status==""){$status_page = "";} else {$status_page = "&status=".$status;}
		if ($dateFrom==""){$dateFrom_page = "";} else {$dateFrom_page = "&dateFrom=".$dateFrom;}
		if ($dateTo==""){$dateTo_page = "";} else {$dateTo_page = "&dateTo=".$dateTo;}
		
		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page.$status_page.$dateFrom_page.$dateTo_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page.$status_page.$dateFrom_page.$dateTo_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page.$status_page.$dateFrom_page.$dateTo_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$search_page.$status_page.$dateFrom_page.$dateTo_page."\">Last</a></li>";}
			echo "
			</ul>
		</span>";
		}
	}

	echo "
	</div> <!-- end of section -->";

	include_once("foot.php");
?>
