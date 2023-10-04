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
		if ($_GET['search'] <> "") {
			$search = $_GET['search'];
			$search_SQL = "AND (INET_NTOA(rangelowerip1) LIKE '%".$search."%' OR rangename LIKE '%".$search."%')";
			$search_ph = $search;
			$search_page = "&search=".$search;
		} else {
			$search = "";
			$search_SQL = "";
			$search_ph = "";
			$search_page = "";
		}
	} else {
		$search = "";
		$search_SQL = "";
		$search_ph = "";
		$search_page = "";
	}
	if (isset($_GET['clear'])) {
		redirect("./autoban.php");
	}
	
	if (isset($_GET['sort'])) {
		$sort_val = $_GET['sort'];
		$sort_page = "&sort=".$sort_val;
		if ($_GET['sort'] == "newest") {$sort_sql = "rangeexpirestime DESC"; $sort_ph = "&#8593; Expiration";}
		else if ($_GET['sort'] == "oldest") {$sort_sql = "rangeexpirestime ASC"; $sort_ph = "&#8595; Expiration";}
		else if ($_GET['sort'] == "priorityasc") {$sort_sql = "rangepriorityid ASC"; $sort_ph = "&#8593; Priority";}
		else if ($_GET['sort'] == "prioritydesc") {$sort_sql = "rangepriorityid DESC"; $sort_ph = "&#8595; Priority";}
		else if ($_GET['sort'] == "descriptionasc") {$sort_sql = "rangename ASC"; $sort_ph = "&#8593; Description";}
		else if ($_GET['sort'] == "descriptiondesc") {$sort_sql = "rangename DESC"; $sort_ph = "&#8595; Description";}
		else if ($_GET['sort'] == "ipasc") {$sort_sql = "rangelowerip1 ASC"; $sort_ph = "&#8593; IP";}
		else if ($_GET['sort'] == "ipdesc") {$sort_sql = "rangelowerip1 DESC"; $sort_ph = "&#8595; IP";}
		else {unset($_GET['sort']); $sort_sql = ""; $sort_ph = "Sort";}
	} else {
		$sort_val = "";
		$sort_sql = "";
		$sort_ph = "Sort";
		$sort_page = "";
	}
	if (isset($_GET['sort'])) {
		$orderby = "ORDER BY ";
	} else {
		$orderby = "ORDER BY rangeexpirestime DESC";
	}
	if(isset($_POST['delete'])){
		if(!empty($_POST['delete'])) {
			$sql_ip = "DELETE FROM hm_securityranges WHERE rangeid = '".$_POST['delete']."';";
			$pdo->exec($sql_ip);
		}
	}

	echo "
	<div class='section'>
		<div style='line-height:24px;display:inline-block;'>
			<form autocomplete='off' action='autoban.php' method='GET'>
				<select name='sort' onchange='this.form.submit()'>
					<option value='".$sort_val."'>".$sort_ph."</option>
					<option value='newest'>&#8593; Expiration</option>
					<option value='oldest'>&#8595; Expiration</option>
					<option value='priorityasc'>&#8593; Priority</option>
					<option value='prioritydesc'>&#8595; Priority</option>
					<option value='descriptionasc'>&#8593; Description</option>
					<option value='descriptiondesc'>&#8595; Description</option>
					<option value='ipasc'>&#8593; IP</option>
					<option value='ipdesc'>&#8595; IP</option>
				</select>";
	
	echo "
				<input type='text' size='20' id='autocomplete' name='search' placeholder='Search Description or IP...' value='".$search_ph."'>
				<input type='submit' value='Search'>
				<button class='button' type='submit' name='clear'>Reset</button>
			</form>
		</div>
	</div>
	
	<div class='section'>";


	$offset = ($page-1) * $no_of_records_per_page;
	
	$total_pages_sql = $pdo->prepare("
		SELECT Count( * ) AS count 
		FROM hm_securityranges		
		WHERE rangeexpires = 1 ".$search_SQL
	);
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql_query = "
		SELECT *, INET_NTOA(rangelowerip1) AS ipaddress
		FROM hm_securityranges
		WHERE rangeexpires = 1 ".$search_SQL."
		".$orderby.$sort_sql."
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
		if ($search == "" && $sort_val == ""){
			echo "Please enter a search term";
		} else {
			echo "No results ".$search_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.": ".number_format($total_rows)." Record".$singular." ".$pagination."<br></span>
		<form autocomplete='off' id='domForm' action='".$_SERVER['REQUEST_URI']."' method='POST'>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>IP Address</div>
					<div class='div-table-col'>Description</div>
					<div class='div-table-col'>Priority</div>
					<div class='div-table-col'>Expiration (minutes)</div>
					<div class='div-table-col'>Delete</div>
				</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$expiry = new DateTime($row['rangeexpirestime']);
			$timenow = new DateTime(date("Y-m-d H:i:s", time()));
			$expireTime = date_diff($timenow, $expiry, true);
			$minutes = $expireTime->days * 24 * 60;
			$minutes += $expireTime->h * 60;
			$minutes += $expireTime->i;
			echo "
				<div class='div-table-row'>
					<div class='div-table-col center mobile-bold' data-column='IP Address'><a href='./data.php?search=".$row['ipaddress']."'>".$row['ipaddress']."</a>&nbsp;</div>
					<div class='div-table-col center' data-column='Description'>".$row['rangename']."&nbsp;</div>
					<div class='div-table-col center' data-column='Priority'>".$row['rangepriorityid']."&nbsp;</div>
					<div class='div-table-col center' data-column='Last'>".number_format($minutes)."&nbsp;</div>
					<div class='div-table-col center' data-column='Delete'>
						<input type='checkbox' style='height:10px;margin:0;' name='delete' value='".$row['rangeid']."' onClick='return confirmSubmit()' onchange='submitFunction()'>
					</div>
				</div>";
		}
		echo "
			</div>
		</form>"; // End table

		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page.$sort_page.$sort2_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page.$sort_page.$sort2_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page.$sort_page.$sort2_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$search_page.$sort_page.$sort2_page."\">Last</a></li>";}
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
	</script>
	<script>
		function submitFunction() {
			document.getElementById('domForm').submit();
		}
	</script>
	<script>
		function confirmSubmit(){
			var agree=confirm(\"Are you sure you to delete this record?\");
			if (agree)
				return true ;
			else
				return false ;
		}
	</script>";

?>

	</div> <!-- end of section -->

<?php include("foot.php") ?>