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
	if (isset($_GET['search'])) {
		$search = $_GET['search'];
		$search_SQL = "WHERE country LIKE '%".$search."%' OR ipaddress LIKE '%".$search."%'";
		$search_ph = $search;
		$search_hidden = "<input type='hidden' name='search' value='".$search."'>";
		$search_page = "&search=".$search;
	} else {
		$search = "";
		$search_SQL = "";
		$search_ph = "";
		// $search_hidden = "";
		$search_page = "";
	}
	if (isset($_GET['clear'])) {
		redirect("./ids.php");
	}
	
	if (isset($_GET['sort1'])) {
		$sort1_val = $_GET['sort1'];
		$sort1_page = "&sort1=".$sort1_val;
		$sort1_hidden = "<input type='hidden' name='sort1' value='".$sort1_val."'>";
		if ($_GET['sort1'] == "hitsasc") {$sort1_sql = "hits ASC"; $sort1_ph = "&#8593; Hits";}
		else if ($_GET['sort1'] == "hitsdesc") {$sort1_sql = "hits DESC"; $sort1_ph = "&#8595; Hits";}
		else if ($_GET['sort1'] == "newest") {$sort1_sql = "DATE(timestamp) ASC"; $sort1_ph = "&#8593; Date";}
		else if ($_GET['sort1'] == "oldest") {$sort1_sql = "DATE(timestamp) DESC"; $sort1_ph = "&#8595; Date";}
		else if ($_GET['sort1'] == "countryasc") {$sort1_sql = "trimcountry ASC"; $sort1_ph = "&#8593; Country";}
		else if ($_GET['sort1'] == "countrydesc") {$sort1_sql = "trimcountry DESC"; $sort1_ph = "&#8595; Country";}
		else if ($_GET['sort1'] == "ipasc") {$sort1_sql = "INET_ATON(ipaddress) ASC"; $sort1_ph = "&#8593; IP";}
		else if ($_GET['sort1'] == "ipdesc") {$sort1_sql = "INET_ATON(ipaddress) DESC"; $sort1_ph = "&#8595; IP";}
		else {unset($_GET['sort1']); $sort1_sql = ""; $sort1_ph = "Sort";}
	} else {
		$sort1_val = "";
		$sort1_sql = "";
		$sort1_ph = "Sort";
		$sort1_page = "";
		// $sort1_hidden = "";
	}
	if (isset($_GET['sort2'])) {
		$sort2_val = $_GET['sort2'];
		$sort2_page = "&sort2=".$sort2_val;
		$sort2_hidden = "<input type='hidden' name='sort2' value='".$sort2_val."'>";
		if ($_GET['sort2'] == "hitsasc") {$sort2_sql = ", hits ASC"; $sort2_ph = "&#8593; Hits";}
		else if ($_GET['sort2'] == "hitsdesc") {$sort2_sql = ", hits DESC"; $sort2_ph = "&#8595; Hits";}
		else if ($_GET['sort2'] == "newest") {$sort2_sql = ", timestamp ASC"; $sort2_ph = "&#8593; Date";}
		else if ($_GET['sort2'] == "oldest") {$sort2_sql = ", timestamp DESC"; $sort2_ph = "&#8595; Date";}
		else if ($_GET['sort2'] == "countryasc") {$sort2_sql = ", trimcountry ASC"; $sort2_ph = "&#8593; Country";}
		else if ($_GET['sort2'] == "countrydesc") {$sort2_sql = ", trimcountry DESC"; $sort2_ph = "&#8595; Country";}
		else if ($_GET['sort2'] == "ipasc") {$sort2_sql = ", INET_ATON(ipaddress) ASC"; $sort2_ph = "&#8593; IP";}
		else if ($_GET['sort2'] == "ipdesc") {$sort2_sql = ", INET_ATON(ipaddress) DESC"; $sort2_ph = "&#8595; IP";}
		else {unset($_GET['sort2']); $sort2_sql = ""; $sort2_ph = "Sort";}
	} else {
		$sort2_val = "";
		$sort2_sql = "";
		$sort2_ph = "Sort";
		$sort2_page = "";
		// $sort2_hidden = "";
	}
	if ((isset($_GET['sort1'])) || (isset($_GET['sort2']))) {
		$orderby = "ORDER BY ";
	} else {
		$orderby = "ORDER BY timestamp DESC";
	}

	echo "
	<div class='section'>
		<div style='line-height:24px;display:inline-block;'>
			<form autocomplete='off' action='ids.php' method='GET'>
				<select name='sort1' onchange='this.form.submit()'>
					<option value='".$sort1_val."'>".$sort1_ph."</option>
					<option value='hitsasc'>&#8593; Hits</option>
					<option value='hitsdesc'>&#8595; Hits</option>
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
					<option value='hitsasc'>&#8593; Hits</option>
					<option value='hitsdesc'>&#8595; Hits</option>
					<option value='newest'>&#8593; Date</option>
					<option value='oldest'>&#8595; Date</option>
					<option value='countryasc'>&#8593; Country</option>
					<option value='countrydesc'>&#8595; Country</option>
					<option value='ipasc'>&#8593; IP</option>
					<option value='ipdesc'>&#8595; IP</option>
				</select>";
	}
	echo "
				<input type='text' size='20' id='autocomplete' name='search' placeholder='Search Country or IP...' value='".$search_ph."'>
				<input type='submit' value='Search'>
				<button class='button' type='submit' name='clear'>Reset</button>
			</form>
		</div>
	</div>
	
	<div class='section'>";


	$offset = ($page-1) * $no_of_records_per_page;
	
	$total_pages_sql = $pdo->prepare("
		SELECT Count( * ) AS count 
		FROM hm_ids 
		".$search_SQL."
	");
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql_query = "
		SELECT 
			TRIM(BOTH '\"' FROM country) AS trimcountry,
			country,
			ipaddress,
			timestamp,
			hits
		FROM hm_ids 
		".$search_SQL."
		".$orderby.$sort1_sql.$sort2_sql."
		LIMIT ".$offset.", ".$no_of_records_per_page;
	$sql = $pdo->prepare($sql_query);
	$sql->execute();

	if ($search==""){
		$search_res="";
	} else {
		$search_res=" for \"<b>".$search."</b>\"";
	}

	if ($total_pages < 2){
		$pagination = "";
	} else {
		$pagination = "(Page: ".number_format($page)." of ".number_format($total_pages).")";
	}

	if ($total_rows == 1){$singular = '';} else {$singular= 's';}
	if ($total_rows == 0){
		if ($search == "" && $sort1_val == "" && $sort2_val == ""){
			echo "Please enter a search term";
		} else {
			echo "No results ".$search_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.": ".number_format($total_rows)." Record".$singular." ".$pagination."<br></span>
		<div class='div-table'>
			<div class='div-table-row-header'>
				<div class='div-table-col'>IP Address</div>
				<div class='div-table-col'>Hits</div>
				<div class='div-table-col'>Last</div>
				<div class='div-table-col'>Country</div>
			</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			echo "
			<div class='div-table-row'>
				<div class='div-table-col center mobile-bold' data-column='IP Address'><a href='./data.php?search=".$row['ipaddress']."'>".$row['ipaddress']."</a></div>
				<div class='div-table-col center' data-column='Hits'>".number_format($row['hits'])."</div>
				<div class='div-table-col center' data-column='Last'>".date("y/m/d H:i:s", strtotime($row['timestamp']))."</div>
				<div class='div-table-col center' data-column='Country'><a href='https://geoip.dynu.net/map/".$row['ipaddress']."' target='_blank'>".$row['trimcountry']."</a></div>
			</div>";
		}
		echo "
		</div>"; // End table

		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page.$sort1_page.$sort2_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page.$sort1_page.$sort2_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page.$sort1_page.$sort2_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$search_page.$sort1_page.$sort2_page."\">Last</a></li>";}
			echo "
			</ul>
		</span>";
		}
	}

	// JS autocomplete
	echo "
	<script>
	$(function() {
		$('#autocomplete').autocomplete({
			source: 'autocomplete.php',
			select: function( event, ui ) {
				event.preventDefault();
				$('#autocomplete').val(ui.item.value);
			}
		});
	});
	</script>";

?>

	</div> <!-- end of section -->

<?php include("foot.php") ?>